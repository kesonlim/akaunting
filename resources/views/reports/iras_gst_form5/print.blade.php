<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>IRAS GST Form 5 - {{ $class->company_name }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            color: #1f2937;
            font-size: 11px;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            border-bottom: 2px solid #0A3B32;
            padding-bottom: 15px;
        }
        .title {
            font-size: 18px;
            font-weight: bold;
            color: #0A3B32;
            text-transform: uppercase;
        }
        .subtitle {
            font-size: 12px;
            color: #4b5563;
            margin-top: 3px;
        }
        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
        }
        .meta-table td {
            padding: 8px 12px;
            font-size: 10px;
        }
        .meta-label {
            color: #6b7280;
            font-weight: bold;
            text-transform: uppercase;
        }
        .meta-value {
            color: #111827;
            font-weight: bold;
            font-size: 11px;
        }
        .box-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }
        .box-table th {
            background-color: #0A3B32;
            color: #ffffff;
            text-align: left;
            padding: 8px 10px;
            font-size: 10px;
            text-transform: uppercase;
            font-weight: bold;
        }
        .box-table td {
            padding: 7px 10px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 10px;
        }
        .box-num {
            display: inline-block;
            background-color: #f3f4f6;
            color: #1f2937;
            font-weight: bold;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            margin-right: 6px;
        }
        .amount-col {
            text-align: right;
            font-weight: bold;
            font-size: 11px;
            width: 140px;
        }
        .total-row td {
            background-color: #f9fafb;
            font-weight: bold;
            border-top: 1px solid #d1d5db;
        }
        .hero-box {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            border: 2px solid {{ $class->box_8 >= 0 ? '#10B981' : '#3B82F6' }};
            background-color: {{ $class->box_8 >= 0 ? '#ecfdf5' : '#eff6ff' }};
        }
        .hero-box td {
            padding: 12px 16px;
        }
        .footer-note {
            margin-top: 25px;
            padding-top: 10px;
            border-top: 1px solid #e5e7eb;
            font-size: 9px;
            color: #6b7280;
            line-height: 1.4;
        }
    </style>
