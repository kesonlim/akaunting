@php
    $companyUen = setting('company.tax_number', setting('general.company_tax_number', '200415432K'));
    $companyName = setting('company.name', setting('general.company_name', config('app.name', 'Think Thank Pte Ltd')));
    $dueAmount = $document->amount_due ?? $document->amount;
    $currencyCode = $document->currency_code ?? 'SGD';
    $qrDataUri = \App\Utilities\PayNow::generateQrCodeDataUri($companyUen, $dueAmount, $document->document_number, $companyName);
@endphp

<div class="paynow-qr-card my-4 p-4 rounded-xl border border-purple-200 bg-gradient-to-br from-purple-50/50 via-white to-purple-50/30" style="background: linear-gradient(135deg, #faf5ff 0%, #ffffff 50%, #fdf4ff 100%); border: 1px solid #e9d5ff; border-radius: 12px; padding: 16px; margin: 16px 0; max-width: 480px; box-shadow: 0 2px 8px rgba(109, 40, 217, 0.06);">
    <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px dashed #d8b4fe; padding-bottom: 10px; margin-bottom: 12px; flex-wrap: wrap; gap: 6px;">
        <div style="display: flex; align-items: center; gap: 8px;">
            <div style="background: #7c3aed; color: #ffffff; font-weight: 800; font-size: 11px; padding: 4px 8px; border-radius: 6px; letter-spacing: 0.05em; text-transform: uppercase;">
                PayNow SG
            </div>
            <span style="font-size: 13px; font-weight: 700; color: #1e1b4b;">Corporate SGQR</span>
        </div>
        <span style="font-size: 10px; font-weight: 600; color: #6b21a8; background: #f3e8ff; padding: 3px 8px; border-radius: 9999px; white-space: nowrap;">
            Instant Domestic Transfer
        </span>
    </div>

    <div style="display: flex; align-items: center; gap: 16px;">
        <!-- Dynamic QR Vector -->
        <div style="flex-shrink: 0; background: #ffffff; padding: 8px; border-radius: 8px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <img src="{{ $qrDataUri }}" alt="PayNow SGQR" style="width: 125px; height: 125px; display: block;" />
        </div>

        <!-- Payment Meta -->
        <div style="font-size: 12px; line-height: 1.5; color: #334155; flex-grow: 1;">
            <div style="margin-bottom: 4px;">
                <span style="color: #64748b; font-size: 11px; display: block;">Recipient UEN:</span>
                <strong style="color: #0f172a; font-family: monospace; font-size: 13px;">{{ $companyUen }}</strong>
            </div>

            <div style="margin-bottom: 4px;">
                <span style="color: #64748b; font-size: 11px; display: block;">Invoice Reference:</span>
                <strong style="color: #7c3aed; font-family: monospace; font-size: 12px;">{{ $document->document_number }}</strong>
            </div>

            <div>
                <span style="color: #64748b; font-size: 11px; display: block;">Amount to Pay:</span>
                <strong style="color: #0f172a; font-size: 14px;">{{ $currencyCode }} {{ number_format((float)$dueAmount, 2) }}</strong>
            </div>
        </div>
    </div>

    <div style="margin-top: 10px; padding-top: 8px; border-top: 1px solid #f1f5f9; font-size: 10px; color: #64748b; text-align: center;">
        Scan using <strong>DBS/POSB PayLah!, OCBC Digital, UOB TMRW, Standard Chartered, HSBC</strong> or any PayNow-enabled mobile banking app.
    </div>
</div>
