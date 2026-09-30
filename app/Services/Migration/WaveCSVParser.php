<?php

namespace App\Services\Migration;

use Illuminate\Http\UploadedFile;

class WaveCSVParser
{
    /**
     * WaveApps CSV type detection signatures (header keywords).
     */
    private const TYPE_SIGNATURES = [
        'customers'         => ['customer name', 'customerName'],
        'vendors'           => ['vendor name', 'vendorName', 'supplier name'],
        'chart_of_accounts' => ['account name', 'account type'],
        'invoices'          => ['invoice #', 'invoice number', 'invoiceNumber'],
        'transactions'      => ['transaction id', 'transactionId', 'transaction date'],
    ];

    /**
     * Field maps from WaveApps headers → StraitsLedger fields.
     */
    private const FIELD_MAPS = [
        'customers' => [
            'customer name'     => 'name',
            'email'             => 'email',
            'phone'             => 'phone',
            'address line 1'    => 'address',
            'address'           => 'address',
            'city'              => 'city',
            'province/state'    => 'state',
            'state'             => 'state',
            'country'           => 'country',
            'postal code'       => 'zip_code',
            'zip code'          => 'zip_code',
            'currency code'     => 'currency_code',
            'currency'          => 'currency_code',
            'website'           => 'website',
            'tax number'        => 'tax_number',
        ],
        'vendors' => [
            'vendor name'       => 'name',
            'supplier name'     => 'name',
            'email'             => 'email',
            'phone'             => 'phone',
            'address'           => 'address',
            'address line 1'    => 'address',
            'city'              => 'city',
            'province/state'    => 'state',
            'state'             => 'state',
            'country'           => 'country',
            'postal code'       => 'zip_code',
            'zip code'          => 'zip_code',
            'currency code'     => 'currency_code',
            'currency'          => 'currency_code',
            'website'           => 'website',
            'tax number'        => 'tax_number',
        ],
        'chart_of_accounts' => [
            'account name'      => 'name',
            'account type'      => 'wave_account_type',
            'currency code'     => 'currency_code',
            'currency'          => 'currency_code',
            'description'       => 'description',
        ],
        'transactions' => [
            'transaction id'    => 'wave_id',
            'transaction date'  => 'paid_at',
            'date'              => 'paid_at',
            'description'       => 'description',
            'total amount'      => 'amount',
            'amount'            => 'amount',
            'tax amount'        => 'tax_amount',
            'account name'      => 'account_name',
            'category'          => 'category_name',
            'notes'             => 'notes',
            'type'              => 'type',
        ],
        'invoices' => [
            'invoice #'         => 'number',
            'invoice number'    => 'number',
            'customer name'     => 'contact_name',
            'customer'          => 'contact_name',
            'invoice date'      => 'issued_at',
            'date'              => 'issued_at',
            'due date'          => 'due_at',
            'item description'  => 'item_name',
            'description'       => 'item_name',
            'quantity'          => 'quantity',
            'qty'               => 'quantity',
            'unit price'        => 'price',
            'price'             => 'price',
            'tax name'          => 'tax_name',
            'tax amount'        => 'tax_amount',
            'discount'          => 'discount',
            'total'             => 'total',
            'currency'          => 'currency_code',
            'currency code'     => 'currency_code',
            'status'            => 'status',
            'notes'             => 'notes',
        ],
    ];

    /**
     * Map WaveApps account types to StraitsLedger category types.
     */
    private const ACCOUNT_TYPE_MAP = [
        'income'            => 'income',
        'sales'             => 'income',
        'revenue'           => 'income',
        'other income'      => 'income',
        'expense'           => 'expense',
        'expenses'          => 'expense',
        'cost of goods'     => 'expense',
        'cost of goods sold' => 'expense',
        'operating expense' => 'expense',
        'payroll'           => 'expense',
        'asset'             => 'other',
        'liability'         => 'other',
        'equity'            => 'other',
        'other'             => 'other',
    ];

