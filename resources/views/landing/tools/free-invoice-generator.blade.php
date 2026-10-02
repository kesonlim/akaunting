<!DOCTYPE html>
<html lang="en-SG" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Free Singapore Invoice Generator with PayNow QR | StraitsLedger</title>
    <meta name="description" content="Generate professional Singapore tax invoices with embedded PayNow QR codes for free. Lock exact SGD amounts, UEN, and reference numbers for 0% fee bank-to-bank payments.">
    <meta name="keywords" content="free invoice generator singapore, paynow invoice generator, paynow qr generator for invoice, singapore tax invoice template, free singapore invoicing">
    <link rel="canonical" href="https://ledger.thethinkthank.com/free-paynow-invoice-generator">
    <link rel="icon" type="image/png" href="https://ledger.thethinkthank.com/public/img/favicon.png">

    <!-- Schema.org JSON-LD -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebApplication",
      "name": "Free Singapore PayNow Invoice Generator",
      "applicationCategory": "BusinessApplication, FinancialApplication",
      "operatingSystem": "All",
      "offers": {
        "@type": "Offer",
        "price": "0",
        "priceCurrency": "SGD"
      },
      "creator": {
        "@type": "Organization",
        "name": "Think Thank Pte Ltd",
        "address": {
          "@type": "PostalAddress",
          "streetAddress": "71 Ayer Rajah Crescent",
          "addressLocality": "Singapore",
          "postalCode": "139951",
          "addressCountry": "SG"
        }
      }
    }
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        straits: {
                            emerald: '#0A3B32',
                            'emerald-dark': '#062620',
                            mint: '#10B981',
                            canvas: '#F8FAFC',
                            slate: '#0F172A',
                            muted: '#64748B',
                            border: '#E2E8F0'
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        display: ['"Plus Jakarta Sans"', 'Inter', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <style>
        @media print {
            .no-print { display: none !important; }
            .print-full { width: 100% !important; margin: 0 !important; box-shadow: none !important; border: none !important; }
        }
    </style>
</head>
<body class="bg-straits-canvas text-straits-slate antialiased font-sans min-h-screen flex flex-col justify-between">

    <!-- Header -->
    <header class="no-print bg-white/95 backdrop-blur-md border-b border-straits-border sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
            <a href="/" class="flex items-center space-x-3">
                <div class="w-9 h-9 rounded-xl bg-straits-emerald flex items-center justify-center text-white font-bold font-display text-lg">S</div>
                <span class="font-display font-bold text-xl text-straits-emerald tracking-tight">StraitsLedger</span>
            </a>
            <div class="flex items-center space-x-3 sm:space-x-4">
                <a href="/auth/login" class="text-xs sm:text-sm font-medium text-straits-muted hover:text-straits-emerald">Sign In</a>
                <a href="/auth/login" class="inline-flex items-center justify-center px-4 py-2 text-xs sm:text-sm font-semibold text-white bg-straits-emerald hover:bg-straits-emerald-dark rounded-xl shadow-sm transition">
                    Get Free Account
                </a>
            </div>
        </div>
    </header>

    <!-- Top Banner -->
    <div class="no-print bg-gradient-to-r from-emerald-900 via-straits-emerald to-emerald-800 text-white py-12 border-b border-emerald-950 text-center px-4">
        <div class="max-w-3xl mx-auto">
            <span class="px-3 py-1 rounded-full bg-emerald-400/20 text-emerald-300 text-xs font-bold border border-emerald-400/30 uppercase tracking-wider inline-block mb-3">
                100% Free Public Tool • No Sign-Up Required
            </span>
            <h1 class="text-3xl sm:text-4xl font-extrabold font-display">Free Singapore PayNow Invoice Generator</h1>
            <p class="text-sm sm:text-base text-emerald-100/90 mt-2 max-w-2xl mx-auto leading-relaxed">
                Create a professional, Singapore-compliant PDF invoice with a live dynamic PayNow SGQR code. Your clients scan with DBS, OCBC, or UOB and pay in 5 seconds with zero transaction fees.
            </p>
        </div>
    </div>

    <!-- Main Generator App -->
    <main class="max-w-6xl mx-auto px-4 sm:px-6 py-8 flex-1 w-full">

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            <!-- Left: Inputs Form -->
            <div class="no-print lg:col-span-5 bg-white p-6 sm:p-7 rounded-3xl border border-straits-border shadow-sm space-y-5">
                <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                    <h2 class="text-base font-bold text-gray-800">1. Invoice Details</h2>
                    <span class="text-xs text-straits-emerald font-semibold">Live Preview →</span>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Your Business / Trading Name</label>
                    <input type="text" id="input_business_name" value="Acme Consulting Pte Ltd" placeholder="e.g. Acme Pte Ltd"
                           class="w-full px-3 py-2 text-xs rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">PayNow UEN / Phone</label>
                        <input type="text" id="input_uen" value="200415432K" placeholder="e.g. 200415432K"
                               class="w-full px-3 py-2 text-xs font-mono rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Invoice Number</label>
                        <input type="text" id="input_invoice_number" value="INV-SG-001" placeholder="e.g. INV-001"
                               class="w-full px-3 py-2 text-xs font-mono rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Client / Customer Name</label>
                    <input type="text" id="input_client_name" value="Straits Enterprise Pte Ltd" placeholder="Client company name"
                           class="w-full px-3 py-2 text-xs rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>

                <!-- Line Item -->
                <div class="p-3 bg-gray-50 rounded-2xl border border-gray-100 space-y-3">
                    <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider block">Line Item</span>
                    <div>
                        <label class="block text-[11px] font-semibold text-gray-600 mb-1">Description</label>
                        <input type="text" id="input_item_desc" value="Monthly Digital Strategy & Retainer"
                               class="w-full px-3 py-1.5 text-xs rounded-lg border border-gray-200">
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-[11px] font-semibold text-gray-600 mb-1">Qty</label>
                            <input type="number" id="input_item_qty" value="1" min="1"
                                   class="w-full px-3 py-1.5 text-xs rounded-lg border border-gray-200">
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-gray-600 mb-1">Price (SGD)</label>
                            <input type="number" id="input_item_price" value="1500.00" step="0.01"
                                   class="w-full px-3 py-1.5 text-xs rounded-lg border border-gray-200">
                        </div>
                    </div>
                </div>

                <!-- Action Button -->
                <div class="pt-2 space-y-2">
                    <button type="button" onclick="window.print()" class="w-full py-3 rounded-xl bg-straits-emerald hover:bg-straits-emerald-dark text-white text-xs font-bold shadow-md transition flex items-center justify-center">
                        <span class="material-icons-outlined text-sm mr-1.5">print</span>
                        Print or Save as PDF
                    </button>
                    <p class="text-[11px] text-gray-400 text-center">Free forever. Generated directly in your browser.</p>
                </div>

                <!-- Upgrade Callout Banner -->
                <div class="mt-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-950 space-y-2">
                    <div class="flex items-center space-x-1.5 font-bold text-emerald-900">
                        <span class="material-icons-outlined text-sm text-emerald-600">bolt</span>
                        <span>Need automatic bank reconciliation?</span>
                    </div>
                    <p class="text-emerald-800 leading-relaxed">
                        Upgrade to <strong>StraitsLedger</strong> to auto-match DBS, OCBC & UOB bank statements and auto-file IRAS 9% GST Form 5.
                    </p>
                    <a href="/auth/login" class="inline-block font-bold text-emerald-700 hover:text-emerald-900 underline">
                        Start Free StraitsLedger Account →
                    </a>
                </div>
            </div>

            <!-- Right: Live Real-Time Invoice Preview Card -->
            <div class="lg:col-span-7 bg-white rounded-3xl border border-straits-border shadow-md p-8 print-full" id="invoice_printable">
                
                <!-- Invoice Header -->
                <div class="flex items-start justify-between border-b border-gray-200 pb-6 mb-6">
                    <div>
                        <span class="text-xs font-bold text-straits-emerald uppercase tracking-wider block">Tax Invoice</span>
                        <h2 class="text-2xl font-extrabold text-straits-slate font-display mt-0.5" id="preview_invoice_number">INV-SG-001</h2>
                        <p class="text-sm font-bold text-gray-800 mt-2" id="preview_business_name">Acme Consulting Pte Ltd</p>
                        <p class="text-xs text-gray-500">UEN: <span id="preview_uen">200415432K</span></p>
                    </div>

                    <div class="text-right">
                        <span class="text-xs text-gray-400 block">Date of Issue</span>
                        <span class="text-xs font-semibold text-gray-700">{{ date('d M Y') }}</span>
                    </div>
                </div>

                <!-- Billed To Box -->
                <div class="mb-6 p-4 rounded-xl bg-gray-50 border border-gray-100 text-xs">
                    <span class="font-bold text-gray-400 uppercase tracking-wider block mb-1">Billed To</span>
                    <p class="font-bold text-sm text-gray-800" id="preview_client_name">Straits Enterprise Pte Ltd</p>
                </div>

                <!-- Line Items Table -->
                <table class="w-full text-xs mb-6">
                    <thead>
                        <tr class="text-gray-400 font-semibold uppercase tracking-wider border-b border-gray-100 pb-2">
                            <th class="text-left py-2">Description</th>
                            <th class="text-center py-2">Qty</th>
                            <th class="text-right py-2">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <tr>
                            <td class="py-3 text-gray-800 font-medium" id="preview_item_desc">Monthly Digital Strategy & Retainer</td>
                            <td class="py-3 text-center text-gray-500" id="preview_item_qty">1</td>
                            <td class="py-3 text-right text-gray-800 font-semibold" id="preview_item_total">S$ 1,500.00</td>
                        </tr>
                    </tbody>
                </table>

                <!-- Total Amount -->
                <div class="flex justify-between items-center py-3 border-t border-b border-gray-200 mb-8 font-display">
                    <span class="text-sm font-bold text-gray-600">Total Payable (SGD)</span>
                    <span class="text-xl font-extrabold text-straits-emerald" id="preview_grand_total">S$ 1,500.00</span>
                </div>

                <!-- Dynamic PayNow QR Block on Invoice -->
                <div class="p-5 rounded-2xl bg-purple-50/60 border border-purple-200 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="space-y-1.5 text-center sm:text-left">
                        <div class="inline-flex items-center space-x-1 px-2.5 py-0.5 rounded-full bg-purple-100 text-purple-800 text-[10px] font-bold">
                            <span>PayNow SGQR</span>
                        </div>
                        <h4 class="font-bold text-xs text-purple-950">Scan with Any Singapore Banking App</h4>
                        <p class="text-[11px] text-gray-500">DBS PayLah! • OCBC Digital • UOB TMRW • GrabPay</p>
                        <p class="text-[11px] text-purple-900 font-mono">Reference: <strong id="preview_qr_ref">INV-SG-001</strong></p>
                    </div>

                    <div class="bg-white p-2.5 rounded-xl border border-purple-200 shadow-sm shrink-0">
                        <img id="preview_qr_img" src="" alt="PayNow SGQR" class="w-32 h-32">
                    </div>
                </div>

                <div class="mt-6 text-center text-[10px] text-gray-400">
                    <span>Generated via StraitsLedger • Singapore Business Suite</span>
                </div>

            </div>

        </div>

    </main>

    <!-- Footer -->
    <footer class="no-print bg-white border-t border-straits-border py-6 mt-12">
        <div class="max-w-6xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-gray-400">
            <p>© 2026 StraitsLedger • A Think Thank Pte Ltd Company (UEN: 200415432K). Built in Singapore.</p>
            <div class="flex items-center space-x-4">
                <a href="/switch-from-xero" class="hover:text-gray-600 transition">Switch from Xero</a>
                <span>•</span>
                <a href="/switch-from-quickbooks" class="hover:text-gray-600 transition">Switch from QuickBooks</a>
                <span>•</span>
                <a href="/for/gst-registered-companies" class="hover:text-gray-600 transition">IRAS GST Tool</a>
            </div>
        </div>
    </footer>

    <!-- Interactive JS to Update Preview & Generate PayNow QR on the fly -->
    <script>
        const uenInput = document.getElementById('input_uen');
        const bizInput = document.getElementById('input_business_name');
        const invInput = document.getElementById('input_invoice_number');
        const clientInput = document.getElementById('input_client_name');
        const descInput = document.getElementById('input_item_desc');
        const qtyInput = document.getElementById('input_item_qty');
        const priceInput = document.getElementById('input_item_price');

        let debounceTimer;

        function updateQr() {
            const uen = uenInput.value.trim();
            const biz = bizInput.value.trim();
            const inv = invInput.value.trim();
            const client = clientInput.value.trim();
            const desc = descInput.value.trim();
            const qty = parseFloat(qtyInput.value) || 1;
            const price = parseFloat(priceInput.value) || 0;
            const total = qty * price;

            // Update text elements
            document.getElementById('preview_business_name').innerText = biz || 'Your Business';
            document.getElementById('preview_uen').innerText = uen || '—';
            document.getElementById('preview_invoice_number').innerText = inv || 'INV-001';
            document.getElementById('preview_client_name').innerText = client || 'Client Name';
            document.getElementById('preview_item_desc').innerText = desc || 'Item Description';
            document.getElementById('preview_item_qty').innerText = qty;
            document.getElementById('preview_item_total').innerText = 'S$ ' + total.toFixed(2);
            document.getElementById('preview_grand_total').innerText = 'S$ ' + total.toFixed(2);
            document.getElementById('preview_qr_ref').innerText = inv || 'INV-001';

            // Call AJAX to get real EMVCo PayNow QR
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                fetch('/api/tools/paynow-qr', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify({
                        uen: uen || '200415432K',
                        company_name: biz || 'Merchant',
                        amount: total,
                        invoice_no: inv || 'INV-001'
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success && data.qr_code) {
                        document.getElementById('preview_qr_img').src = data.qr_code;
                    }
                })
                .catch(err => console.error(err));
            }, 300);
        }

        [uenInput, bizInput, invInput, clientInput, descInput, qtyInput, priceInput].forEach(el => {
            el.addEventListener('input', updateQr);
        });

        // Trigger on load
        window.addEventListener('DOMContentLoaded', updateQr);
    </script>

</body>
</html>
