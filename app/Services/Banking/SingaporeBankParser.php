<?php

namespace App\Services\Banking;

use Carbon\Carbon;

class SingaporeBankParser
{
    /**
     * Parse raw statement file or text content into normalized transactions.
     *
     * @param string $content
     * @param string $formatHint 'auto'|'dbs'|'ocbc'|'uob'|'generic'
     * @return array
     */
    public function parse(string $content, string $formatHint = 'auto'): array
    {
        $lines = preg_split('/\r\n|\r|\n/', trim($content));
        if (empty($lines)) {
            return [
                'success' => false,
                'error' => 'The statement file is empty.',
                'transactions' => [],
                'metadata' => [],
            ];
        }

        // Determine format
        $format = ($formatHint !== 'auto') ? $formatHint : $this->detectFormat($lines);

        $transactions = [];
        $headerIndex = -1;
        $headers = [];

        // Scan for header row
        foreach ($lines as $idx => $line) {
            $parsedCols = str_getcsv($line);
            $normalizedCols = array_map(fn($c) => strtolower(trim(preg_replace('/[^a-zA-Z0-9]/', '', $c))), $parsedCols);

            if ($this->isHeaderRow($normalizedCols, $format)) {
                $headerIndex = $idx;
                $headers = $normalizedCols;
                break;
            }
        }

        if ($headerIndex === -1) {
            // Fallback: try first line as header
            $headerIndex = 0;
            $headers = array_map(fn($c) => strtolower(trim(preg_replace('/[^a-zA-Z0-9]/', '', $c))), str_getcsv($lines[0]));
        }

        // Parse rows following header
        for ($i = $headerIndex + 1; $i < count($lines); $i++) {
            $line = trim($lines[$i]);
            if (empty($line)) continue;

            $cols = str_getcsv($line);
            if (count($cols) < 2) continue;

            $tx = $this->parseRow($cols, $headers, $format);
            if ($tx) {
                $transactions[] = $tx;
            }
        }

        // Compute metadata
        $totalInflow = 0.0;
        $totalOutflow = 0.0;
        foreach ($transactions as $t) {
            if ($t['type'] === 'income') {
                $totalInflow += $t['amount'];
            } else {
                $totalOutflow += $t['amount'];
            }
        }

        return [
            'success' => true,
            'format' => $format,
            'format_name' => $this->getFormatName($format),
            'count' => count($transactions),
            'total_inflow' => round($totalInflow, 2),
            'total_outflow' => round($totalOutflow, 2),
            'net_movement' => round($totalInflow - $totalOutflow, 2),
            'transactions' => $transactions,
        ];
    }

    /**
     * Detect bank format from lines.
     */
    protected function detectFormat(array $lines): string
    {
        $joined = strtolower(implode(' ', array_slice($lines, 0, 15)));

        if (str_contains($joined, 'ideal') || str_contains($joined, 'statement code') || (str_contains($joined, 'client reference') && str_contains($joined, 'debit amount'))) {
            return 'dbs';
        }

        if (str_contains($joined, 'velocity') || (str_contains($joined, 'withdrawal') && str_contains($joined, 'deposit') && str_contains($joined, 'running balance'))) {
            return 'ocbc';
        }

        if (str_contains($joined, 'infinity') || str_contains($joined, 'bibplus') || (str_contains($joined, 'post date') && str_contains($joined, 'transaction type'))) {
            return 'uob';
        }

        if (str_contains($joined, 'dbs') || str_contains($joined, 'posb')) {
            return 'dbs';
        }

        if (str_contains($joined, 'ocbc')) {
            return 'ocbc';
        }

        if (str_contains($joined, 'uob')) {
            return 'uob';
        }

        return 'generic';
    }

