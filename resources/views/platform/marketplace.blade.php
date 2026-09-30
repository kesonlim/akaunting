<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>App Marketplace & Integrations | StraitsLedger</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #F8FAFC; }
        .font-display { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="text-slate-900 min-h-screen flex flex-col">

    <!-- Top Master Navigation -->
    <header class="bg-[#0A3B32] text-white sticky top-0 z-40 border-b border-[#062620] shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Brand -->
                <div class="flex items-center space-x-3">
                    <a href="/platform" class="flex items-center space-x-3 group">
                        <div class="w-8 h-8 rounded-lg bg-emerald-500 flex items-center justify-center font-display font-bold text-white shadow-sm">
                            S
                        </div>
                        <div>
                            <span class="font-display font-bold text-lg tracking-tight block leading-none">StraitsLedger</span>
                            <span class="text-[10px] text-emerald-300 font-medium tracking-wider uppercase block mt-1">App Marketplace & Connectors</span>
                        </div>
                    </a>
                </div>

                <div class="flex items-center space-x-4">
                    <a href="/platform" class="text-xs font-semibold px-3 py-1.5 rounded-lg bg-emerald-900/60 hover:bg-emerald-800 text-emerald-100 transition-colors flex items-center space-x-1.5">
                        <i class="fa-solid fa-chart-line text-[11px]"></i>
                        <span>Executive Console</span>
                    </a>
                    <a href="/1" class="text-xs font-semibold px-3 py-1.5 rounded-lg bg-white text-[#0A3B32] hover:bg-emerald-50 transition-colors flex items-center space-x-1.5 shadow-sm">
                        <i class="fa-solid fa-book text-[11px]"></i>
                        <span>Think Thank Books</span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">

        @if(session('success'))
            <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium flex items-center space-x-3">
                <i class="fa-solid fa-circle-check text-emerald-600 text-lg"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm font-medium flex items-center space-x-3">
                <i class="fa-solid fa-circle-exclamation text-red-600 text-lg"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Header -->
        <div class="mb-8">
            <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-semibold mb-3">
                <i class="fa-solid fa-plug text-[11px]"></i>
                <span>Active Workspace: {{ $currentCompany->name ?? 'Think Thank Pte Ltd' }}</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-display font-extrabold text-slate-900 tracking-tight">
                Third-Party Integrations & App Connectors
            </h1>
            <p class="text-sm text-slate-500 mt-1 max-w-3xl">
                Seamlessly connect e-commerce storefronts, migration utilities, and payment rails directly into StraitsLedger.
            </p>
        </div>

        <!-- Marketplace Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            <!-- Card 1: Shopify Sync -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 flex flex-col justify-between hover:shadow-md transition-shadow">
                <div>
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-green-50 border border-green-100 flex items-center justify-center text-green-600 text-2xl font-bold">
                            <i class="fa-brands fa-shopify"></i>
                        </div>
                        @if($shopifyConnected)
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 flex items-center space-x-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                <span>Connected</span>
                            </span>
                        @else
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                Ready to Connect
                            </span>
                        @endif
                    </div>
                    <h3 class="font-display font-bold text-lg text-slate-900 mb-1">Shopify Store Sync</h3>
                    <p class="text-xs text-slate-500 leading-relaxed mb-4">
                        Automatically import orders from your Shopify stores (e.g. DiaperCakes.sg). Generates invoices, syncs customers, and tracks revenue in real-time.
                    </p>

                    <form action="{{ route('marketplace.shopify.connect') }}" method="POST" class="space-y-3 pt-3 border-t border-slate-100">
                        @csrf
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-700 uppercase tracking-wider mb-1">Shopify Store Domain</label>
                            <input type="text" name="store_domain" value="{{ $shopifyDomain ?: 'diapercakes.sg' }}" placeholder="e.g. yourstore.myshopify.com" class="w-full px-3 py-2 text-xs rounded-lg border border-slate-200 focus:outline-none focus:border-emerald-500">
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-700 uppercase tracking-wider mb-1">Webhook URL</label>
                            <input type="text" readonly value="{{ url('/api/webhooks/shopify/' . $companyId) }}" class="w-full px-3 py-1.5 text-[11px] bg-slate-50 rounded border border-slate-200 text-slate-500 font-mono select-all">
                        </div>
                        <button type="submit" class="w-full py-2 bg-[#0A3B32] hover:bg-[#062620] text-white text-xs font-bold rounded-lg transition-colors shadow-sm">
                            Save Shopify Configuration
                        </button>
                    </form>
                </div>
            </div>

            <!-- Card 2: WaveApps Cloud Migrator -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 flex flex-col justify-between hover:shadow-md transition-shadow">
                <div>
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600 text-2xl font-bold">
                            <i class="fa-solid fa-water"></i>
                        </div>
                        @if($waveConnected)
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 flex items-center space-x-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                <span>Synced</span>
                            </span>
                        @else
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                1-Click Sync
                            </span>
                        @endif
                    </div>
                    <h3 class="font-display font-bold text-lg text-slate-900 mb-1">WaveApps API Migrator</h3>
                    <p class="text-xs text-slate-500 leading-relaxed mb-4">
                        Migrate directly from WaveApps without manual CSV downloads. Pulls customers, items, accounts, and invoices via GraphQL API.
                    </p>

                    <form action="{{ route('marketplace.wave.sync') }}" method="POST" class="space-y-3 pt-3 border-t border-slate-100">
                        @csrf
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-700 uppercase tracking-wider mb-1">Wave Personal Access Token</label>
                            <input type="password" name="api_token" placeholder="Paste your Wave API Token" class="w-full px-3 py-2 text-xs rounded-lg border border-slate-200 focus:outline-none focus:border-blue-500" required>
                            <span class="text-[10px] text-slate-400 block mt-1">Generate in developer.waveapps.com → Manage Applications</span>
                        </div>
                        <button type="submit" class="w-full py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-lg transition-colors shadow-sm flex items-center justify-center space-x-1.5">
                            <i class="fa-solid fa-cloud-arrow-down text-xs"></i>
                            <span>Pull All Wave Data Now</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Card 3: PayNow SGQR -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 flex flex-col justify-between hover:shadow-md transition-shadow">
                <div>
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-purple-50 border border-purple-100 flex items-center justify-center text-purple-600 text-2xl font-bold">
                            <i class="fa-solid fa-qrcode"></i>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 flex items-center space-x-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            <span>Active (Built-In)</span>
                        </span>
                    </div>
                    <h3 class="font-display font-bold text-lg text-slate-900 mb-1">Singapore PayNow SGQR</h3>
                    <p class="text-xs text-slate-500 leading-relaxed mb-4">
                        Native Singapore PayNow QR codes embedded on every customer invoice, tied to UEN <strong>200415432K</strong>. Eliminates credit card gateway transaction fees.
                    </p>
                    <div class="pt-3 border-t border-slate-100 text-xs text-slate-600 space-y-2">
                        <div class="flex justify-between py-1 border-b border-slate-50">
                            <span class="text-slate-400">Target Entity:</span>
                            <span class="font-semibold text-slate-800">Think Thank Pte Ltd</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-slate-400">Settlement:</span>
                            <span class="font-semibold text-emerald-600">Instant to Corporate Bank</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 4: Xero & QuickBooks Migrator -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 flex flex-col justify-between hover:shadow-md transition-shadow">
                <div>
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-600 text-2xl font-bold">
                            <i class="fa-solid fa-right-left"></i>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                            Roadmap
                        </span>
                    </div>
                    <h3 class="font-display font-bold text-lg text-slate-900 mb-1">Xero & QuickBooks Sync</h3>
                    <p class="text-xs text-slate-500 leading-relaxed mb-4">
                        Transition clients off high-priced Xero and QuickBooks Online subscription tiers. 1-click ledger migration engine.
                    </p>
                </div>
                <div class="pt-3 border-t border-slate-100">
                    <button disabled class="w-full py-2 bg-slate-100 text-slate-400 text-xs font-semibold rounded-lg cursor-not-allowed">
                        In Architecture Review
                    </button>
                </div>
            </div>

            <!-- Card 5: REST API & Webhooks Engine -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 flex flex-col justify-between hover:shadow-md transition-shadow">
                <div>
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-700 text-2xl font-bold">
                            <i class="fa-solid fa-code"></i>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            Available
                        </span>
                    </div>
                    <h3 class="font-display font-bold text-lg text-slate-900 mb-1">Developer API & Webhooks</h3>
                    <p class="text-xs text-slate-500 leading-relaxed mb-4">
                        Connect custom ERPs, warehouse software, or automation tools (Zapier / Make) using StraitsLedger's REST API endpoints.
                    </p>
                </div>
                <div class="pt-3 border-t border-slate-100">
                    <a href="/api/ping" target="_blank" class="block text-center w-full py-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold rounded-lg transition-colors shadow-sm">
                        View API Endpoint Status
                    </a>
                </div>
            </div>

        </div>

    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-6 text-center text-xs text-slate-500">
        &copy; 2026 Straits &bull; A business unit of Think Thank Pte Ltd (UEN: 200415432K). All rights reserved.
    </footer>

</body>
</html>