    /**
     * Parse an uploaded WaveApps CSV file.
     *
     * @param UploadedFile $file
     * @return array{type: string, rows: array, headers: array, mapped: array, warnings: array, count: int}
     */
    public function parse(UploadedFile $file): array
    {
        $content = file_get_contents($file->getRealPath());

        // Detect BOM and strip it
        $content = ltrim($content, "\xEF\xBB\xBF");

        $lines = array_filter(explode("\n", str_replace("\r\n", "\n", $content)));
        $lines = array_values($lines);

        if (empty($lines)) {
            return $this->error('The uploaded file appears to be empty.');
        }

        // Parse CSV headers from first line
        $headers = $this->parseCsvLine($lines[0]);
        $headers = array_map('trim', $headers);
        $headersLower = array_map('strtolower', $headers);

        // Detect type
        $type = $this->detectType($headersLower);
        if (!$type) {
            return $this->error(
                'Could not detect WaveApps CSV type. Please ensure you are uploading an unmodified WaveApps export. ' .
                'Detected headers: ' . implode(', ', $headers)
            );
        }

        // Parse data rows
        $fieldMap = self::FIELD_MAPS[$type];
        $rows = [];
        $mapped = [];
        $warnings = [];

        for ($i = 1; $i < count($lines); $i++) {
            $line = trim($lines[$i]);
            if (empty($line)) continue;

            $values = $this->parseCsvLine($line);
            $row = [];
            foreach ($headers as $idx => $header) {
                $row[$header] = $values[$idx] ?? '';
            }
            $rows[] = $row;

            // Map row to StraitsLedger fields
            $mappedRow = [];
            foreach ($headersLower as $idx => $headerLow) {
                $slField = $fieldMap[$headerLow] ?? null;
                if ($slField) {
                    $mappedRow[$slField] = trim($values[$idx] ?? '');
                }
            }

            // Post-process
            $mappedRow = $this->postProcess($type, $mappedRow, $i + 1, $warnings);
            $mapped[] = $mappedRow;
        }

        return [
            'type'     => $type,
            'rows'     => $rows,
            'headers'  => $headers,
            'mapped'   => $mapped,
            'warnings' => $warnings,
            'count'    => count($mapped),
            'error'    => null,
        ];
    }

    /**
     * Detect CSV type from header row (lowercased).
     */
    private function detectType(array $headersLower): ?string
    {
        foreach (self::TYPE_SIGNATURES as $type => $signatures) {
            foreach ($signatures as $sig) {
                if (in_array(strtolower($sig), $headersLower)) {
                    return $type;
                }
            }
        }
        return null;
    }

    /**
     * Post-process a mapped row for type-specific transformations.
     */
    private function postProcess(string $type, array $row, int $lineNum, array &$warnings): array
    {
        // Default currency to SGD if missing
        if (empty($row['currency_code'])) {
            $row['currency_code'] = 'SGD';
        }

        if ($type === 'chart_of_accounts') {
            $waveType = strtolower(trim($row['wave_account_type'] ?? ''));
            $row['type'] = self::ACCOUNT_TYPE_MAP[$waveType] ?? 'other';
            unset($row['wave_account_type']);

            if (!$row['name']) {
                $warnings[] = "Row {$lineNum}: Missing account name — row will be skipped.";
            }
        }

        if (in_array($type, ['customers', 'vendors'])) {
            if (empty($row['name'])) {
                $warnings[] = "Row {$lineNum}: Missing contact name — row will be skipped.";
            }
        }

        if ($type === 'transactions') {
            // Normalise amount: remove currency symbols, commas
            $row['amount'] = preg_replace('/[^0-9.\-]/', '', $row['amount'] ?? '0');
            if (empty($row['amount'])) $row['amount'] = '0';

            // Determine income/expense from sign
            $amount = (float) $row['amount'];
            if (!isset($row['type']) || empty($row['type'])) {
                $row['type'] = $amount >= 0 ? 'income' : 'expense';
            }
            $row['amount'] = abs($amount);
        }

        if ($type === 'invoices') {
            $row['quantity'] = is_numeric($row['quantity'] ?? '') ? (float)$row['quantity'] : 1;
            $price = preg_replace('/[^0-9.\-]/', '', $row['price'] ?? '0');
            $row['price'] = (float)($price ?: 0);
        }

        return $row;
    }

    /**
     * Parse a single CSV line (handles quoted fields).
     */
    private function parseCsvLine(string $line): array
    {
        return str_getcsv($line, ',', '"', '\\');
    }

    /**
     * Return a standardised error result.
     */
    private function error(string $message): array
    {
        return [
            'type'     => null,
            'rows'     => [],
            'headers'  => [],
            'mapped'   => [],
            'warnings' => [],
            'count'    => 0,
            'error'    => $message,
        ];
    }

    /**
     * Get a human-readable label for a CSV type.
     */
    public static function typeLabel(string $type): string
    {
        return match($type) {
            'customers'         => 'Customers',
            'vendors'           => 'Vendors / Suppliers',
            'chart_of_accounts' => 'Chart of Accounts',
            'transactions'      => 'Transactions',
            'invoices'          => 'Invoices',
            default             => ucfirst($type),
        };
    }
}
