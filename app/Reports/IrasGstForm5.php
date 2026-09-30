<?php

namespace App\Reports;

use App\Abstracts\Report;
use App\Models\Banking\Transaction;
use App\Models\Document\Document;
use App\Models\Setting\Tax;
use App\Traits\Currencies;
use App\Utilities\Date;
use Illuminate\Support\Str;

class IrasGstForm5 extends Report
{
    use Currencies;

    public $default_name = 'IRAS GST Form 5';

    public $category = 'general.accounting';

    public $group = 'tax';

    public $icon = 'account_balance';

    public $type = 'iras_gst_form5';

    public $chart = false;

    public $has_money = true;

    // Company & Registration Meta
    public $company_name = '';
    public $company_uen = '';
    public $gst_registration_number = '';
    public $filing_period_label = '';
    public $filing_due_date = '';
    public $tax_rate_percentage = 9.0;

    // Official Singapore IRAS GST Form 5 Boxes
    public $box_1 = 0.0; // Standard-rated supplies
    public $box_2 = 0.0; // Zero-rated supplies
    public $box_3 = 0.0; // Exempt supplies
    public $box_4 = 0.0; // Total supplies (1 + 2 + 3)
    public $box_5 = 0.0; // Output tax due
    public $box_6 = 0.0; // Taxable purchases
    public $box_7 = 0.0; // Input tax and refunds claimed
    public $box_8 = 0.0; // Net GST to be paid to / refunded by IRAS (5 - 7)
    public $box_9 = 0.0; // Total revenue for accounting period
    public $box_10 = 0.0; // Tourist refunds
    public $box_11 = 0.0; // Bad debt relief claimed
    public $box_12 = 0.0; // Pre-registration GST claimed
    public $box_13 = 0.0; // Revenue from imported goods under MES

    // Audit Trail Breakdown Collections
    public $sales_transactions = [];
    public $purchase_transactions = [];
    public $periodic_summary = [];

    public function setViews()
    {
        parent::setViews();

        $this->views['show'] = 'reports.iras_gst_form5.show';
        $this->views['print'] = 'reports.iras_gst_form5.print';
        $this->views['iras_gst_form5'] = 'reports.iras_gst_form5.content';
    }

    public function setTables()
    {
        $this->tables = [
            'supplies' => 'Part 1 & 2: Supplies & Output Tax',
            'purchases' => 'Part 3: Purchases & Input Tax',
            'net_gst'   => 'Part 4: Net GST Payable / Refundable',
        ];
    }

