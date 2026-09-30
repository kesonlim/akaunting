<?php

namespace App\Services\Migration;

use App\Models\Common\Contact;
use App\Models\Setting\Category;
use App\Models\Banking\Account;
use App\Models\Banking\Transaction;
use App\Traits\Transactions as TransactionsTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WaveImporter
{
    use TransactionsTrait;

    /**
     * Import parsed WaveApps data into StraitsLedger.
     *
     * @param string $type     One of: customers, vendors, chart_of_accounts, transactions, invoices
     * @param array  $mapped   Array of mapped rows from WaveCSVParser
     * @return array{imported: int, skipped: int, errors: array, summary: string}
     */
    public function import(string $type, array $mapped): array
    {
        $imported = 0;
        $skipped  = 0;
        $errors   = [];

        foreach ($mapped as $i => $row) {
            try {
                $result = match($type) {
                    'customers'         => $this->importContact($row, 'customer'),
                    'vendors'           => $this->importContact($row, 'vendor'),
                    'chart_of_accounts' => $this->importCategory($row),
                    'transactions'      => $this->importTransaction($row),
                    'invoices'          => $this->importInvoice($row),
                    default             => null,
                };

                if ($result === 'skipped') {
                    $skipped++;
                } elseif ($result !== null) {
                    $imported++;
                }
            } catch (\Throwable $e) {
                $rowNum = $i + 2; // +1 for header, +1 for 1-based
                $errors[] = "Row {$rowNum}: " . $e->getMessage();
                Log::warning("WaveImporter error at row {$rowNum}: " . $e->getMessage());
            }
        }

        $typeLabel = WaveCSVParser::typeLabel($type);
        $summary = "Imported {$imported} {$typeLabel}";
        if ($skipped > 0) $summary .= ", skipped {$skipped} duplicates";
        if (count($errors) > 0) $summary .= ", " . count($errors) . " error(s)";

        return compact('imported', 'skipped', 'errors', 'summary');
    }

    // ──────────────────────────────────────────────────────────
    // CONTACTS (Customers & Vendors)
    // ──────────────────────────────────────────────────────────

    private function importContact(array $row, string $type): string
    {
        if (empty($row['name'])) {
            return 'skipped';
        }

        $companyId = company_id();

        // Check for duplicate (same company + type + name or email)
        $query = Contact::where('company_id', $companyId)
            ->where('type', $type)
            ->where('name', $row['name']);

        if (!empty($row['email'])) {
            $query->orWhere(function ($q) use ($companyId, $type, $row) {
                $q->where('company_id', $companyId)
                  ->where('type', $type)
                  ->where('email', $row['email']);
            });
        }

        if ($query->exists()) {
            return 'skipped';
        }

        Contact::create([
            'company_id'    => $companyId,
            'type'          => $type,
            'name'          => $row['name'],
            'email'         => $row['email'] ?? null,
            'phone'         => $row['phone'] ?? null,
            'address'       => $row['address'] ?? null,
            'city'          => $row['city'] ?? null,
            'state'         => $row['state'] ?? null,
            'zip_code'      => $row['zip_code'] ?? null,
            'country'       => !empty($row['country']) ? strtoupper(substr(trim($row['country']), 0, 2)) : 'SG',
            'currency_code' => !empty($row['currency_code']) ? strtoupper(trim($row['currency_code'])) : 'SGD',
            'website'       => $row['website'] ?? null,
            'tax_number'    => $row['tax_number'] ?? null,
            'enabled'       => 1,
            'created_from'  => 'wave-migration',
        ]);

        return 'imported';
    }

    // ──────────────────────────────────────────────────────────
    // CHART OF ACCOUNTS → Categories
    // ──────────────────────────────────────────────────────────

    private function importCategory(array $row): string
    {
        if (empty($row['name'])) {
            return 'skipped';
        }

        // Only import income/expense types (skip asset, liability, equity)
        $validTypes = ['income', 'expense'];
        $categoryType = $row['type'] ?? 'other';

        if (!in_array($categoryType, $validTypes)) {
            return 'skipped';
        }

        $companyId = company_id();

        // Duplicate check
        if (Category::where('company_id', $companyId)
            ->where('name', $row['name'])
            ->where('type', $categoryType)
            ->exists()) {
            return 'skipped';
        }

        Category::create([
            'company_id'   => $companyId,
            'name'         => $row['name'],
            'type'         => $categoryType,
            'color'        => $categoryType === 'income' ? '#10B981' : '#EF4444',
            'enabled'      => 1,
            'created_from' => 'wave-migration',
        ]);

        return 'imported';
    }

    // ──────────────────────────────────────────────────────────
    // TRANSACTIONS
    // ──────────────────────────────────────────────────────────

    private function importTransaction(array $row): string
    {
        $amount = (float) ($row['amount'] ?? 0);
        if ($amount <= 0) {
            return 'skipped';
        }

        $companyId = company_id();

        // Find or use default account
        $account = Account::where('company_id', $companyId)
            ->where('currency_code', 'SGD')
            ->where('enabled', 1)
            ->first();

        if (!$account) {
            return 'skipped';
        }

        // Find or create category
        $type = $row['type'] ?? 'income';
        $categoryName = $row['category_name'] ?? ($type === 'income' ? 'Wave Import - Income' : 'Wave Import - Expense');
        $category = Category::firstOrCreate(
            ['company_id' => $companyId, 'name' => $categoryName, 'type' => $type],
            ['color' => '#F59E0B', 'enabled' => 1, 'created_from' => 'wave-migration']
        );

        // Parse date
        $paidAt = null;
        if (!empty($row['paid_at'])) {
            try {
                $paidAt = \Carbon\Carbon::parse($row['paid_at'])->format('Y-m-d H:i:s');
            } catch (\Exception $e) {
                $paidAt = now()->format('Y-m-d H:i:s');
            }
        } else {
            $paidAt = now()->format('Y-m-d H:i:s');
        }

        $number = $this->getNextTransactionNumber($type);

        Transaction::create([
            'company_id'     => $companyId,
            'type'           => $type,
            'account_id'     => $account->id,
            'paid_at'        => $paidAt,
            'amount'         => $amount,
            'currency_code'  => $account->currency_code ?? 'SGD',
            'currency_rate'  => 1,
            'category_id'    => $category->id,
            'number'         => $number,
            'description'    => $row['description'] ?? ($row['notes'] ?? 'Imported from WaveApps'),
            'payment_method' => 'bank_transfer',
            'reference'      => $row['wave_id'] ?? null,
            'enabled'        => 1,
            'created_from'   => 'wave-migration',
        ]);

        return 'imported';
    }

    // ──────────────────────────────────────────────────────────
    // INVOICES (grouped by invoice number)
    // ──────────────────────────────────────────────────────────

    private function importInvoice(array $row): string
    {
        // For invoices we just import as income transactions for simplicity
        // Full document creation requires complex multi-step process
        $amount = (float) ($row['price'] ?? 0) * (float) ($row['quantity'] ?? 1);
        if ($amount <= 0) {
            return 'skipped';
        }

        $companyId = company_id();

        $account = Account::where('company_id', $companyId)
            ->where('enabled', 1)
            ->first();

        if (!$account) {
            return 'skipped';
        }

        $category = Category::firstOrCreate(
            ['company_id' => $companyId, 'name' => 'Wave Import - Invoice Income', 'type' => 'income'],
            ['color' => '#10B981', 'enabled' => 1, 'created_from' => 'wave-migration']
        );

        $paidAt = null;
        if (!empty($row['issued_at'])) {
            try { $paidAt = \Carbon\Carbon::parse($row['issued_at'])->format('Y-m-d H:i:s'); }
            catch (\Exception $e) { $paidAt = now()->format('Y-m-d H:i:s'); }
        } else {
            $paidAt = now()->format('Y-m-d H:i:s');
        }

        $number = $this->getNextTransactionNumber('income');

        Transaction::create([
            'company_id'     => $companyId,
            'type'           => 'income',
            'account_id'     => $account->id,
            'paid_at'        => $paidAt,
            'amount'         => $amount,
            'currency_code'  => strtoupper($row['currency_code'] ?? 'SGD'),
            'currency_rate'  => 1,
            'category_id'    => $category->id,
            'number'         => $number,
            'description'    => "Wave Invoice {$row['number']} – " . ($row['item_name'] ?? 'Imported Invoice'),
            'payment_method' => 'bank_transfer',
            'reference'      => $row['number'] ?? null,
            'enabled'        => 1,
            'created_from'   => 'wave-migration',
        ]);

        return 'imported';
    }
}