    /**
     * Check if a column row is a recognized header.
     */
    protected function isHeaderRow(array $cols, string $format): bool
    {
        $str = implode(' ', $cols);

        return match ($format) {
            'dbs' => (str_contains($str, 'date') && (str_contains($str, 'debit') || str_contains($str, 'credit') || str_contains($str, 'statement'))),
            'ocbc' => (str_contains($str, 'date') && (str_contains($str, 'withdrawal') || str_contains($str, 'deposit') || str_contains($str, 'runningbalance'))),
            'uob' => (str_contains($str, 'date') && (str_contains($str, 'type') || str_contains($str, 'debit') || str_contains($str, 'credit'))),
            default => (str_contains($str, 'date') && (str_contains($str, 'amount') || str_contains($str, 'debit') || str_contains($str, 'credit') || str_contains($str, 'description'))),
        };
    }

    /**
     * Parse a single row of columns into a normalized transaction array.
     */
    protected function parseRow(array $cols, array $headers, string $format): ?array
    {
        $row = [];
        foreach ($cols as $idx => $val) {
            $h = $headers[$idx] ?? 'col_' . $idx;
            $row[$h] = trim($val);
        }

        $date = null;
        $description = '';
        $reference = '';
        $amount = 0.0;
        $type = 'income'; // 'income' (credit/deposit) or 'expense' (debit/withdrawal)
        $runningBalance = null;

        // Dynamic column lookup
        $colDate = $this->findCol($row, ['transactiondate', 'postdate', 'postingdate', 'date', 'valuedate']);
        $colDesc = $this->findCol($row, ['description', 'transactiondescription', 'paymentdetails', 'particulars', 'narrative']);
        $colClientRef = $this->findCol($row, ['clientreference', 'customerreference', 'referenceno', 'reference', 'ref']);
        $colDebit = $this->findCol($row, ['debitamount', 'debit', 'withdrawal', 'withdrawalamount']);
        $colCredit = $this->findCol($row, ['creditamount', 'credit', 'deposit', 'depositamount']);
        $colAmount = $this->findCol($row, ['amount', 'transamount', 'netamount']);
        $colBalance = $this->findCol($row, ['balance', 'runningbalance', 'ledgerbalance']);

        // Parse Date
        if ($colDate) {
            $date = $this->cleanDate($colDate);
        }
        if (!$date) {
            // Cannot process row without a date
            return null;
        }

        // Parse Description & Reference
        $description = $colDesc ?: ($colClientRef ?: 'Bank Transaction');
        $reference = $colClientRef ?: '';

        // If extra details exist, append
        $detailsCol = $this->findCol($row, ['paymentdetails', 'additionalinfo', 'remittanceinfo']);
        if ($detailsCol && $detailsCol !== $description) {
            $description .= ' | ' . $detailsCol;
        }

        // Parse Amount & Type
        $debitVal = $colDebit ? $this->cleanNumber($colDebit) : 0.0;
        $creditVal = $colCredit ? $this->cleanNumber($colCredit) : 0.0;

        if ($creditVal > 0) {
            $type = 'income';
            $amount = $creditVal;
        } elseif ($debitVal > 0) {
            $type = 'expense';
            $amount = $debitVal;
        } elseif ($colAmount) {
            $rawAmount = $this->cleanNumber($colAmount);
            if ($rawAmount < 0) {
                $type = 'expense';
                $amount = abs($rawAmount);
            } else {
                $type = 'income';
                $amount = $rawAmount;
            }
        }

        if ($amount <= 0) {
            return null;
        }

        // Running balance
        if ($colBalance) {
            $runningBalance = $this->cleanNumber($colBalance);
        }

        // Counterparty and UEN extraction
        $extractedUen = $this->extractUen($description . ' ' . $reference);
        $extractedInvoiceNo = $this->extractInvoiceNumber($description . ' ' . $reference);
        $counterparty = $this->extractCounterparty($description, $format);

        return [
            'id' => uniqid('tx_', true),
            'date' => $date,
            'type' => $type,
            'amount' => round($amount, 2),
            'currency' => 'SGD',
            'description' => $description,
            'reference' => $reference,
            'running_balance' => $runningBalance,
            'counterparty' => $counterparty,
            'uen' => $extractedUen,
            'invoice_number' => $extractedInvoiceNo,
            'bank_format' => $format,
        ];
    }

