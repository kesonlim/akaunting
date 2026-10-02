<!DOCTYPE html>
<html lang="en-SG" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pay Invoice {{ $invoice->document_number }} | {{ $companyName }}</title>
    <meta name="description" content="Secure instant PayNow QR payment checkout portal for invoice {{ $invoice->document_number }}.">
    <link rel="icon" type="image/png" href="https://ledger.thethinkthank.com/public/img/favicon.png">

    <!-- Google Fonts -->
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
</head>
<body class="bg-straits-canvas text-straits-slate antialiased font-sans min-h-screen flex flex-col justify-between">

    <!-- Top Secure Header -->
    <header class="bg-white border-b border-straits-border sticky top-0 z-40">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 rounded-lg bg-straits-emerald text-white flex items-center justify-center font-bold text-sm">
                    S
                </div>
                <div>
                    <span class="font-display font-bold text-sm text-straits-slate">{{ $companyName }}</span>
                    <span class="text-xs text-gray-400 block sm:inline sm:ml-2">UEN: {{ $companyUen }}</span>
                </div>
            </div>

            <div class="flex items-center space-x-2">
                <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $isPaid ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                    {{ $isPaid ? 'PAID' : 'PAYMENT DUE' }}
                </span>
            </div>
        </div>
    </header>

    <!-- Main Content Container -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 py-8 flex-1 w-full">

        @if(session('success'))
        <div class="mb-6 rounded-2xl bg-emerald-50 border border-emerald-200 p-4 flex items-center space-x-3 text-emerald-800 text-sm">
            <span class="material-icons-outlined text-emerald-600">check_circle</span>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-start">

            <!-- Left Column: Invoice Details -->
            <div class="md:col-span-7 bg-white rounded-3xl border border-straits-border shadow-sm p-6 sm:p-8">
                <div class="flex items-center justify-between border-b border-gray-100 pb-5 mb-6">
                    <div>
                        <span class="text-xs font-bold text-straits-emerald uppercase tracking-wider">Tax Invoice</span>
                        <h1 class="text-2xl font-extrabold text-straits-slate font-display mt-0.5">{{ $invoice->document_number }}</h1>
                    </div>
                    <div class="text-right">
                        <span class="text-xs text-gray-400 block">Due Date</span>
                        <span class="text-sm font-semibold text-gray-700">{{ \Carbon\Carbon::parse($invoice->due_at)->format('d M Y') }}</span>
                    </div>
                </div>

                <!-- Billed To -->
                <div class="mb-6 p-4 rounded-xl bg-gray-50 border border-gray-100 text-xs">
                    <span class="font-bold text-gray-500 uppercase tracking-wider block mb-1">Billed To</span>
                    <p class="font-bold text-sm text-gray-800">{{ $invoice->contact_name }}</p>
                    @if($invoice->contact_email)
                    <p class="text-gray-500 mt-0.5">{{ $invoice->contact_email }}</p>
                    @endif
                    @if($invoice->contact_address)
                    <p class="text-gray-500 mt-0.5">{{ $invoice->contact_address }}</p>
                    @endif
                </div>

                <!-- Items Breakdown -->
                <div class="border-t border-b border-gray-100 py-4 mb-6">
                    <table class="w-full text-xs">
                        <thead>
                            <tr class="text-gray-400 font-semibold uppercase tracking-wider pb-2 border-b border-gray-100">
                                <th class="text-left py-2">Item Description</th>
                                <th class="text-center py-2">Qty</th>
                                <th class="text-right py-2">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($invoice->items as $item)
                            <tr>
                                <td class="py-3 text-gray-800 font-medium">{{ $item->name }}</td>
                                <td class="py-3 text-center text-gray-500">{{ (float) $item->quantity }}</td>
                                <td class="py-3 text-right text-gray-800 font-semibold">S$ {{ number_format($item->total, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Totals -->
                <div class="space-y-1.5 text-xs text-gray-600">
                    <div class="flex justify-between">
                        <span>Subtotal</span>
                        <span>S$ {{ number_format($invoice->amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between font-extrabold text-base text-straits-slate pt-3 border-t border-gray-200">
                        <span>Total Payable</span>
                        <span class="text-straits-emerald">S$ {{ number_format($amountDue, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Right Column: Interactive PayNow QR Checkout Card -->
            <div class="md:col-span-5 space-y-6">

                @if(!$isPaid)
                <!-- QR Code Box -->
                <div class="bg-gradient-to-b from-purple-50 to-white rounded-3xl border border-purple-200 shadow-md p-6 sm:p-8 text-center">
                    <div class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full bg-purple-100 text-purple-800 text-xs font-bold mb-4">
                        <span>🇸G Instant PayNow SGQR</span>
                    </div>

                    <h2 class="text-lg font-bold text-gray-900 font-display">Scan to Pay</h2>
                    <p class="text-xs text-gray-500 mt-1">DBS PayLah! • OCBC Digital • UOB TMRW • GrabPay</p>

                    @if(!empty($qrDataUri))
                    <div class="mt-5 p-4 bg-white rounded-2xl border-2 border-purple-300 inline-block shadow-sm">
                        <img src="{{ $qrDataUri }}" alt="PayNow SGQR Code" class="w-48 h-48 mx-auto">
                    </div>
                    @endif

                    <div class="mt-4 p-3 bg-purple-50/60 rounded-xl text-left text-xs text-purple-900 space-y-1 border border-purple-100">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Payee UEN:</span>
                            <span class="font-mono font-bold">{{ $companyUen }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Exact Amount:</span>
                            <span class="font-bold text-purple-950">S$ {{ number_format($amountDue, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Reference:</span>
                            <span class="font-mono font-bold">{{ $invoice->document_number }}</span>
                        </div>
                    </div>

                    <!-- Client Confirmation Form -->
                    <div class="mt-6 pt-5 border-t border-purple-100 text-left">
                        <p class="text-xs font-bold text-gray-800 mb-2">Already scanned and paid?</p>
                        <form action="{{ route('portal.paynow.confirm', $invoice->document_number) }}" method="POST" class="space-y-3">
                            @csrf
                            <div>
                                <label for="reference" class="block text-[11px] font-semibold text-gray-600 mb-1">Bank Reference No. / Transaction ID</label>
                                <input type="text" name="reference" id="reference" required placeholder="e.g. DBS-TX-89210 or PayNow ref"
                                       class="w-full px-3 py-2 text-xs rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-purple-500">
                            </div>
                            <button type="submit" class="w-full py-2.5 rounded-xl bg-purple-700 hover:bg-purple-800 text-white text-xs font-bold shadow-sm transition">
                                Confirm Payment Sent
                            </button>
                        </form>
                    </div>
                </div>

                @else
                <!-- Paid Confirmation Box -->
                <div class="bg-emerald-50 rounded-3xl border border-emerald-200 shadow-sm p-8 text-center">
                    <div class="w-16 h-16 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center mx-auto mb-4">
                        <span class="material-icons-outlined text-4xl">check_circle</span>
                    </div>
                    <h2 class="text-xl font-bold text-emerald-950 font-display">Invoice Settled</h2>
                    <p class="text-xs text-emerald-700 mt-2">Thank you! Payment for this invoice has been fully received and verified.</p>
                </div>
                @endif

                <!-- Viral Referral Growth Loop Card -->
                <div class="p-5 rounded-3xl bg-gradient-to-br from-emerald-900 to-straits-emerald text-white shadow-md text-left relative overflow-hidden">
                    <div class="relative z-10">
                        <div class="flex items-center space-x-2 mb-2">
                            <span class="px-2 py-0.5 text-[10px] font-bold rounded-md bg-emerald-400/20 text-emerald-300 border border-emerald-400/30 uppercase tracking-wider">Free For Singapore Businesses</span>
                        </div>
                        <h3 class="font-bold text-sm font-display text-white">Like this 1-click PayNow checkout?</h3>
                        <p class="text-xs text-emerald-100/90 mt-1 leading-relaxed">
                            Send Singapore invoices with automatic 0% fee PayNow QR codes for your own company. No subscription fees.
                        </p>
                        <div class="mt-4 flex items-center space-x-3">
                            <a href="/free-paynow-invoice-generator" target="_blank"
                               class="inline-flex items-center justify-center px-4 py-2 rounded-xl bg-white text-emerald-950 font-bold text-xs shadow-sm hover:bg-emerald-50 transition">
                                Create Free PayNow Invoice →
                            </a>
                            <a href="/switch-from-xero" target="_blank" class="text-xs text-emerald-200 hover:text-white underline">
                                Compare vs Xero
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Security & Help Badge -->
                <div class="p-4 rounded-2xl bg-white border border-straits-border text-center text-xs text-gray-500">
                    <p class="font-medium text-gray-700">🔒 Direct Bank-to-Bank Transfer</p>
                    <p class="mt-0.5 text-[11px]">Funds are transferred directly into {{ $companyName }}'s corporate account via MAS PayNow rail.</p>
                </div>

            </div>

        </div>

    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-straits-border py-6 mt-12">
        <div class="max-w-4xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-gray-400">
            <p>Powered by <a href="/" class="font-bold text-straits-emerald hover:underline">StraitsLedger</a> • Singapore Compliance Accounting</p>
            <div class="flex items-center space-x-4">
                <a href="/free-paynow-invoice-generator" class="hover:text-gray-600 transition">Free Invoice Tool</a>
                <span>•</span>
                <a href="/switch-from-xero" class="hover:text-gray-600 transition">Switch from Xero</a>
                <span>•</span>
                <a href="/switch-from-quickbooks" class="hover:text-gray-600 transition">Switch from QuickBooks</a>
            </div>
        </div>
    </footer>

</body>
</html>