</head>
<body>

    {{-- Official Header --}}
    <table class="header-table">
        <tr>
            <td>
                <div class="title">INLAND REVENUE AUTHORITY OF SINGAPORE (IRAS)</div>
                <div class="subtitle">GST Form 5 • Goods and Services Tax Return</div>
            </td>
            <td style="text-align: right;">
                <div style="font-size: 14px; font-weight: bold; color: #0A3B32;">STRAITSLEDGER</div>
                <div style="font-size: 9px; color: #6b7280;">Singapore Statutory Tax Engine</div>
            </td>
        </tr>
    </table>

    {{-- Taxpayer Metadata --}}
    <table class="meta-table">
        <tr>
            <td width="30%">
                <div class="meta-label">Taxable Person / Entity</div>
                <div class="meta-value">{{ $class->company_name }}</div>
            </td>
            <td width="25%">
                <div class="meta-label">Singapore UEN / GST Reg No.</div>
                <div class="meta-value">{{ $class->company_uen }}</div>
            </td>
            <td width="25%">
                <div class="meta-label">Accounting Period / Basis</div>
                <div class="meta-value">{{ $class->filing_period_label }} ({{ ucfirst($class->getBasis()) }})</div>
            </td>
            <td width="20%">
                <div class="meta-label">Statutory Rate</div>
                <div class="meta-value">9.0% GST</div>
            </td>
        </tr>
    </table>

    {{-- Box 8 Net Position Highlight --}}
    <table class="hero-box">
        <tr>
            <td>
                <div style="font-size: 11px; font-weight: bold; text-transform: uppercase; color: {{ $class->box_8 >= 0 ? '#065f46' : '#1e40af' }};">
                    Box 8 • Official Net GST Position
                </div>
                <div style="font-size: 14px; font-weight: bold; color: #111827; margin-top: 2px;">
                    {{ $class->box_8 >= 0 ? 'Net GST to be Paid to IRAS' : 'Net GST to be Refunded by IRAS' }}
                </div>
            </td>
            <td style="text-align: right;">
                <div style="font-size: 20px; font-weight: bold; color: {{ $class->box_8 >= 0 ? '#047857' : '#1d4ed8' }};">
                    S${{ number_format(abs($class->box_8), 2) }}
                </div>
            </td>
        </tr>
    </table>

    {{-- Supplies (Output Tax) Table --}}
    <table class="box-table">
        <thead>
            <tr>
                <th colspan="2">Part 1 & 2: Supplies & Output Tax Due</th>
                <th class="amount-col" style="color: #ffffff;">Amount (SGD)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td width="10%"><span class="box-num">Box 1</span></td>
                <td>Total value of standard-rated supplies (excluding GST)</td>
                <td class="amount-col">S${{ number_format($class->box_1, 2) }}</td>
            </tr>
            <tr>
                <td><span class="box-num">Box 2</span></td>
                <td>Total value of zero-rated supplies (exports & international services)</td>
                <td class="amount-col">S${{ number_format($class->box_2, 2) }}</td>
            </tr>
            <tr>
                <td><span class="box-num">Box 3</span></td>
                <td>Total value of exempt supplies (financial services, residential rent, etc.)</td>
                <td class="amount-col">S${{ number_format($class->box_3, 2) }}</td>
            </tr>
            <tr class="total-row">
                <td><span class="box-num" style="background-color: #d1fae5; color: #065f46;">Box 4</span></td>
                <td><strong>Total Supplies (Sum of Box 1 + Box 2 + Box 3)</strong></td>
                <td class="amount-col">S${{ number_format($class->box_4, 2) }}</td>
            </tr>
            <tr style="background-color: #f0fdf4;">
                <td><span class="box-num" style="background-color: #0A3B32; color: #ffffff;">Box 5</span></td>
                <td><strong style="color: #065f46;">Output Tax Due (Total GST collected on standard-rated supplies)</strong></td>
                <td class="amount-col" style="color: #065f46;">S${{ number_format($class->box_5, 2) }}</td>
            </tr>
        </tbody>
    </table>

    {{-- Purchases (Input Tax) Table --}}
    <table class="box-table">
        <thead>
            <tr>
                <th colspan="2">Part 3: Purchases & Input Tax Claimed</th>
                <th class="amount-col" style="color: #ffffff;">Amount (SGD)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td width="10%"><span class="box-num">Box 6</span></td>
                <td>Total value of taxable purchases (excluding GST)</td>
                <td class="amount-col">S${{ number_format($class->box_6, 2) }}</td>
            </tr>
            <tr style="background-color: #faf5ff;">
                <td><span class="box-num" style="background-color: #7c3aed; color: #ffffff;">Box 7</span></td>
                <td><strong style="color: #581c87;">Input Tax and Refunds Claimed</strong></td>
                <td class="amount-col" style="color: #581c87;">S${{ number_format($class->box_7, 2) }}</td>
            </tr>
        </tbody>
    </table>

    {{-- Supplementary Table --}}
    <table class="box-table">
        <thead>
            <tr>
                <th colspan="2">Part 5: Revenue & Declarations</th>
                <th class="amount-col" style="color: #ffffff;">Amount (SGD)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td width="10%"><span class="box-num">Box 9</span></td>
                <td>Total value of goods and services revenue for the accounting period</td>
                <td class="amount-col">S${{ number_format($class->box_9, 2) }}</td>
            </tr>
            <tr>
                <td><span class="box-num">Box 10</span></td>
                <td>Did you claim tourist refund?</td>
                <td class="amount-col">S${{ number_format($class->box_10, 2) }}</td>
            </tr>
            <tr>
                <td><span class="box-num">Box 11</span></td>
                <td>Did you make any bad debt relief claims?</td>
                <td class="amount-col">S${{ number_format($class->box_11, 2) }}</td>
            </tr>
            <tr>
                <td><span class="box-num">Box 12</span></td>
                <td>Did you make any pre-registration GST claims?</td>
                <td class="amount-col">S${{ number_format($class->box_12, 2) }}</td>
            </tr>
            <tr>
                <td><span class="box-num">Box 13</span></td>
                <td>Revenue from imported goods under Major Exporter Scheme (MES)</td>
                <td class="amount-col">S${{ number_format($class->box_13, 2) }}</td>
            </tr>
        </tbody>
    </table>

    {{-- Statutory Notes --}}
    <div class="footer-note">
        <strong>Statutory Declaration Note:</strong> This document is generated by StraitsLedger (Singapore Compliance Edition) from verified general ledger transactions. All tax invoices, payment vouchers, and export/import declarations must be preserved for five (5) years pursuant to Section 46 of the Singapore Goods and Services Tax Act 1993.
    </div>

</body>
</html>