    /**
     * Clean date string into Y-m-d.
     */
    protected function cleanDate(string $raw): ?string
    {
        $raw = trim($raw);
        if (empty($raw)) return null;

        $formats = [
            'd/m/Y', 'd-m-Y', 'Y-m-d', 'd M Y', 'd-M-Y', 'd F Y',
            'd/m/y', 'd-m-y', 'Y/m/d', 'm/d/Y'
        ];

        foreach ($formats as $f) {
            try {
                $parsed = Carbon::createFromFormat($f, $raw);
                if ($parsed && $parsed->year > 2000 && $parsed->year < 2100) {
                    return $parsed->format('Y-m-d');
                }
            } catch (\Exception $e) {
                continue;
            }
        }

        try {
            $p = Carbon::parse($raw);
            if ($p && $p->year > 2000 && $p->year < 2100) {
                return $p->format('Y-m-d');
            }
        } catch (\Exception $e) {
            // Ignore
        }

        return null;
    }

    /**
     * Clean number string to float.
     */
    protected function cleanNumber(string $raw): float
    {
        $raw = trim($raw);
        $isNegative = str_starts_with($raw, '-') || (str_starts_with($raw, '(') && str_ends_with($raw, ')'));
        
        // Remove currency symbols, commas, and parentheses
        $cleaned = preg_replace('/[^0-9.]/', '', $raw);
        $val = (float) $cleaned;

        return $isNegative ? -$val : $val;
    }

    /**
     * Helper to find first matching column from candidate names.
     */
    protected function findCol(array $row, array $candidates): ?string
    {
        foreach ($candidates as $cand) {
            foreach ($row as $k => $v) {
                if (str_contains($k, $cand) && $v !== '') {
                    return $v;
                }
            }
        }
        return null;
    }

    /**
     * Extract Singapore UEN (Unique Entity Number) from text.
     */
    protected function extractUen(string $text): ?string
    {
        // Standard formats:
        // 1. Businesses (ROB): 8 digits + 1 check digit (e.g. 52912345A)
        // 2. Local Companies (ROC): 9-10 digits, usually YYYY + 5 digits + check (e.g. 200415432K, 202319882K)
        // 3. Others: TyyPQnnnnX (e.g. T08LL0123A)
        if (preg_match('/\b(20\d{7}[A-Z]|19\d{7}[A-Z]|[TSR]\d{2}[A-Z]{2}\d{4}[A-Z]|\d{8}[A-Z])\b/i', $text, $matches)) {
            return strtoupper($matches[1]);
        }
        return null;
    }

    /**
     * Extract Invoice Number candidate from text.
     */
    protected function extractInvoiceNumber(string $text): ?string
    {
        if (preg_match('/\b(INV[-_]?\d{3,8})\b/i', $text, $matches)) {
            return strtoupper($matches[1]);
        }
        if (preg_match('/\b(INVOICE\s*#?\s*\d{3,8})\b/i', $text, $matches)) {
            return strtoupper(preg_replace('/\s+/', '', $matches[1]));
        }
        return null;
    }

    /**
     * Extract clean counterparty name from description.
     */
    protected function extractCounterparty(string $desc, string $format): string
    {
        // Clean common bank prefixes
        $cleaned = preg_replace('/^(FAST|PAYNOW|GIRO|MEPS|IBG|ITR|NETS|SALARY|BILL PAYMENT|FUND TRANSFER)\s*[:-]?\s*/i', '', $desc);
        $cleaned = preg_replace('/(PAYNOW-|FAST-)[A-Z0-9_-]+/i', '', $cleaned);
        $parts = explode('|', $cleaned);
        $candidate = trim($parts[0]);

        return mb_substr($candidate, 0, 80);
    }

    /**
     * Human-readable bank name.
     */
    public function getFormatName(string $format): string
    {
        return match ($format) {
            'dbs' => 'DBS / POSB IDEAL',
            'ocbc' => 'OCBC Velocity',
            'uob' => 'UOB Infinity (BIBPlus)',
            default => 'Standard Singapore Bank Feed / Neobank (CSV)',
        };
    }
}