    public function setData()
    {
        $company = $this->model?->company ?? company();
        $this->company_name = $company->name ?? setting('company.name', 'Think Thank Pte Ltd');
        $this->company_uen = $company->tax_number ?? setting('company.tax_number', '200415432K');
        $this->gst_registration_number = setting('general.gst_number') ?: $this->company_uen;

        $basis = $this->getBasis();
        $period = $this->getPeriod();
        $year = $this->year ?? date('Y');

        // Formulate period label and statutory IRAS due date
        $this->filing_period_label = strtoupper($period) . " " . $year;
        $this->filing_due_date = "1 month after end of prescribed accounting period";

        // Query Invoices according to Basis
        if ($basis === 'cash') {
            $invoices = $this->applyFilters(
                model: Transaction::with('recurring', 'invoice', 'invoice.totals', 'invoice.items', 'invoice.contact')->income()->isDocument()->isNotTransfer(),
                args: ['date_field' => 'paid_at', 'model_type' => 'income'],
            )->get();
            $invoice_date_col = 'paid_at';
        } else {
            $invoices = $this->applyFilters(
                model: Document::invoice()->with('recurring', 'totals', 'transactions', 'items', 'items.taxes', 'contact')->accrued(),
                args: ['date_field' => 'issued_at', 'model_type' => 'invoice'],
            )->get();
            $invoice_date_col = 'issued_at';
        }

        // Query Bills according to Basis
        if ($basis === 'cash') {
            $bills = $this->applyFilters(
                model: Transaction::with('recurring', 'bill', 'bill.totals', 'bill.items', 'bill.contact')->expense()->isDocument()->isNotTransfer(),
                args: ['date_field' => 'paid_at', 'model_type' => 'expense'],
            )->get();
            $bill_date_col = 'paid_at';
        } else {
            $bills = $this->applyFilters(
                model: Document::bill()->with('recurring', 'totals', 'transactions', 'items', 'items.taxes', 'contact')->accrued(),
                args: ['date_field' => 'issued_at', 'model_type' => 'bill'],
            )->get();
            $bill_date_col = 'issued_at';
        }

        // Direct Transactions
        $direct_incomes = $this->applyFilters(
            model: Transaction::with('taxes', 'category')->income()->isNotDocument()->isNotTransfer(),
            args: ['date_field' => 'paid_at', 'model_type' => 'income'],
        )->get();

        $direct_expenses = $this->applyFilters(
            model: Transaction::with('taxes', 'category')->expense()->isNotDocument()->isNotTransfer(),
            args: ['date_field' => 'paid_at', 'model_type' => 'expense'],
        )->get();

        // 1. Process Sales (Output Tax & Supplies)
        foreach ($invoices as $inv) {
            $doc = ($basis === 'cash') ? $inv->invoice : $inv;
            if (!$doc) continue;

            $date_raw = $inv->$invoice_date_col ?? $doc->issued_at;
            $parsed_date = Date::parse($date_raw);
            $periodic_key = $this->getFormattedDate($parsed_date);

            $doc_amount = (float) $doc->amount;
            $doc_net = 0.0;
            $doc_tax = 0.0;
            $tax_rate_found = 0.0;
            $tax_name_found = 'Standard (9%)';

            // Check line items or totals
            if (!empty($doc->items) && count($doc->items)) {
                foreach ($doc->items as $item) {
                    $item_qty = (float) ($item->quantity ?: 1);
                    $item_price = (float) ($item->price ?: 0);
                    $item_discount = (float) ($item->discount ?: 0);
                    $line_net = ($item_qty * $item_price) - $item_discount;

                    $line_tax = 0.0;
                    $item_rate = 9.0; // default Singapore standard rate

                    if (!empty($item->taxes) && count($item->taxes)) {
                        foreach ($item->taxes as $it) {
                            $item_rate = (float) ($it->rate ?? 9.0);
                            $tax_name_found = $it->name ?? 'Singapore GST (9%)';
                        }
                    }

                    $tax_rate_found = $item_rate;
                    $line_tax = ($item_rate > 0) ? ($line_net * ($item_rate / 100)) : 0.0;

                    $doc_net += $line_net;
                    $doc_tax += $line_tax;
                }
            } else {
                // Check totals for tax
                if (!empty($doc->totals)) {
                    foreach ($doc->totals as $total) {
                        if ($total->code === 'tax') {
                            $doc_tax += (float) $total->amount;
                            $tax_name_found = $total->name;
                            $tax_rate_found = 9.0;
                        }
                    }
                }
                $doc_net = $doc_amount - $doc_tax;
            }

            // Convert currency to SGD
            $converted_net = $this->convertToDefault($doc_net, $doc->currency_code, $doc->currency_rate);
            $converted_tax = $this->convertToDefault($doc_tax, $doc->currency_code, $doc->currency_rate);
            $converted_total = $converted_net + $converted_tax;

            // Classify into Box 1, 2, or 3
            if ($tax_rate_found > 0 || $doc_tax > 0) {
                // Box 1: Standard-Rated
                $this->box_1 += $converted_net;
                $this->box_5 += $converted_tax;
            } elseif ($tax_rate_found === 0.0 && Str::contains(strtolower($tax_name_found), 'zero')) {
                // Box 2: Zero-Rated
                $this->box_2 += $converted_net;
            } else {
                // Default to Box 1 for standard supplies
                $this->box_1 += $converted_net;
                $this->box_5 += $converted_tax;
            }

            $this->box_9 += $converted_net;

            // Track for audit drilldown
            $this->sales_transactions[] = [
                'date' => $parsed_date->format('Y-m-d'),
                'document_number' => $doc->document_number ?? 'INV-0000',
                'contact_name' => $doc->contact->name ?? 'Client',
                'description' => ($doc->items && count($doc->items)) ? $doc->items[0]->name : 'Sales Supply',
                'tax_type' => ($tax_rate_found > 0) ? 'Standard 9%' : 'Zero-Rated',
                'net_amount' => $converted_net,
                'tax_amount' => $converted_tax,
                'total_amount' => $converted_total,
            ];
        }

        // Direct Income Transactions
        foreach ($direct_incomes as $inc) {
            $parsed_date = Date::parse($inc->paid_at);
            $amount = (float) $inc->amount;
            $tax_amount = 0.0;

            if (!empty($inc->taxes)) {
                foreach ($inc->taxes as $t) {
                    $tax_amount += (float) $t->amount;
                }
            }

            $net = $amount - $tax_amount;
            $converted_net = $this->convertToDefault($net, $inc->currency_code, $inc->currency_rate);
            $converted_tax = $this->convertToDefault($tax_amount, $inc->currency_code, $inc->currency_rate);

            if ($converted_tax > 0) {
                $this->box_1 += $converted_net;
                $this->box_5 += $converted_tax;
            } else {
                $this->box_1 += $converted_net;
            }
            $this->box_9 += $converted_net;

            $this->sales_transactions[] = [
                'date' => $parsed_date->format('Y-m-d'),
                'document_number' => $inc->reference ?? 'REC-' . $inc->id,
                'contact_name' => $inc->contact->name ?? 'General Income',
                'description' => $inc->description ?: 'Operating Income',
                'tax_type' => ($converted_tax > 0) ? 'Standard 9%' : 'Standard',
                'net_amount' => $converted_net,
                'tax_amount' => $converted_tax,
                'total_amount' => $converted_net + $converted_tax,
            ];
        }

        // 2. Process Purchases (Input Tax & Taxable Purchases)
        foreach ($bills as $b) {
            $doc = ($basis === 'cash') ? $b->bill : $b;
            if (!$doc) continue;

            $date_raw = $b->$bill_date_col ?? $doc->issued_at;
            $parsed_date = Date::parse($date_raw);

            $doc_amount = (float) $doc->amount;
            $doc_net = 0.0;
            $doc_tax = 0.0;

            if (!empty($doc->totals)) {
                foreach ($doc->totals as $total) {
                    if ($total->code === 'tax') {
                        $doc_tax += (float) $total->amount;
                    }
                }
            }

            $doc_net = $doc_amount - $doc_tax;
            $converted_net = $this->convertToDefault($doc_net, $doc->currency_code, $doc->currency_rate);
            $converted_tax = $this->convertToDefault($doc_tax, $doc->currency_code, $doc->currency_rate);

            $this->box_6 += $converted_net;
            $this->box_7 += $converted_tax;

            $this->purchase_transactions[] = [
                'date' => $parsed_date->format('Y-m-d'),
                'document_number' => $doc->document_number ?? 'BILL-0000',
                'contact_name' => $doc->contact->name ?? 'Supplier',
                'description' => ($doc->items && count($doc->items)) ? $doc->items[0]->name : 'Business Purchase',
                'tax_type' => ($converted_tax > 0) ? 'Input Tax 9%' : 'Exempt/No Tax',
                'net_amount' => $converted_net,
                'tax_amount' => $converted_tax,
                'total_amount' => $converted_net + $converted_tax,
            ];
        }

        // Direct Expenses
        foreach ($direct_expenses as $exp) {
            $parsed_date = Date::parse($exp->paid_at);
            $amount = (float) $exp->amount;
            $tax_amount = 0.0;

            if (!empty($exp->taxes)) {
                foreach ($exp->taxes as $t) {
                    $tax_amount += (float) $t->amount;
                }
            }

            $net = $amount - $tax_amount;
            $converted_net = $this->convertToDefault($net, $exp->currency_code, $exp->currency_rate);
            $converted_tax = $this->convertToDefault($tax_amount, $exp->currency_code, $exp->currency_rate);

            $this->box_6 += $converted_net;
            $this->box_7 += $converted_tax;

            $this->purchase_transactions[] = [
                'date' => $parsed_date->format('Y-m-d'),
                'document_number' => $exp->reference ?? 'EXP-' . $exp->id,
                'contact_name' => $exp->contact->name ?? 'Vendor',
                'description' => $exp->description ?: 'Operating Expense',
                'tax_type' => ($converted_tax > 0) ? 'Input Tax 9%' : 'Expense',
                'net_amount' => $converted_net,
                'tax_amount' => $converted_tax,
                'total_amount' => $converted_net + $converted_tax,
            ];
        }

        // 3. Final Calculations
        $this->box_4 = $this->box_1 + $this->box_2 + $this->box_3;
        $this->box_8 = $this->box_5 - $this->box_7;

        // Populate table structures for default export and print
        foreach ($this->dates as $d) {
            $this->footer_totals['supplies'][$d] = $this->box_5;
            $this->footer_totals['purchases'][$d] = $this->box_7;
            $this->footer_totals['net_gst'][$d] = $this->box_8;
        }
    }

    public function getFields()
    {
        return [
            $this->getPeriodField(),
            $this->getBasisField(),
        ];
    }
}
