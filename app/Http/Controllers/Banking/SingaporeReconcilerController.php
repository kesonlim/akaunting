<?php

namespace App\Http\Controllers\Banking;

use App\Abstracts\Http\Controller;
use App\Models\Banking\Account;
use App\Services\Banking\SingaporeBankParser;
use App\Services\Banking\SingaporeReconciliationEngine;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SingaporeReconcilerController extends Controller
{
    protected SingaporeBankParser $parser;
    protected SingaporeReconciliationEngine $engine;

    public function __construct(SingaporeBankParser $parser, SingaporeReconciliationEngine $engine)
    {
        $this->parser = $parser;
        $this->engine = $engine;

        $this->middleware('permission:read-banking-reconciliations')->only('index', 'downloadSample');
        $this->middleware('permission:create-banking-reconciliations')->only('parse', 'reconcile', 'batchReconcile');
    }

    /**
     * Show Singapore Bank Reconciler landing & upload dashboard.
     */
    public function index()
    {
        $accounts = Account::enabled()->orderBy('name')->get();
        if ($accounts->isEmpty()) {
            try {
                $account = Account::firstOrCreate([
                    'company_id' => company_id(),
                    'number' => '003-902-841-2',
                ], [
                    'type' => 'bank',
                    'name' => 'DBS Corporate Current Account',
                    'currency_code' => 'SGD',
                    'opening_balance' => 0.0,
                    'bank_name' => 'DBS Bank Ltd',
                    'bank_phone' => '+65 6222 2200',
                    'bank_address' => '12 Marina Boulevard, Marina Bay Financial Centre Tower 3, Singapore 018982',
                    'enabled' => 1,
                ]);
                $accounts = Account::where('company_id', company_id())->get();
            } catch (\Throwable $e) {
                // Ignore fallback creation errors
            }
        }

        $selectedAccount = $accounts->first();
        if (!$selectedAccount) {
            $selectedAccount = new Account([
                'id' => 1,
                'name' => 'DBS Corporate Current Account',
                'number' => '003-902-841-2',
                'currency_code' => 'SGD',
            ]);
            $accounts = collect([$selectedAccount]);
        }

        return view('banking.singapore_reconciler.index', compact('accounts', 'selectedAccount'));
    }

    /**
     * Parse statement file / text and run automated match rules.
     */
    public function parse(Request $request)
    {
        $accountId = $request->input('account_id');
        $formatHint = $request->input('format', 'auto');
        $rawContent = '';

        if ($request->hasFile('statement_file')) {
            $file = $request->file('statement_file');
            $rawContent = file_get_contents($file->getRealPath());
        } elseif ($request->filled('statement_text')) {
            $rawContent = $request->input('statement_text');
        } elseif ($request->filled('sample_type')) {
            $rawContent = $this->getSampleContent($request->input('sample_type'));
        }

        if (empty(trim($rawContent))) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'error' => 'Please upload a statement file or paste CSV text.'], 422);
            }
            flash('Please upload a statement file or paste statement data.')->error();
            return redirect()->back();
        }

        // Parse statement
        $parseResult = $this->parser->parse($rawContent, $formatHint);
        if (!$parseResult['success'] || empty($parseResult['transactions'])) {
            $msg = $parseResult['error'] ?? 'Could not detect valid transactions in the provided statement.';
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'error' => $msg], 422);
            }
            flash($msg)->error();
            return redirect()->back();
        }

        // Run smart match engine
        $matchResult = $this->engine->match($parseResult['transactions'], $accountId);

        $data = [
            'success' => true,
            'format' => $parseResult['format'],
            'format_name' => $parseResult['format_name'],
            'total_inflow' => $parseResult['total_inflow'],
            'total_outflow' => $parseResult['total_outflow'],
            'net_movement' => $parseResult['net_movement'],
            'count' => $parseResult['count'],
            'high_confidence_count' => $matchResult['high_confidence_count'],
            'high_confidence_amount' => $matchResult['high_confidence_amount'],
            'transactions' => $matchResult['transactions'],
            'account' => $matchResult['account'],
            'categories' => $matchResult['categories'],
        ];

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json($data);
        }

        $accounts = Account::enabled()->orderBy('name')->get();
        $selectedAccount = Account::find($accountId) ?? $accounts->first();
        if (!$selectedAccount) {
            $selectedAccount = new Account([
                'id' => $accountId ?: 1,
                'name' => 'DBS Corporate Current Account',
                'number' => '003-902-841-2',
                'currency_code' => 'SGD',
            ]);
            $accounts = collect([$selectedAccount]);
        }

        return view('banking.singapore_reconciler.reconcile', array_merge($data, compact('accounts', 'selectedAccount')));
    }

    /**
     * Execute a single reconciliation action.
     */
    public function reconcile(Request $request)
    {
        $validated = $request->validate([
            'action' => 'required|string',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'description' => 'nullable|string',
            'reference' => 'nullable|string',
            'account_id' => 'required|integer',
            'target_id' => 'nullable|integer',
            'category_id' => 'nullable|integer',
        ]);

        $res = $this->engine->executeAction($validated);

        return response()->json($res, $res['success'] ? 200 : 422);
    }

    /**
     * Batch reconcile multiple approved transactions.
     */
    public function batchReconcile(Request $request)
    {
        $matches = $request->input('matches', []);
        if (empty($matches)) {
            return response()->json(['success' => false, 'error' => 'No matches supplied for batch execution.'], 422);
        }

        $successCount = 0;
        $failedCount = 0;
        $errors = [];

        foreach ($matches as $match) {
            $res = $this->engine->executeAction($match);
            if ($res['success']) {
                $successCount++;
            } else {
                $failedCount++;
                $errors[] = $res['error'] ?? 'Execution failed.';
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Successfully reconciled {$successCount} bank transactions in 1 click!",
            'success_count' => $successCount,
            'failed_count' => $failedCount,
            'errors' => $errors,
        ]);
    }

    /**
     * Download or view sample Singapore statement templates.
     */
    public function downloadSample(string $bank)
    {
        $csv = $this->getSampleContent($bank);
        $filename = "singapore_{$bank}_statement_sample.csv";

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    /**
     * Generate authentic Singapore bank statement CSV structures.
     */
    protected function getSampleContent(string $bank): string
    {
        $today = now()->format('d/m/Y');
        $twoDaysAgo = now()->subDays(2)->format('d/m/Y');
        $fiveDaysAgo = now()->subDays(5)->format('d/m/Y');
        $sevenDaysAgo = now()->subDays(7)->format('d/m/Y');

        return match ($bank) {
            'dbs' => "Account Number: 003-902-841-2\nAccount Name: Think Thank Pte Ltd\nCurrency: SGD\n\nTransaction Date,Value Date,Statement Code,Reference,Debit Amount,Credit Amount,Client Reference,Payment Details,Balance\n{$today},{$today},ITR,PAYNOW-202319882K,,1850.00,INV-00002,FAST-PAYNOW Acme Corp SG Pte Ltd INV-00002,28450.00\n{$twoDaysAgo},{$twoDaysAgo},GIRO,CPF-BOARD-E1029,950.00,,CPF CONTRIBUTION,CENTRAL PROVIDENT FUND BOARD E-GIRO,26600.00\n{$fiveDaysAgo},{$fiveDaysAgo},FAST,FAST-STARHUB-991,168.00,,BILL-SH-0926,STARHUB LTD FIBRE & MOBILE BROADBAND,27550.00\n{$sevenDaysAgo},{$sevenDaysAgo},CHG,DBS-SC-MONTHLY,10.00,,SERVICE CHG,DBS BUSINESS ACCOUNT MONTHLY SERVICE CHARGE,27718.00\n",
            'ocbc' => "Transaction Date,Value Date,Description,Currency,Withdrawal,Deposit,Running Balance,Reference No\n{$today},{$today},PAYNOW UEN 202319882K ACME CORP INV-00002,SGD,,1850.00,31450.00,OCBC-PN-882190\n{$twoDaysAgo},{$twoDaysAgo},GIRO CPF CONTRIBUTION FOR STAFF,SGD,950.00,,29600.00,OCBC-GR-102918\n{$fiveDaysAgo},{$fiveDaysAgo},FAST PAYMENT SINGTEL BROADBAND,SGD,188.50,,30550.00,OCBC-FT-991823\n{$sevenDaysAgo},{$sevenDaysAgo},OCBC VELOCITY MAINTENANCE CHARGE,SGD,15.00,,30738.50,OCBC-SC-001923\n",
            'uob' => "Posting Date,Value Date,Transaction Type,Account Number,Description,Debit Amount,Credit Amount,Balance,Customer Reference\n{$today},{$today},PAYNOW,101-309-842-1,PAYNOW-UEN-202319882K ACME CORP,,1850.00,45850.00,INV-00002\n{$twoDaysAgo},{$twoDaysAgo},GIRO,101-309-842-1,CENTRAL PROVIDENT FUND CPF BOARD,950.00,,44000.00,CPF-SEP-2026\n{$fiveDaysAgo},{$fiveDaysAgo},FAST,101-309-842-1,M1 TELECOM FIBRE BROADBAND,145.00,,44950.00,TELCO-M1-88\n{$sevenDaysAgo},{$sevenDaysAgo},SERVICE CHG,101-309-842-1,UOB INFINITY MONTHLY FEE,10.00,,45095.00,UOB-FEE-99\n",
            default => "Date,Description,Reference,Debit,Credit,Balance\n{$today},Customer Payment Acme Corp,INV-00002,,1850.00,18500.00\n{$twoDaysAgo},CPF Board Pension,CPF-SEP,950.00,,16650.00\n{$fiveDaysAgo},Office Internet SP Services,UTIL-09,120.00,,17600.00\n{$sevenDaysAgo},Bank Fee FAST,FEE-01,10.00,,17720.00\n",
        };
    }
}
