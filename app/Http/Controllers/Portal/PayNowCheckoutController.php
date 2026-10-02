<?php

namespace App\Http\Controllers\Portal;

use App\Abstracts\Http\Controller;
use App\Models\Document\Document;
use App\Models\Banking\Transaction;
use App\Models\Setting\Category;
use App\Models\Banking\Account;
use App\Traits\Transactions as TransactionsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PayNowCheckoutController extends Controller
{
    use TransactionsTrait;

    /**
     * Show client-facing instant invoice checkout portal.
     * Accessible by public token/document number without user login.
     *
     * @param string $documentNumber e.g. INV-00001
     */
    public function show(string $documentNumber)
    {
        $invoice = Document::invoice()
            ->where('document_number', $documentNumber)
            ->with(['contact', 'items', 'totals', 'company'])
            ->firstOrFail();

        $company = $invoice->company;
        $companyUen = trim(
            setting('invoice.paynow_uen')
            ?: setting('company.tax_number')
            ?: setting('general.company_tax_number')
            ?: ($company->tax_number ?? '200415432K')
        );

        $companyName = trim(
            setting('invoice.paynow_merchant_name')
            ?: setting('company.name')
            ?: setting('general.company_name')
            ?: ($company->name ?? 'Think Thank Pte Ltd')
        );

        $amountDue = $invoice->amount_due ?? $invoice->amount;
        $isPaid = in_array($invoice->status, ['paid']) || $amountDue <= 0;

        $qrDataUri = '';
        if (!$isPaid && !empty($companyUen)) {
            $qrDataUri = \App\Utilities\PayNow::generateQrCodeDataUri(
                $companyUen,
                $amountDue,
                $invoice->document_number,
                $companyName,
                '2'
            );
        }

        return view('portal.paynow.checkout', compact(
            'invoice',
            'company',
            'companyUen',
            'companyName',
            'amountDue',
            'isPaid',
            'qrDataUri'
        ));
    }

    /**
     * Client confirms payment with bank transaction reference / proof.
     */
    public function confirmPayment(Request $request, string $documentNumber)
    {
        $request->validate([
            'reference' => 'required|string|max:100',
            'notes'     => 'nullable|string|max:500',
        ]);

        $invoice = Document::invoice()
            ->where('document_number', $documentNumber)
            ->firstOrFail();

        $companyId = $invoice->company_id;
        $amount = $invoice->amount_due ?? $invoice->amount;

        // Find or create default account
        $account = Account::where('company_id', $companyId)
            ->where('enabled', 1)
            ->first();

        // Find income category
        $category = Category::where('company_id', $companyId)
            ->where('type', 'income')
            ->first();

        $txNumber = $this->getNextTransactionNumber('income');

        // Create recording transaction
        Transaction::create([
            'company_id'     => $companyId,
            'type'           => 'income',
            'account_id'     => $account ? $account->id : 1,
            'paid_at'        => now()->format('Y-m-d H:i:s'),
            'amount'         => $amount,
            'currency_code'  => $invoice->currency_code ?? 'SGD',
            'currency_rate'  => 1,
            'category_id'    => $category ? $category->id : ($invoice->category_id ?? 1),
            'contact_id'     => $invoice->contact_id,
            'document_id'    => $invoice->id,
            'number'         => $txNumber,
            'description'    => "PayNow Settlement via Client Portal: " . $request->input('reference'),
            'payment_method' => 'paynow',
            'reference'      => $request->input('reference'),
            'enabled'        => 1,
            'created_from'   => 'client-portal-paynow',
        ]);

        // Update invoice status to paid
        $invoice->status = 'paid';
        $invoice->save();

        return redirect()->route('portal.paynow.checkout', $invoice->document_number)
            ->with('success', 'Thank you! Your PayNow payment reference has been recorded and the invoice is marked as paid.');
    }
}
