<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SaaS Master Console | StraitsLedger Executive Control Plane</title>
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
                <!-- Brand & Master Label -->
                <div class="flex items-center space-x-3">
                    <svg width="32" height="32" viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <polygon points="60,6 106,33 106,87 60,114 14,87 14,33" fill="#10B981"/>
                        <polygon points="60,20 94,40 94,80 60,100 26,80 26,40" fill="#FFFFFF"/>
                        <path d="M 42 42 L 72 42 L 72 50 L 52 50 L 52 58 L 78 58 L 78 78 L 42 78 L 42 70 L 68 70 L 68 64 L 42 64 Z" fill="#0A3B32"/>
                    </svg>
                    <div>
                        <div class="flex items-center space-x-2">
                            <span class="font-display font-extrabold text-base tracking-tight text-white">STRAITS LEDGER</span>
                            <span class="bg-[#10B981] text-[#0A3B32] font-black text-[10px] px-2 py-0.5 rounded tracking-wider uppercase">SaaS Master Console</span>
                        </div>
                        <div class="text-[11px] text-emerald-300 font-medium hidden sm:block">Executive Command Center &bull; Owner &amp; CEO Control Plane</div>
                    </div>
                </div>

                <!-- Right Side Actions & Profile -->
                <div class="flex items-center space-x-3">
                    <a href="/1" class="text-xs bg-white/10 hover:bg-white/20 text-white font-medium px-3.5 py-1.5 rounded-lg border border-white/20 transition flex items-center space-x-1.5">
                        <i class="fa-solid fa-arrow-right-to-bracket text-emerald-400"></i>
                        <span class="hidden sm:inline">Switch to Tenant Ledger</span>
                        <span class="sm:hidden">Tenant Books</span>
                    </a>

                    <button onclick="document.getElementById('provisionModal').classList.remove('hidden')" class="text-xs bg-[#10B981] hover:bg-emerald-400 text-[#0A3B32] font-bold px-3.5 py-1.5 rounded-lg shadow-sm transition flex items-center space-x-1.5">
                        <i class="fa-solid fa-plus"></i>
                        <span>Provision Tenant</span>
                    </button>

                    <div class="h-6 w-px bg-emerald-800/60 hidden sm:block"></div>

                    <div class="flex items-center space-x-2 text-xs text-emerald-100">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span class="font-medium hidden md:inline">{{ auth()->user()->email }}</span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">

        @if (session('success'))
            <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium flex items-center justify-between shadow-sm">
                <div class="flex items-center space-x-2">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-lg"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900">&times;</button>
            </div>
        @endif

        <!-- Executive KPI Banner -->
        <div class="mb-8">
            <h1 class="text-2xl font-display font-extrabold text-slate-900 tracking-tight">SaaS Business Performance</h1>
            <p class="text-slate-500 text-sm mt-1">Real-time telemetry across subscribed Singapore SME companies, subscription run rate, and platform usage.</p>
        </div>

        <!-- 4 Key Metrics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
            <!-- MRR -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:shadow transition">
                <div class="flex items-center justify-between text-slate-500 mb-2">
                    <span class="text-xs font-semibold uppercase tracking-wider">Monthly Recurring (MRR)</span>
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm">
                        <i class="fa-solid fa-chart-line"></i>
                    </div>
                </div>
                <div class="text-2xl font-display font-extrabold text-slate-900">
                    S$ {{ number_format($totalMrr, 2) }}
                </div>
                <div class="mt-2 flex items-center text-xs text-emerald-600 font-medium">
                    <i class="fa-solid fa-arrow-trend-up mr-1"></i>
                    <span>+18.4% from last quarter</span>
                </div>
            </div>

            <!-- ARR -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:shadow transition">
                <div class="flex items-center justify-between text-slate-500 mb-2">
                    <span class="text-xs font-semibold uppercase tracking-wider">Annualized Run Rate (ARR)</span>
                    <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center text-sm">
                        <i class="fa-solid fa-sack-dollar"></i>
                    </div>
                </div>
                <div class="text-2xl font-display font-extrabold text-slate-900">
                    S$ {{ number_format($totalArr, 2) }}
                </div>
                <div class="mt-2 text-xs text-slate-500 font-medium">
                    Based on {{ $activeTenantsCount }} active subscriber accounts
                </div>
            </div>

            <!-- Active Companies -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:shadow transition">
                <div class="flex items-center justify-between text-slate-500 mb-2">
                    <span class="text-xs font-semibold uppercase tracking-wider">Subscribed Companies</span>
                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-sm">
                        <i class="fa-solid fa-building"></i>
                    </div>
                </div>
                <div class="text-2xl font-display font-extrabold text-slate-900">
                    {{ $activeTenantsCount }} <span class="text-sm font-normal text-slate-400">Tenants</span>
                </div>
                <div class="mt-2 text-xs text-slate-500 font-medium flex items-center space-x-1.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span>100% platform health</span>
                </div>
            </div>

            <!-- Total Users -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:shadow transition">
                <div class="flex items-center justify-between text-slate-500 mb-2">
                    <span class="text-xs font-semibold uppercase tracking-wider">Platform Users</span>
                    <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-sm">
                        <i class="fa-solid fa-users"></i>
                    </div>
                </div>
                <div class="text-2xl font-display font-extrabold text-slate-900">
                    {{ $totalUsersCount }} <span class="text-sm font-normal text-slate-400">Seats</span>
                </div>
                <div class="mt-2 text-xs text-slate-500 font-medium">
                    Directors, Accountants &amp; Bookkeepers
                </div>
            </div>
        </div>

        <!-- Singapore Compliance & Operational Strip -->
        <div class="bg-gradient-to-r from-[#0A3B32] to-[#124e43] rounded-2xl p-6 text-white mb-8 shadow-sm">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 border-b border-emerald-700/50 pb-4 mb-4">
                <div>
                    <h3 class="text-base font-bold font-display flex items-center space-x-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                        <span>Singapore Statutory &amp; Clearing Telemetry</span>
                    </h3>
                    <p class="text-xs text-emerald-200 mt-0.5">Automated compliance engines running across all active Singapore tenants.</p>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="text-[11px] bg-emerald-500/20 text-emerald-200 border border-emerald-400/30 px-2.5 py-1 rounded-full font-semibold">
                        <i class="fa-solid fa-shield-halved mr-1"></i> MAS TRM Aligned
                    </span>
                    <span class="text-[11px] bg-emerald-500/20 text-emerald-200 border border-emerald-400/30 px-2.5 py-1 rounded-full font-semibold">
                        <i class="fa-solid fa-lock mr-1"></i> Singapore Data Residency
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs">
                <div class="bg-white/10 rounded-xl p-3 border border-white/10">
                    <div class="text-emerald-300 font-medium">IRAS 9% GST Engine</div>
                    <div class="text-lg font-bold mt-1">Box 1-8 Auto-Calc</div>
                    <div class="text-[10px] text-emerald-200/80 mt-0.5">Active across all SG entities</div>
                </div>
                <div class="bg-white/10 rounded-xl p-3 border border-white/10">
                    <div class="text-emerald-300 font-medium">PayNow Corporate SGQR</div>
                    <div class="text-lg font-bold mt-1">EMVCo Standard</div>
                    <div class="text-[10px] text-emerald-200/80 mt-0.5">Dynamic QR on invoices</div>
                </div>
                <div class="bg-white/10 rounded-xl p-3 border border-white/10">
                    <div class="text-emerald-300 font-medium">InvoiceNow (Peppol)</div>
                    <div class="text-lg font-bold mt-1">IMDA Aligned</div>
                    <div class="text-[10px] text-emerald-200/80 mt-0.5">Pre-configured e-invoicing</div>
                </div>
                <div class="bg-white/10 rounded-xl p-3 border border-white/10">
                    <div class="text-emerald-300 font-medium">Banking Rails</div>
                    <div class="text-lg font-bold mt-1">DBS &bull; OCBC &bull; UOB</div>
                    <div class="text-[10px] text-emerald-200/80 mt-0.5">Statement reconciliation engine</div>
                </div>
            </div>
        </div>

        <!-- Tenant Directory Table -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-12">
            <div class="px-6 py-5 border-b border-slate-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                <div>
                    <h2 class="font-display font-bold text-lg text-slate-900">Subscribed Companies &amp; Tenant Accounts</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Select any company to enter its accounting workspace or manage its plan.</p>
                </div>
                <div class="flex items-center space-x-2 text-xs">
                    <span class="text-slate-400">Total Entities:</span>
                    <span class="font-bold text-slate-800 bg-slate-100 px-2 py-0.5 rounded-full">{{ $companies->count() }}</span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50/80 text-slate-600 font-semibold border-b border-slate-200">
                            <th class="py-3.5 px-6">Company / Entity</th>
                            <th class="py-3.5 px-6">Singapore UEN</th>
                            <th class="py-3.5 px-6">SaaS Plan Tier</th>
                            <th class="py-3.5 px-6">Monthly Revenue</th>
                            <th class="py-3.5 px-6">Users</th>
                            <th class="py-3.5 px-6">Status</th>
                            <th class="py-3.5 px-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @foreach ($companies as $comp)
                            <tr class="hover:bg-slate-50/60 transition">
                                <!-- Company Name -->
                                <td class="py-4 px-6 font-semibold text-slate-900">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-9 h-9 rounded-lg bg-[#0A3B32] text-white flex items-center justify-center font-bold text-xs uppercase shadow-xs">
                                            {{ substr($comp->name, 0, 2) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-sm text-slate-900">{{ $comp->name }}</div>
                                            <div class="text-[11px] text-slate-400">Tenant ID: #{{ $comp->id }} &bull; Added {{ \Carbon\Carbon::parse($comp->created_at)->format('d M Y') }}</div>
                                        </div>
                                    </div>
                                </td>

                                <!-- UEN -->
                                <td class="py-4 px-6 font-mono text-slate-600">
                                    <span class="bg-slate-100 px-2 py-1 rounded text-[11px] font-semibold text-slate-700 border border-slate-200">
                                        {{ $comp->uen }}
                                    </span>
                                </td>

                                <!-- Plan Tier -->
                                <td class="py-4 px-6">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $comp->badge }}">
                                        {{ $comp->plan }}
                                    </span>
                                </td>

                                <!-- MRR -->
                                <td class="py-4 px-6 font-bold text-slate-900">
                                    @if ($comp->mrr > 0)
                                        S$ {{ number_format($comp->mrr, 2) }}/mo
                                    @else
                                        <span class="text-slate-400 italic">Internal</span>
                                    @endif
                                </td>

                                <!-- Users -->
                                <td class="py-4 px-6">
                                    <span class="inline-flex items-center space-x-1 text-slate-600">
                                        <i class="fa-solid fa-user-group text-slate-400 text-[10px]"></i>
                                        <span>{{ $comp->user_count }} seat(s)</span>
                                    </span>
                                </td>

                                <!-- Status -->
                                <td class="py-4 px-6">
                                    @if ($comp->enabled)
                                        <span class="inline-flex items-center space-x-1.5 text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full text-[11px] font-medium border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            <span>Active</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center space-x-1.5 text-slate-500 bg-slate-50 px-2 py-0.5 rounded-full text-[11px] font-medium border border-slate-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                            <span>Disabled</span>
                                        </span>
                                    @endif
                                </td>

                                <!-- Actions -->
                                <td class="py-4 px-6 text-right">
                                    <a href="/platform/switch/{{ $comp->id }}" class="inline-flex items-center space-x-1.5 bg-[#0A3B32] hover:bg-[#072A24] text-white px-3 py-1.5 rounded-lg text-xs font-semibold shadow-xs transition transform hover:-translate-y-0.5">
                                        <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                                        <span>Access Workspace</span>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </main>

    <!-- Provisioning Modal -->
    <div id="provisionModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-slate-200 animate-in fade-in zoom-in-95 duration-150">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 rounded-lg bg-[#0A3B32] text-white flex items-center justify-center">
                        <i class="fa-solid fa-plus text-sm"></i>
                    </div>
                    <div>
                        <h3 class="font-display font-bold text-base text-slate-900">Provision New SME Tenant</h3>
                        <p class="text-xs text-slate-500">Create a new company ledger with instant IRAS 9% GST configuration.</p>
                    </div>
                </div>
                <button onclick="document.getElementById('provisionModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 text-lg">&times;</button>
            </div>

            <form action="{{ route('platform.provision') }}" method="POST" class="mt-5 space-y-4 text-xs">
                @csrf
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Company Registered Name *</label>
                    <input type="text" name="company_name" required placeholder="e.g. Marina Bay Hospitality Pte. Ltd." class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0A3B32] text-sm">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Singapore UEN *</label>
                        <input type="text" name="company_uen" required placeholder="e.g. 202419820K" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0A3B32] font-mono text-sm uppercase">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Subscription Plan *</label>
                        <select name="plan_tier" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0A3B32] text-sm">
                            <option value="Business Pro">Business Pro (S$49/mo)</option>
                            <option value="Accounting Practice">Accounting Practice (S$89/mo)</option>
                            <option value="Enterprise Sovereign">Enterprise Sovereign (S$299/mo)</option>
                            <option value="Micro-SME Starter">Micro-SME Starter (S$19/mo)</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Primary Admin Email *</label>
                    <input type="email" name="company_email" required placeholder="finance@company.sg" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0A3B32] text-sm">
                </div>

                <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-3 text-[11px] text-emerald-800 flex items-start space-x-2">
                    <i class="fa-solid fa-check-circle text-emerald-600 mt-0.5"></i>
                    <span>Automatically provisions standard Singapore Chart of Accounts (SFRS), pre-configures 9% GST tax codes, and generates PayNow QR payment rails.</span>
                </div>

                <div class="flex items-center justify-end space-x-3 pt-3 border-t border-slate-100">
                    <button type="button" onclick="document.getElementById('provisionModal').classList.add('hidden')" class="px-4 py-2 border border-slate-300 rounded-lg text-slate-700 font-semibold hover:bg-slate-50 transition">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2 bg-[#0A3B32] hover:bg-[#072A24] text-white font-bold rounded-lg shadow-sm transition">
                        Complete Provisioning
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-6 text-center text-xs text-slate-500">
        <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-2">
            <div>&copy; 2026 Straits &bull; A business unit of Think Thank Pte Ltd (UEN: 200415432K). All rights reserved.</div>
            <div class="flex items-center space-x-4 text-slate-400">
                <span>IMDA Peppol Ready</span>
                <span>&bull;</span>
                <span>IRAS Form 5 Active</span>
                <span>&bull;</span>
                <span>PayNow SGQR Enabled</span>
            </div>
        </div>
    </footer>

</body>
</html>
