@php
    $settingVal = setting('invoice.paynow_enabled');
    $paynowEnabled = ($settingVal !== '0' && $settingVal !== 0);

    $company = $document->company ?? (function_exists('company_id') ? \App\Models\Common\Company::find($document->company_id ?? company_id()) : null);

    $companyUen = trim(
        (isset($companyUen) && !empty($companyUen) ? $companyUen : null)
        ?? (setting('invoice.paynow_uen') ?: null)
        ?? (setting('company.tax_number') ?: null)
        ?? (setting('general.company_tax_number') ?: null)
        ?? ($company->tax_number ?? '')
    );

    $companyName = trim(
        (isset($companyName) && !empty($companyName) ? $companyName : null)
        ?? (setting('invoice.paynow_merchant_name') ?: null)
        ?? (setting('company.name') ?: null)
        ?? (setting('general.company_name') ?: null)
        ?? ($company->name ?? config('app.name'))
    );

    $proxyType = setting('invoice.paynow_proxy_type', '2');
    $dueAmount = $document->amount_due ?? $document->amount;
    $currencyCode = strtoupper($document->currency_code ?? 'SGD');

    // Only render if PayNow is enabled and a valid UEN / identifier exists
    $shouldRender = $paynowEnabled && !empty($companyUen);

    if ($shouldRender) {
        $qrDataUri = \App\Utilities\PayNow::generateQrCodeDataUri($companyUen, $dueAmount, $document->document_number, $companyName, $proxyType);
    }
@endphp

@if ($shouldRender)
<table class="paynow-qr-card" cellpadding="0" cellspacing="0" style="width: 100%; max-width: 480px; margin: 16px 0; border: 1px solid #d8b4fe; border-radius: 10px; background-color: #faf5ff; font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
    <!-- Card Header Banner -->
    <tr>
        <td colspan="2" style="padding: 10px 14px; border-bottom: 1px dashed #d8b4fe; background-color: #f3e8ff; border-top-left-radius: 9px; border-top-right-radius: 9px;">
            <table cellpadding="0" cellspacing="0" style="width: 100%;">
                <tr>
                    <td style="vertical-align: middle;">
                        <span style="display: inline-block; background-color: #7c3aed; color: #ffffff; font-weight: 800; font-size: 11px; padding: 3px 8px; border-radius: 4px; letter-spacing: 0.05em; text-transform: uppercase;">
                            PayNow SG
                        </span>
                        <strong style="font-size: 13px; color: #1e1b4b; margin-left: 8px;">
                            Corporate SGQR
                        </strong>
                    </td>
                    <td style="text-align: right; vertical-align: middle;">
                        <span style="font-size: 10px; font-weight: 600; color: #6b21a8; background-color: #ffffff; padding: 2px 8px; border-radius: 12px; border: 1px solid #e9d5ff;">
                            Instant Domestic Rail
                        </span>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <!-- Card Content -->
    <tr>
        <!-- Left Column: QR Code -->
        <td style="width: 135px; padding: 14px 10px 10px 14px; vertical-align: top; text-align: center;">
            <div style="background-color: #ffffff; padding: 6px; border: 1px solid #e2e8f0; border-radius: 8px; display: inline-block;">
                <img src="{{ $qrDataUri }}" alt="PayNow SGQR" width="120" height="120" style="width: 120px; height: 120px; display: block; border: 0;" />
            </div>
            <div style="font-size: 9px; color: #7c3aed; font-weight: 600; margin-top: 4px; text-transform: uppercase; letter-spacing: 0.04em;">
                Dynamic SGQR
            </div>
        </td>

        <!-- Right Column: Meta -->
        <td style="padding: 14px 14px 10px 6px; vertical-align: top; font-size: 12px; color: #334155; line-height: 1.4;">
            <div style="margin-bottom: 6px;">
                <span style="color: #64748b; font-size: 10px; text-transform: uppercase; letter-spacing: 0.03em; display: block;">Recipient Entity / Merchant:</span>
                <strong style="color: #0f172a; font-size: 12px;">{{ $companyName }}</strong>
            </div>

            <div style="margin-bottom: 6px;">
                <span style="color: #64748b; font-size: 10px; text-transform: uppercase; letter-spacing: 0.03em; display: block;">
                    {{ $proxyType === '0' ? 'PayNow Mobile' : 'Corporate UEN' }}:
                </span>
                <strong style="color: #0f172a; font-family: monospace; font-size: 13px; letter-spacing: 0.02em;">{{ $companyUen }}</strong>
            </div>

            <div style="margin-bottom: 6px;">
                <span style="color: #64748b; font-size: 10px; text-transform: uppercase; letter-spacing: 0.03em; display: block;">Invoice Reference:</span>
                <strong style="color: #7c3aed; font-family: monospace; font-size: 12px;">{{ $document->document_number }}</strong>
            </div>

            <div>
                <span style="color: #64748b; font-size: 10px; text-transform: uppercase; letter-spacing: 0.03em; display: block;">Amount to Pay:</span>
                <strong style="color: #0f172a; font-size: 14px; font-weight: 800;">
                    {{ $currencyCode }} {{ number_format((float)$dueAmount, 2) }}
                </strong>
            </div>
        </td>
    </tr>

    <!-- Card Footer -->
    <tr>
        <td colspan="2" style="padding: 8px 14px 10px 14px; border-top: 1px solid #f3e8ff; font-size: 10px; color: #64748b; text-align: center; border-bottom-left-radius: 9px; border-bottom-right-radius: 9px;">
            Scan with <strong>DBS PayLah!, OCBC Digital, UOB TMRW, Standard Chartered, HSBC</strong> or any Singapore PayNow banking app.
        </td>
    </tr>
</table>
@endif
