<?php

namespace App\Services\Banking;

use App\Models\Banking\Account;
use App\Models\Banking\Transaction;
use App\Models\Document\Document;
use App\Models\Setting\Category;
use App\Jobs\Banking\CreateBankingDocumentTransaction;
use App\Jobs\Banking\CreateTransaction;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class SingaporeReconciliationEngine
{
    /**
     * Run smart matching across a list of parsed bank transactions.
     *
     * @param array $parsedTransactions
     * @param int|null $accountId
     * @return array
     */
    public function match(array $parsedTransactions, ?int $accountId = null): array
    {
        $account = $accountId ? Account::find($accountId) : Account::enabled()->first();

        // 1. Load active unpaid/partial invoices
        $invoices = Document::invoice()
            ->notPaid()
            ->with(['contact', 'totals', 'transactions'])
            ->get();

        // 2. Load active unpaid/partial bills
        $bills = Document::bill()
            ->notPaid()
            ->with(['contact', 'totals', 'transactions'])
            ->get();

        // 3. Load categories for auto-classification
        $expenseCategories = Category::where('type', 'expense')->enabled()->get();
        $incomeCategories = Category::where('type', 'income')->enabled()->get();

        $matchedTransactions = [];
        $highConfidenceCount = 0;
        $totalMatchedAmount = 0.0;

        foreach ($parsedTransactions as $tx) {
            $suggestion = $this->evaluateTransaction(
                $tx,
                $invoices,
                $bills,
                $expenseCategories,
                $incomeCategories,
                $account
            );

            if ($suggestion['confidence'] >= 85) {
                $highConfidenceCount++;
                $totalMatchedAmount += $tx['amount'];
            }

            $tx['suggestion'] = $suggestion;
            $matchedTransactions[] = $tx;
        }

        return [
            'account' => $account ? [
                'id' => $account->id,
                'name' => $account->name,
                'number' => $account->number,
                'bank_name' => $account->bank_name,
                'currency_code' => $account->currency_code,
            ] : null,
            'total_items' => count($matchedTransactions),
            'high_confidence_count' => $highConfidenceCount,
            'high_confidence_amount' => round($totalMatchedAmount, 2),
            'transactions' => $matchedTransactions,
            'categories' => [
                'expense' => $expenseCategories->map(fn($c) => ['id' => $c->id, 'name' => $c->name]),
                'income' => $incomeCategories->map(fn($c) => ['id' => $c->id, 'name' => $c->name]),
            ],
        ];
    }

    /**
     * Evaluate a single transaction against invoices, bills, and rules.
     */
    protected function evaluateTransaction(
        array $tx,
        Collection $invoices,
        Collection $bills,
        Collection $expenseCategories,
        Collection $incomeCategories,
        ?Account $account
    ): array {
        $amount = (float) $tx['amount'];
        $desc = strtoupper($tx['description'] . ' ' . $tx['reference']);
        $txUen = $tx['uen'] ?? null;
        $txInvNo = $tx['invoice_number'] ?? null;

        // INCOMING FUNDS (Deposits / Credits)
        if ($tx['type'] === 'income') {
            // Check Invoices
            foreach ($invoices as $inv) {
                $invDue = round((float) ($inv->amount - $inv->paid), 2);
                $invNo = strtoupper($inv->document_number);
                $custName = strtoupper($inv->contact->name ?? '');
                $custUen = strtoupper($inv->contact->tax_number ?? '');

                // Case 1: Exact Invoice Number match + Amount match
                if ($txInvNo && str_contains($txInvNo, preg_replace('/[^0-9]/', '', $invNo)) && abs($invDue - $amount) < 0.05) {
                    return [
                        'action' => 'match_invoice',
                        'confidence' => 100,
                        'badge' => 'Exact Invoice Match',
                        'badge_color' => 'emerald',
                        'target_type' => 'invoice',
                        'target_id' => $inv->id,
                        'target_number' => $inv->document_number,
                        'target_entity' => $inv->contact->name ?? 'Customer',
                        'target_amount' => $invDue,
                        'reason' => "Invoice #{$inv->document_number} matched by reference and exact amount of S$" . number_format($amount, 2),
                        'category_id' => $inv->category_id,
                    ];
                }

                // Case 2: Customer UEN match + Amount match
                if ($txUen && !empty($custUen) && $txUen === $custUen && abs($invDue - $amount) < 0.05) {
                    return [
                        'action' => 'match_invoice',
                        'confidence' => 95,
                        'badge' => 'PayNow UEN Match',
                        'badge_color' => 'emerald',
                        'target_type' => 'invoice',
                        'target_id' => $inv->id,
                        'target_number' => $inv->document_number,
                        'target_entity' => $inv->contact->name ?? 'Customer',
                        'target_amount' => $invDue,
                        'reason' => "Customer UEN ({$custUen}) verified on PayNow with exact invoice balance of S$" . number_format($amount, 2),
                        'category_id' => $inv->category_id,
                    ];
                }

                // Case 3: Customer Name contained in description + Amount match
                if (!empty($custName) && str_contains($desc, $custName) && abs($invDue - $amount) < 0.05) {
                    return [
                        'action' => 'match_invoice',
                        'confidence' => 90,
                        'badge' => 'Customer Name Match',
                        'badge_color' => 'teal',
                        'target_type' => 'invoice',
                        'target_id' => $inv->id,
                        'target_number' => $inv->document_number,
                        'target_entity' => $inv->contact->name ?? 'Customer',
                        'target_amount' => $invDue,
                        'reason' => "Customer '{$inv->contact->name}' found in bank narrative with matching invoice amount S$" . number_format($amount, 2),
                        'category_id' => $inv->category_id,
                    ];
                }

                // Case 4: Amount match only (Single candidate)
                if (abs($invDue - $amount) < 0.05) {
                    return [
                        'action' => 'match_invoice',
                        'confidence' => 85,
                        'badge' => 'Amount Match',
                        'badge_color' => 'blue',
                        'target_type' => 'invoice',
                        'target_id' => $inv->id,
                        'target_number' => $inv->document_number,
                        'target_entity' => $inv->contact->name ?? 'Customer',
                        'target_amount' => $invDue,
                        'reason' => "Matches open invoice #{$inv->document_number} balance of S$" . number_format($amount, 2),
                        'category_id' => $inv->category_id,
                    ];
                }
            }

            // Fallback for Income: Direct Sales / Revenue
            $defaultIncomeCat = $incomeCategories->firstWhere('name', 'Sales') ?? $incomeCategories->first();
            return [
                'action' => 'create_income',
                'confidence' => 70,
                'badge' => 'Direct Revenue',
                'badge_color' => 'gray',
                'target_type' => 'income',
                'target_id' => null,
                'target_number' => null,
                'target_entity' => $tx['counterparty'] ?: 'Direct Customer',
                'target_amount' => $amount,
                'reason' => 'Direct bank receipt without an open invoice. Record as direct sales income.',
                'category_id' => $defaultIncomeCat?->id,
                'category_name' => $defaultIncomeCat?->name ?? 'Sales',
            ];
        }

        // OUTGOING FUNDS (Debits / Withdrawals)
        // Check Bills
        foreach ($bills as $bill) {
            $billDue = round((float) ($bill->amount - $bill->paid), 2);
            $billNo = strtoupper($bill->document_number);
            $vendorName = strtoupper($bill->contact->name ?? '');

            if ((str_contains($desc, $billNo) || (str_contains($desc, $vendorName) && !empty($vendorName))) && abs($billDue - $amount) < 0.05) {
                return [
                    'action' => 'match_bill',
                    'confidence' => 95,
                    'badge' => 'Vendor Bill Match',
                    'badge_color' => 'emerald',
                    'target_type' => 'bill',
                    'target_id' => $bill->id,
                    'target_number' => $bill->document_number,
                    'target_entity' => $bill->contact->name ?? 'Vendor',
                    'target_amount' => $billDue,
                    'reason' => "Matches supplier bill #{$bill->document_number} ({$bill->contact->name})",
                    'category_id' => $bill->category_id,
                ];
            }
        }

        // Singapore Rule-Based Auto-Categorization
        $rule = $this->classifySingaporeExpense($desc, $expenseCategories);
        if ($rule) {
            return [
                'action' => 'create_expense',
                'confidence' => $rule['confidence'],
                'badge' => $rule['badge'],
                'badge_color' => $rule['badge_color'],
                'target_type' => 'expense',
                'target_id' => null,
                'target_number' => null,
                'target_entity' => $rule['vendor'],
                'target_amount' => $amount,
                'reason' => $rule['reason'],
                'category_id' => $rule['category_id'],
                'category_name' => $rule['category_name'],
            ];
        }

        // Fallback for General Business Expense
        $defaultExpenseCat = $expenseCategories->firstWhere('name', 'General') ?? $expenseCategories->first();
        return [
            'action' => 'create_expense',
            'confidence' => 65,
            'badge' => 'Operating Expense',
            'badge_color' => 'gray',
            'target_type' => 'expense',
            'target_id' => null,
            'target_number' => null,
            'target_entity' => $tx['counterparty'] ?: 'Singapore Vendor',
            'target_amount' => $amount,
            'reason' => 'Direct operating disbursement. Allocate to business expense.',
            'category_id' => $defaultExpenseCat?->id,
            'category_name' => $defaultExpenseCat?->name ?? 'Operating Expenses',
        ];
    }

    /**
     * Singapore-specific expense pattern recognition.
     */
    protected function classifySingaporeExpense(string $desc, Collection $categories): ?array
    {
        // 1. CPF (Central Provident Fund Board)
        if (preg_match('/\b(CPF|CENTRAL PROVIDENT|CPF BOARD)\b/i', $desc)) {
            $cat = $this->findCategory($categories, ['CPF', 'Payroll', 'Salaries', 'Employee', 'Wages']);
            return [
                'confidence' => 95,
                'badge' => 'CPF Statutory',
                'badge_color' => 'purple',
                'vendor' => 'Central Provident Fund Board (CPF)',
                'reason' => 'Mandatory Singapore statutory pension & employee CPF contribution.',
                'category_id' => $cat?->id,
                'category_name' => $cat?->name ?? 'Employee Benefits (CPF)',
            ];
        }

        // 2. IRAS (Taxation / GST / Corporate Tax)
        if (preg_match('/\b(IRAS|INLAND REVENUE)\b/i', $desc)) {
            $cat = $this->findCategory($categories, ['Tax', 'Taxes', 'GST', 'IRAS', 'Corporate Tax']);
            return [
                'confidence' => 95,
                'badge' => 'IRAS Tax Payment',
                'badge_color' => 'purple',
                'vendor' => 'Inland Revenue Authority of Singapore (IRAS)',
                'reason' => 'Statutory Singapore tax payment (GST Form 5 or Corporate Income Tax).',
                'category_id' => $cat?->id,
                'category_name' => $cat?->name ?? 'Tax Expense',
            ];
        }

        // 3. Bank Fees, Service Charges, FAST charges
        if (preg_match('/\b(SERVICE CHARGE|FAST FEE|GIRO FEE|ANNUAL FEE|BANK CHARGES|INTEREST CHARGE|PAYNOW FEE|FALL BELOW FEE)\b/i', $desc)) {
            $cat = $this->findCategory($categories, ['Bank', 'Charges', 'Fees', 'Finance']);
            return [
                'confidence' => 95,
                'badge' => 'Bank Fee',
                'badge_color' => 'amber',
                'vendor' => 'Bank Service Fee',
                'reason' => 'Standard Singapore bank account maintenance or electronic transaction fee.',
                'category_id' => $cat?->id,
                'category_name' => $cat?->name ?? 'Bank Charges',
            ];
        }

        // 4. Telco & Utilities
        if (preg_match('/\b(SINGTEL|STARHUB|M1|MYREPUBLIC|SP SERVICES|TUAS POWER|GENECO|SEMBCORP|KEPPEL ELECTRIC)\b/i', $desc, $m)) {
            $cat = $this->findCategory($categories, ['Utilities', 'Telephone', 'Internet', 'Office']);
            return [
                'confidence' => 90,
                'badge' => 'Telco / Utility',
                'badge_color' => 'blue',
                'vendor' => strtoupper($m[1]),
                'reason' => 'Monthly Singapore telecommunication or office utility bill.',
                'category_id' => $cat?->id,
                'category_name' => $cat?->name ?? 'Utilities',
            ];
        }

        // 5. Ground Transport & Courier
        if (preg_match('/\b(GRAB|COMFORTDELGRO|GOJEK|SMRT|TRANSITLINK|SIMPLYGO|NINJA VAN|LALAMOVE)\b/i', $desc, $m)) {
            $cat = $this->findCategory($categories, ['Travel', 'Transport', 'Delivery', 'Logistics']);
            return [
                'confidence' => 90,
                'badge' => 'Transport',
                'badge_color' => 'blue',
                'vendor' => strtoupper($m[1]),
                'reason' => 'Local business transportation, ride-hailing, or dispatch courier service.',
                'category_id' => $cat?->id,
                'category_name' => $cat?->name ?? 'Travel & Transport',
            ];
        }

        // 6. Cloud & IT Subscriptions
        if (preg_match('/\b(AWS|AMAZON WEB SERVICES|GOOGLE|MICROSOFT|ZOOM|SLACK|ATLASSIAN|GITHUB|OPENAI|NOTION|DROPBOX)\b/i', $desc, $m)) {
            $cat = $this->findCategory($categories, ['Software', 'IT', 'Subscriptions', 'Technology']);
            return [
                'confidence' => 90,
                'badge' => 'SaaS / Cloud',
                'badge_color' => 'indigo',
                'vendor' => strtoupper($m[1]),
                'reason' => 'Software subscription, web hosting, or cloud infrastructure charge.',
                'category_id' => $cat?->id,
                'category_name' => $cat?->name ?? 'Software & IT',
            ];
        }

        return null;
    }

    /**
     * Find best matching category by keywords.
     */
    protected function findCategory(Collection $categories, array $keywords): ?Category
    {
        foreach ($keywords as $kw) {
            $found = $categories->first(function ($c) use ($kw) {
                return str_contains(strtolower($c->name), strtolower($kw));
            });
            if ($found) return $found;
        }

        return $categories->first();
    }

    /**
     * Execute a single reconciliation action (Match Invoice, Match Bill, or Create Transaction).
     *
     * @param array $actionData
     * @return array
     */
    public function executeAction(array $actionData): array
    {
        $action = $actionData['action'] ?? null;
        $accountId = (int) ($actionData['account_id'] ?? 1);
        $amount = (float) ($actionData['amount'] ?? 0.0);
        $date = $actionData['date'] ?? now()->format('Y-m-d');
        $desc = $actionData['description'] ?? 'Bank Reconciled Transaction';
        $ref = $actionData['reference'] ?? '';

        $account = Account::find($accountId) ?? Account::enabled()->first();
        if (!$account) {
            return ['success' => false, 'error' => 'No active bank account found.'];
        }

        try {
            switch ($action) {
                case 'match_invoice':
                    $invoiceId = (int) ($actionData['target_id'] ?? 0);
                    $invoice = Document::invoice()->find($invoiceId);
                    if (!$invoice) {
                        return ['success' => false, 'error' => 'Invoice not found.'];
                    }

                    // Dispatch Document Transaction Job
                    $job = new CreateBankingDocumentTransaction($invoice, [
                        'type' => 'income',
                        'account_id' => $account->id,
                        'amount' => $amount,
                        'currency_code' => $account->currency_code,
                        'currency_rate' => 1.0,
                        'paid_at' => $date,
                        'description' => $desc,
                        'payment_method' => 'paynow',
                        'reference' => $ref,
                    ]);
                    $tx = app()->call([$job, 'handle']);

                    return [
                        'success' => true,
                        'message' => "Invoice #{$invoice->document_number} successfully matched and marked paid (S$" . number_format($amount, 2) . ").",
                        'transaction_id' => $tx->id,
                        'document_status' => $invoice->fresh()->status,
                    ];

                case 'match_bill':
                    $billId = (int) ($actionData['target_id'] ?? 0);
                    $bill = Document::bill()->find($billId);
                    if (!$bill) {
                        return ['success' => false, 'error' => 'Bill not found.'];
                    }

                    $job = new CreateBankingDocumentTransaction($bill, [
                        'type' => 'expense',
                        'account_id' => $account->id,
                        'amount' => $amount,
                        'currency_code' => $account->currency_code,
                        'currency_rate' => 1.0,
                        'paid_at' => $date,
                        'description' => $desc,
                        'payment_method' => 'bank_transfer',
                        'reference' => $ref,
                    ]);
                    $tx = app()->call([$job, 'handle']);

                    return [
                        'success' => true,
                        'message' => "Bill #{$bill->document_number} successfully matched and settled (S$" . number_format($amount, 2) . ").",
                        'transaction_id' => $tx->id,
                        'document_status' => $bill->fresh()->status,
                    ];

                case 'create_income':
                    $categoryId = (int) ($actionData['category_id'] ?? 0);
                    if (!$categoryId) {
                        $categoryId = Category::where('type', 'income')->enabled()->first()?->id ?? 1;
                    }

                    $tx = (new CreateTransaction([
                        'type' => 'income',
                        'account_id' => $account->id,
                        'amount' => $amount,
                        'currency_code' => $account->currency_code,
                        'currency_rate' => 1.0,
                        'paid_at' => $date,
                        'description' => $desc,
                        'category_id' => $categoryId,
                        'payment_method' => 'bank_transfer',
                        'reference' => $ref,
                    ]))->handle();

                    return [
                        'success' => true,
                        'message' => "Direct income recorded: S$" . number_format($amount, 2),
                        'transaction_id' => $tx->id,
                    ];

                case 'create_expense':
                default:
                    $categoryId = (int) ($actionData['category_id'] ?? 0);
                    if (!$categoryId) {
                        $categoryId = Category::where('type', 'expense')->enabled()->first()?->id ?? 1;
                    }

                    $tx = (new CreateTransaction([
                        'type' => 'expense',
                        'account_id' => $account->id,
                        'amount' => $amount,
                        'currency_code' => $account->currency_code,
                        'currency_rate' => 1.0,
                        'paid_at' => $date,
                        'description' => $desc,
                        'category_id' => $categoryId,
                        'payment_method' => 'bank_transfer',
                        'reference' => $ref,
                    ]))->handle();

                    return [
                        'success' => true,
                        'message' => "Expense recorded and categorized: S$" . number_format($amount, 2),
                        'transaction_id' => $tx->id,
                    ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
}
