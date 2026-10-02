<?php

namespace App\Http\Controllers\Marketing;

use Illuminate\Routing\Controller as BaseController;
use App\Utilities\PayNow;
use Illuminate\Http\Request;

class FreeInvoiceGeneratorController extends BaseController
{
    /**
     * Display the Free PayNow Invoice Generator tool.
     */
    public function index()
    {
        return view('landing.tools.free-invoice-generator');
    }

    /**
     * AJAX endpoint to generate dynamic PayNow QR code preview.
     */
    public function generateQr(Request $request)
    {
        $request->validate([
            'uen'          => 'required|string|max:20',
            'company_name' => 'nullable|string|max:100',
            'amount'       => 'nullable|numeric|min:0',
            'invoice_no'   => 'nullable|string|max:50',
        ]);

        $uen = trim($request->input('uen'));
        $companyName = trim($request->input('company_name', 'Merchant'));
        $amount = (float) $request->input('amount', 0);
        $invoiceNo = trim($request->input('invoice_no', 'INV-001'));

        $qrDataUri = PayNow::generateQrCodeDataUri(
            $uen,
            $amount > 0 ? $amount : null,
            $invoiceNo,
            $companyName,
            '2'
        );

        return response()->json([
            'success' => true,
            'qr_code' => $qrDataUri,
            'details' => [
                'uen'          => $uen,
                'company_name' => $companyName,
                'amount'       => number_format($amount, 2),
                'invoice_no'   => $invoiceNo,
            ]
        ]);
    }
}
