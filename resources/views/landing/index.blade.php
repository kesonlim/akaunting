<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StraitsLedger — Singapore Cloud Accounting & IRAS Tax</title>
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 120 120'%3E%3Cpolygon points='60,6 106,33 106,87 60,114 14,87 14,33' fill='%230A3B32'/%3E%3Cpolygon points='60,6 106,33 106,87 60,114 60,60' fill='%2310B981'/%3E%3Cpolygon points='60,20 94,40 94,80 60,100 26,80 26,40' fill='%23FFFFFF'/%3E%3Cpath d='M 42 42 L 72 42 L 72 50 L 52 50 L 52 58 L 78 58 L 78 78 L 42 78 L 42 70 L 68 70 L 68 64 L 42 64 Z' fill='%230A3B32'/%3E%3C/svg%3E">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --brand-forest: #0A3B32;
            --brand-emerald: #10B981;
            --brand-mint: #ECFDF5;
            --brand-stone: #E8E5DF;
            --bg-canvas: #FAFAFA;
        }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--bg-canvas);
            color: #0F172A;
            scroll-behavior: smooth;
        }
        .font-display {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .tabular-num {
            font-variant-numeric: tabular-nums;
            font-feature-settings: "tnum";
        }
        details summary::-webkit-details-marker {
            display: none;
        }
        details[open] summary .faq-icon {
            transform: rotate(180deg);
        }
    </style>
</head>
<body class="bg-[#FAFAFA] text-[#0F172A] antialiased">

    <!-- Top Compliance Banner -->
    <div class="bg-[#0A3B32] text-white text-xs font-medium text-center py-2.5 px-4 flex items-center justify-center gap-2">
        <span class="bg-[#10B981]/25 text-[#A7F3D0] text-[10px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">IRAS 9% GST READY</span>
        <span>Pre-configured standard-rated, zero-rated, and exempt tax codes with PayNow SG QR.</span>
    </div>

    <!-- Top Navigation -->
    <nav class="border-b border-[#E8E5DF] bg-white/95 backdrop-blur sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center h-20">
            <!-- Brand Lockup -->
            <a href="/" class="flex items-center space-x-3 text-decoration-none">
                <svg width="36" height="36" viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <polygon points="60,6 106,33 106,87 60,114 14,87 14,33" fill="#0A3B32"/>
                    <polygon points="60,6 106,33 106,87 60,114 60,60" fill="#10B981" fill-opacity="0.85"/>
                    <polygon points="60,114 106,87 60,60" fill="#E8E5DF" fill-opacity="0.4"/>
                    <polygon points="60,114 14,87 60,60" fill="#062620" fill-opacity="0.6"/>
                    <polygon points="60,20 94,40 94,80 60,100 26,80 26,40" fill="#FFFFFF" stroke="#E8E5DF" stroke-width="1.5"/>
                    <path d="M 42 42 L 72 42 L 72 50 L 52 50 L 52 58 L 78 58 L 78 78 L 42 78 L 42 70 L 68 70 L 68 64 L 42 64 Z" fill="#0A3B32"/>
                    <path d="M 40 40 L 70 40 L 70 48 L 50 48 L 50 56 L 76 56 L 76 76 L 40 76 L 40 68 L 66 68 L 66 62 L 40 62 Z" fill="#E8E5DF"/>
                </svg>
                <div>
                    <div class="font-display font-extrabold text-xl tracking-tight text-[#0A3B32] leading-none">STRAITS LEDGER</div>
                    <div class="text-[10px] font-semibold text-[#10B981] tracking-widest mt-0.5">CLOUD ACCOUNTING &amp; TAX</div>
                </div>
            </a>

            <!-- Nav Links -->
            <div class="hidden lg:flex items-center space-x-7 text-sm font-medium text-[#4B5563]">
                <a href="#features" class="hover:text-[#0A3B32] transition">Features</a>
                <a href="#comparison" class="hover:text-[#0A3B32] transition">Why StraitsLedger</a>
                <a href="#migration" class="hover:text-[#0A3B32] transition">5-Min Migration</a>
                <a href="#ecosystem" class="hover:text-[#0A3B32] transition">Straits Suite</a>
                <a href="#pricing" class="hover:text-[#0A3B32] transition">Pricing</a>
                <a href="#faq" class="hover:text-[#0A3B32] transition">FAQ</a>
            </div>

            <!-- Actions -->
            <div class="flex items-center space-x-2 sm:space-x-3">
                <a href="/auth/login" class="text-xs sm:text-sm font-semibold text-[#0A3B32] hover:bg-[#F4F1EA] px-3 sm:px-4 py-2 rounded-lg transition border border-[#E8E5DF]">Log In</a>
                <a href="#signup" onclick="openSignupModal()" class="bg-[#0A3B32] hover:bg-[#072A24] text-white text-xs sm:text-sm font-semibold px-3 sm:px-5 py-2 sm:py-2.5 rounded-lg shadow-sm transition transform hover:-translate-y-0.5 whitespace-nowrap"><span class="hidden sm:inline">Start 14-Day </span>Free Trial</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative pt-20 pb-24 overflow-hidden bg-gradient-to-b from-white via-[#FAFAFA] to-[#F4F1EA] border-b border-[#E8E5DF]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <div class="inline-flex items-center space-x-2 bg-[#ECFDF5] border border-[#10B981]/30 px-4 py-1.5 rounded-full text-xs font-semibold text-[#0A3B32] mb-8 shadow-sm">
                <span class="w-2 h-2 rounded-full bg-[#10B981] animate-pulse"></span>
                <span>IRAS 9% GST Form 5 Verified &bull; PayNow SG QR Embedded</span>
            </div>

            <h1 class="font-display text-4xl md:text-6xl font-extrabold text-[#0A3B32] tracking-tight leading-tight max-w-4xl mx-auto">
                Singapore’s Cloud Accounting Platform for <span class="text-[#10B981]">SMEs &amp; Practices</span>
            </h1>

            <p class="mt-6 text-lg md:text-xl text-[#4B5563] max-w-2xl mx-auto font-normal leading-relaxed">
                Automate IRAS GST Form 5 filings, issue PayNow QR e-invoices, and auto-post payroll journals seamlessly from <strong class="text-[#0A3B32]">StraitsHR</strong>.
            </p>

            <div class="mt-10 flex flex-col sm:flex-row justify-center items-center gap-4">
                <button onclick="openSignupModal()" class="w-full sm:w-auto bg-[#0A3B32] hover:bg-[#072A24] text-white text-base font-bold px-8 py-4 rounded-xl shadow-lg shadow-[#0A3B32]/10 transition transform hover:-translate-y-0.5 flex items-center justify-center">
                    <i class="fa-solid fa-rocket mr-2"></i> Start 14-Day Free Trial
                </button>
                <a href="#comparison" class="w-full sm:w-auto bg-white hover:bg-[#F4F1EA] text-[#0A3B32] border border-[#E8E5DF] text-base font-semibold px-8 py-4 rounded-xl transition flex items-center justify-center">
                    <i class="fa-solid fa-scale-balanced mr-2 text-[#10B981]"></i> See Why SMEs Switch
                </a>
            </div>

            <!-- Hero Feature Bullets -->
            <div class="mt-12 grid grid-cols-2 md:grid-cols-4 gap-6 max-w-4xl mx-auto border-t border-[#E8E5DF] pt-8 text-left">
                <div class="flex items-center space-x-3">
                    <i class="fa-solid fa-check-circle text-[#10B981] text-lg"></i>
                    <span class="text-xs font-semibold text-[#0F172A]">IRAS Form 5 Auto-Calculator</span>
                </div>
                <div class="flex items-center space-x-3">
                    <i class="fa-solid fa-check-circle text-[#10B981] text-lg"></i>
                    <span class="text-xs font-semibold text-[#0F172A]">PayNow SG QR Invoicing</span>
                </div>
                <div class="flex items-center space-x-3">
                    <i class="fa-solid fa-check-circle text-[#10B981] text-lg"></i>
                    <span class="text-xs font-semibold text-[#0F172A]">1-Click StraitsHR Sync</span>
                </div>
                <div class="flex items-center space-x-3">
                    <i class="fa-solid fa-check-circle text-[#10B981] text-lg"></i>
                    <span class="text-xs font-semibold text-[#0F172A]">Multi-Entity Agency Portal</span>
                </div>
            </div>

            <!-- Hero Product Mockup / Interactive Financial Console -->
            <div class="mt-14 max-w-5xl mx-auto rounded-2xl bg-white border border-[#E8E5DF] shadow-2xl overflow-hidden text-left">
                <!-- Window Header -->
                <div class="bg-[#F8FAFC] border-b border-[#E8E5DF] px-4 py-3 flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <span class="w-3 h-3 rounded-full bg-red-400"></span>
                        <span class="w-3 h-3 rounded-full bg-amber-400"></span>
                        <span class="w-3 h-3 rounded-full bg-emerald-400"></span>
                        <span class="text-xs font-semibold text-slate-500 ml-2">app.straitsledger.sg &bull; Acme Tech Pte. Ltd. (UEN: 202418920K)</span>
                    </div>
                    <div class="flex items-center space-x-2 text-[11px] font-medium text-emerald-800 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-200">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span>IRAS 9% GST Active</span>
                    </div>
                </div>

                <!-- Dashboard KPI Banner -->
                <div class="p-6 bg-slate-50/60 border-b border-[#E8E5DF] grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-sm">
                        <div class="text-[11px] font-medium text-slate-500">Q3 Operating Revenue</div>
                        <div class="text-xl font-extrabold text-[#0A3B32] mt-1 tabular-num">S$ 248,920.00</div>
                        <div class="text-[10px] font-semibold text-emerald-600 mt-1 flex items-center">
                            <i class="fa-solid fa-arrow-trend-up mr-1"></i> +18.4% vs Q2
                        </div>
                    </div>
                    <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-sm">
                        <div class="text-[11px] font-medium text-slate-500">IRAS Form 5 Net Payable</div>
                        <div class="text-xl font-extrabold text-slate-900 mt-1 tabular-num">S$ 14,210.50</div>
                        <div class="text-[10px] font-semibold text-slate-500 mt-1">Due 31 Oct 2026</div>
                    </div>
                    <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-sm">
                        <div class="text-[11px] font-medium text-slate-500">PayNow SGQR Invoicing</div>
                        <div class="text-xl font-extrabold text-purple-700 mt-1 tabular-num">S$ 82,450.00</div>
                        <div class="text-[10px] font-semibold text-purple-600 mt-1">Instant Bank Settled</div>
                    </div>
                    <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-sm">
                        <div class="text-[11px] font-medium text-slate-500">StraitsHR Payroll Sync</div>
                        <div class="text-xl font-extrabold text-blue-700 mt-1 tabular-num">S$ 42,100.00</div>
                        <div class="text-[10px] font-semibold text-blue-600 mt-1">14 Pax &bull; CPF Posted</div>
                    </div>
                </div>

                <!-- Dashboard Content Preview Split -->
                <div class="p-6 grid grid-cols-1 md:grid-cols-12 gap-6 bg-white">
                    <!-- Left: IRAS Form 5 e-Tax Widget -->
                    <div class="md:col-span-7 bg-[#FAFAFA] border border-slate-200/80 rounded-xl p-5">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-200">
                            <div class="flex items-center space-x-2">
                                <i class="fa-solid fa-file-invoice text-emerald-600"></i>
                                <span class="font-display font-bold text-sm text-[#0A3B32]">IRAS GST Return (Form 5)</span>
                            </div>
                            <span class="text-[10px] font-bold bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded">Pre-Audit Verified</span>
                        </div>
                        <div class="mt-4 space-y-2.5 text-xs">
                            <div class="flex justify-between py-1.5 border-b border-slate-200/60">
                                <span class="text-slate-600">Box 1: Standard-Rated Supplies (9%)</span>
                                <span class="font-bold text-slate-900 tabular-num">S$ 215,000.00</span>
                            </div>
                            <div class="flex justify-between py-1.5 border-b border-slate-200/60">
                                <span class="text-slate-600">Box 6: Output Tax Due</span>
                                <span class="font-bold text-slate-900 tabular-num">S$ 19,350.00</span>
                            </div>
                            <div class="flex justify-between py-1.5 border-b border-slate-200/60">
                                <span class="text-slate-600">Box 7: Input Tax &amp; Import Claimed</span>
                                <span class="font-bold text-emerald-700 tabular-num">-S$ 5,139.50</span>
                            </div>
                            <div class="flex justify-between py-2 bg-emerald-50/80 px-3 rounded-lg border border-emerald-100 font-bold">
                                <span class="text-[#0A3B32]">Box 8: Net GST to be Paid to IRAS</span>
                                <span class="text-[#0A3B32] tabular-num text-sm">S$ 14,210.50</span>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Live Journal Feeds -->
                    <div class="md:col-span-5 bg-[#FAFAFA] border border-slate-200/80 rounded-xl p-5 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between pb-3 border-b border-slate-200">
                                <span class="font-display font-bold text-sm text-[#0A3B32]">Singapore Smart Feeds</span>
                                <span class="text-[10px] font-semibold text-slate-500">Live Sync</span>
                            </div>
                            <div class="mt-3 space-y-3">
                                <div class="flex items-center justify-between text-xs">
                                    <div class="flex items-center space-x-2">
                                        <div class="w-7 h-7 bg-purple-100 text-purple-700 rounded-lg flex items-center justify-center text-[11px] font-bold">QR</div>
                                        <div>
                                            <div class="font-semibold text-slate-800">DBS PayNow Inbound</div>
                                            <div class="text-[10px] text-slate-400">Temasek Marine &bull; Auto-reconciled</div>
                                        </div>
                                    </div>
                                    <span class="font-bold text-emerald-600 tabular-num">+S$ 4,895.00</span>
                                </div>
                                <div class="flex items-center justify-between text-xs">
                                    <div class="flex items-center space-x-2">
                                        <div class="w-7 h-7 bg-blue-100 text-blue-700 rounded-lg flex items-center justify-center text-[11px] font-bold">HR</div>
                                        <div>
                                            <div class="font-semibold text-slate-800">StraitsHR Payroll Sync</div>
                                            <div class="text-[10px] text-slate-400">CPF (17%) &amp; SDL Journal Posted</div>
                                        </div>
                                    </div>
                                    <span class="font-bold text-slate-700 tabular-num">-S$ 42,100.00</span>
                                </div>
                                <div class="flex items-center justify-between text-xs">
                                    <div class="flex items-center space-x-2">
                                        <div class="w-7 h-7 bg-emerald-100 text-emerald-700 rounded-lg flex items-center justify-center text-[11px] font-bold">PE</div>
                                        <div>
                                            <div class="font-semibold text-slate-800">InvoiceNow (Peppol)</div>
                                            <div class="text-[10px] text-slate-400">GovTech Singapore &bull; E-Tax Ack</div>
                                        </div>
                                    </div>
                                    <span class="font-bold text-slate-700 tabular-num">+S$ 18,200.00</span>
                                </div>
                            </div>
                        </div>
                        <div class="pt-3 border-t border-slate-200 flex items-center justify-between text-[10px] text-slate-500">
                            <span>MAS TRM Guidelines Aligned</span>
                            <span class="font-semibold text-emerald-700">Audit-Trail Ready</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 1: Singapore Banking & Ecosystem Trust Strip -->
    <section class="py-12 bg-white border-b border-[#E8E5DF]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="text-center text-[11px] font-bold tracking-widest uppercase text-slate-500 mb-8">
                COMPATIBLE WITH SINGAPORE'S BANKING &amp; CLEARING INFRASTRUCTURE
            </p>
            <div class="grid grid-cols-2 md:grid-cols-6 gap-6 items-center justify-center opacity-85 hover:opacity-100 transition">
                <!-- DBS -->
                <div class="flex flex-col items-center p-3 rounded-xl border border-slate-200/80 bg-slate-50/50 hover:bg-white hover:border-[#10B981] transition text-center shadow-xs">
                    <span class="text-xs font-black tracking-wider text-red-600">DBS IDEAL</span>
                    <span class="text-[10px] text-slate-500 mt-0.5 font-medium">CSV &amp; OFX Statement Sync</span>
                </div>
                <!-- OCBC -->
                <div class="flex flex-col items-center p-3 rounded-xl border border-slate-200/80 bg-slate-50/50 hover:bg-white hover:border-[#10B981] transition text-center shadow-xs">
                    <span class="text-xs font-black tracking-wider text-red-700">OCBC Velocity</span>
                    <span class="text-[10px] text-slate-500 mt-0.5 font-medium">Statement Import Sync</span>
                </div>
                <!-- UOB -->
                <div class="flex flex-col items-center p-3 rounded-xl border border-slate-200/80 bg-slate-50/50 hover:bg-white hover:border-[#10B981] transition text-center shadow-xs">
                    <span class="text-xs font-black tracking-wider text-blue-800">UOB Infinity</span>
                    <span class="text-[10px] text-slate-500 mt-0.5 font-medium">Reconciliation Ready</span>
                </div>
                <!-- PayNow -->
                <div class="flex flex-col items-center p-3 rounded-xl border border-slate-200/80 bg-slate-50/50 hover:bg-white hover:border-[#10B981] transition text-center shadow-xs">
                    <span class="text-xs font-black tracking-wider text-purple-700">PayNow SGQR</span>
                    <span class="text-[10px] text-slate-500 mt-0.5 font-medium">SGQR Invoicing</span>
                </div>
                <!-- InvoiceNow / Peppol -->
                <div class="flex flex-col items-center p-3 rounded-xl border border-slate-200/80 bg-slate-50/50 hover:bg-white hover:border-[#10B981] transition text-center shadow-xs">
                    <span class="text-xs font-black tracking-wider text-emerald-700">InvoiceNow</span>
                    <span class="text-[10px] text-slate-500 mt-0.5 font-medium">Peppol-Ready Schema</span>
                </div>
                <!-- StanChart -->
                <div class="flex flex-col items-center p-3 rounded-xl border border-slate-200/80 bg-slate-50/50 hover:bg-white hover:border-[#10B981] transition text-center shadow-xs">
                    <span class="text-xs font-black tracking-wider text-blue-600">StanChart</span>
                    <span class="text-[10px] text-slate-500 mt-0.5 font-medium">Straight2Bank CSV</span>
                </div>
            </div>

            <!-- Trust Metrics Bar -->
            <div class="mt-10 pt-8 border-t border-slate-100 grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                <div>
                    <div class="font-display font-extrabold text-2xl text-[#0A3B32]">100%</div>
                    <div class="text-xs text-slate-500 mt-1 font-medium">IRAS 9% GST Tax Codes Configured</div>
                </div>
                <div>
                    <div class="font-display font-extrabold text-2xl text-[#0A3B32]">&lt; 60 Sec</div>
                    <div class="text-xs text-slate-500 mt-1 font-medium">Smart Statement Reconciliation</div>
                </div>
                <div>
                    <div class="font-display font-extrabold text-2xl text-[#0A3B32]">256-Bit</div>
                    <div class="text-xs text-slate-500 mt-1 font-medium">Bank-Grade TLS / SSL Encryption</div>
                </div>
                <div>
                    <div class="font-display font-extrabold text-2xl text-[#0A3B32]">SG Compliant</div>
                    <div class="text-xs text-slate-500 mt-1 font-medium">Singapore PDPA &amp; MAS TRM Aligned</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Key Features Grid -->
    <section id="features" class="py-24 bg-[#FAFAFA] border-b border-[#E8E5DF]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-xs font-bold text-[#10B981] uppercase tracking-widest">Built For Singapore Compliance</h2>
                <p class="font-display text-3xl font-extrabold text-[#0A3B32] mt-2">Everything You Need to Run Your Business Finance</p>
                <p class="text-slate-500 text-sm max-w-2xl mx-auto mt-2">Engineered specifically around Singapore corporate statutory reporting, IRAS tax codes, and banking rails.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1: IRAS Form 5 -->
                <div class="bg-white p-8 rounded-2xl border border-[#E8E5DF] hover:border-[#10B981] transition shadow-sm hover:shadow-md">
                    <div class="w-12 h-12 bg-[#ECFDF5] text-[#059669] rounded-xl flex items-center justify-center text-2xl mb-6">
                        <i class="fa-solid fa-file-invoice-dollar"></i>
                    </div>
                    <div class="text-[10px] font-bold text-emerald-800 bg-emerald-50 px-2.5 py-0.5 rounded-full inline-block mb-3 border border-emerald-200">IRAS E-TAX READY</div>
                    <h3 class="font-display text-xl font-bold text-[#0A3B32] mb-2">IRAS 9% GST Form 5</h3>
                    <p class="text-[#4B5563] text-sm leading-relaxed">
                        Pre-configured 9% standard-rated, zero-rated, and exempt tax categories. Auto-populate Boxes 1 through 8 for instant IRAS portal filing without manual spreadsheets.
                    </p>
                </div>

                <!-- Feature 2: PayNow & InvoiceNow -->
                <div class="bg-white p-8 rounded-2xl border border-[#E8E5DF] hover:border-[#10B981] transition shadow-sm hover:shadow-md">
                    <div class="w-12 h-12 bg-[#ECFDF5] text-[#059669] rounded-xl flex items-center justify-center text-2xl mb-6">
                        <i class="fa-solid fa-qrcode"></i>
                    </div>
                    <div class="text-[10px] font-bold text-purple-800 bg-purple-50 px-2.5 py-0.5 rounded-full inline-block mb-3 border border-purple-200">SGQR &bull; PEPPOL</div>
                    <h3 class="font-display text-xl font-bold text-[#0A3B32] mb-2">PayNow &amp; InvoiceNow</h3>
                    <p class="text-[#4B5563] text-sm leading-relaxed">
                        Embed PayNow Corporate SGQR codes on customer PDF invoices for 1-scan payment collection. Fully compliant with Singapore IMDA Peppol InvoiceNow standard.
                    </p>
                </div>

                <!-- Feature 3: StraitsHR Sync -->
                <div class="bg-white p-8 rounded-2xl border border-[#E8E5DF] hover:border-[#10B981] transition shadow-sm hover:shadow-md">
                    <div class="w-12 h-12 bg-[#ECFDF5] text-[#059669] rounded-xl flex items-center justify-center text-2xl mb-6">
                        <i class="fa-solid fa-arrows-rotate"></i>
                    </div>
                    <div class="text-[10px] font-bold text-blue-800 bg-blue-50 px-2.5 py-0.5 rounded-full inline-block mb-3 border border-blue-200">STRAITS SUITE ECOSYSTEM</div>
                    <h3 class="font-display text-xl font-bold text-[#0A3B32] mb-2">1-Click StraitsHR Sync</h3>
                    <p class="text-[#4B5563] text-sm leading-relaxed">
                        Seamless 1-click sync of monthly wages, employer CPF (17%), and SDL from StraitsHR directly into StraitsLedger General Ledger. Zero duplicate journal entries.
                    </p>
                </div>

                <!-- Feature 4: Multi-Currency SGD/USD/MYR -->
                <div class="bg-white p-8 rounded-2xl border border-[#E8E5DF] hover:border-[#10B981] transition shadow-sm hover:shadow-md">
                    <div class="w-12 h-12 bg-[#ECFDF5] text-[#059669] rounded-xl flex items-center justify-center text-2xl mb-6">
                        <i class="fa-solid fa-coins"></i>
                    </div>
                    <div class="text-[10px] font-bold text-emerald-800 bg-emerald-50 px-2.5 py-0.5 rounded-full inline-block mb-3 border border-emerald-200">MAS EXCHANGE FEEDS</div>
                    <h3 class="font-display text-xl font-bold text-[#0A3B32] mb-2">Multi-Currency Ledger</h3>
                    <p class="text-[#4B5563] text-sm leading-relaxed">
                        Full multi-currency support (SGD, USD, MYR, EUR, GBP) with automatic realized and unrealized FX gain/loss computations aligned with Singapore accounting standards (SFRS).
                    </p>
                </div>

                <!-- Feature 5: Bank Feeds & GIRO Reconciliation -->
                <div class="bg-white p-8 rounded-2xl border border-[#E8E5DF] hover:border-[#10B981] transition shadow-sm hover:shadow-md">
                    <div class="w-12 h-12 bg-[#ECFDF5] text-[#059669] rounded-xl flex items-center justify-center text-2xl mb-6">
                        <i class="fa-solid fa-building-columns"></i>
                    </div>
                    <div class="text-[10px] font-bold text-slate-800 bg-slate-100 px-2.5 py-0.5 rounded-full inline-block mb-3 border border-slate-200">DBS &bull; OCBC &bull; UOB</div>
                    <h3 class="font-display text-xl font-bold text-[#0A3B32] mb-2">Bank &amp; GIRO Reconciliation</h3>
                    <p class="text-[#4B5563] text-sm leading-relaxed">
                        Import standard corporate bank statements and export GIRO payment files. Smart matching reconciles invoices, bills, and payroll disbursements in minutes.
                    </p>
                </div>

                <!-- Feature 6: Multi-Entity Agency Portal -->
                <div class="bg-white p-8 rounded-2xl border border-[#E8E5DF] hover:border-[#10B981] transition shadow-sm hover:shadow-md">
                    <div class="w-12 h-12 bg-[#ECFDF5] text-[#059669] rounded-xl flex items-center justify-center text-2xl mb-6">
                        <i class="fa-solid fa-sitemap"></i>
                    </div>
                    <div class="text-[10px] font-bold text-emerald-800 bg-emerald-50 px-2.5 py-0.5 rounded-full inline-block mb-3 border border-emerald-200">FOR CORPORATE PRACTICES</div>
                    <h3 class="font-display text-xl font-bold text-[#0A3B32] mb-2">Multi-Entity Portal</h3>
                    <p class="text-[#4B5563] text-sm leading-relaxed">
                        Designed for corporate secretaries, accounting firms, and conglomerates managing multiple Singapore UENs under a unified console with granular team permissions.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 2: The Strategic Comparison Matrix -->
    <section id="comparison" class="py-24 bg-white border-b border-[#E8E5DF]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="text-xs font-bold text-[#10B981] uppercase tracking-widest">Built From Scratch For Singapore</span>
                <h2 class="font-display text-3xl md:text-4xl font-extrabold text-[#0A3B32] mt-2">Why Modern Businesses Choose StraitsLedger</h2>
                <p class="text-slate-500 text-sm max-w-2xl mx-auto mt-2">See how StraitsLedger compares to generic legacy accounting software and fragmented spreadsheets.</p>
            </div>

            <!-- Comparison Table Container -->
            <div class="block md:hidden text-center text-xs text-slate-400 mb-3 font-medium">
                <i class="fa-solid fa-arrows-left-right mr-1 text-emerald-600"></i> Swipe sideways to compare platforms
            </div>
            <div class="overflow-x-auto rounded-2xl border border-[#E8E5DF] shadow-sm bg-white">
                <table class="w-full min-w-[760px] text-left border-collapse">
                    <thead>
                        <tr class="border-b border-[#E8E5DF] bg-slate-50/75">
                            <th class="py-5 px-6 text-sm font-bold text-slate-800 w-1/3">Core Capability</th>
                            <th class="py-5 px-6 text-sm font-extrabold text-[#0A3B32] bg-[#ECFDF5]/60 border-x border-[#10B981]/30 w-1/4">
                                <div class="flex items-center space-x-2">
                                    <span>StraitsLedger</span>
                                    <span class="text-[10px] font-bold bg-[#10B981] text-white px-2 py-0.5 rounded-full uppercase">SG Compliant</span>
                                </div>
                            </th>
                            <th class="py-5 px-6 text-sm font-semibold text-slate-600 w-1/5">Legacy Cloud SaaS<br><span class="text-[11px] font-normal text-slate-400">(Xero / QuickBooks)</span></th>
                            <th class="py-5 px-6 text-sm font-semibold text-slate-600 w-1/5">Manual Spreadsheets<br><span class="text-[11px] font-normal text-slate-400">(Excel / Google Sheets)</span></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E8E5DF] text-xs">
                        <!-- Row 1: IRAS Form 5 -->
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-4 px-6 font-semibold text-slate-900">
                                <div>IRAS 9% GST Reporting</div>
                                <div class="text-[11px] font-normal text-slate-500 mt-0.5">Pre-configured 9% standard, zero-rated &amp; exempt Singapore tax codes</div>
                            </td>
                            <td class="py-4 px-6 font-bold text-emerald-800 bg-[#ECFDF5]/30 border-x border-[#10B981]/20">
                                <i class="fa-solid fa-circle-check text-emerald-600 mr-1.5 text-sm"></i> Pre-Configured IRAS Tax Engine
                            </td>
                            <td class="py-4 px-6 text-slate-600">
                                Requires manual setup or costly 3rd-party tax app
                            </td>
                            <td class="py-4 px-6 text-red-600 font-medium">
                                <i class="fa-solid fa-circle-xmark text-red-400 mr-1.5"></i> High risk of formula error &amp; IRAS audit penalty
                            </td>
                        </tr>

                        <!-- Row 2: PayNow SGQR -->
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-4 px-6 font-semibold text-slate-900">
                                <div>PayNow Corporate SGQR on Invoices</div>
                                <div class="text-[11px] font-normal text-slate-500 mt-0.5">Mobile banking SGQR format support on customer invoices</div>
                            </td>
                            <td class="py-4 px-6 font-bold text-emerald-800 bg-[#ECFDF5]/30 border-x border-[#10B981]/20">
                                <i class="fa-solid fa-circle-check text-emerald-600 mr-1.5 text-sm"></i> PayNow SGQR Standard
                            </td>
                            <td class="py-4 px-6 text-slate-600">
                                Requires external Stripe/integrator add-on with fees
                            </td>
                            <td class="py-4 px-6 text-red-600 font-medium">
                                <i class="fa-solid fa-circle-xmark text-red-400 mr-1.5"></i> Static text UEN, zero automated reconciliation
                            </td>
                        </tr>

                        <!-- Row 3: InvoiceNow Peppol -->
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-4 px-6 font-semibold text-slate-900">
                                <div>InvoiceNow (Peppol) Architecture</div>
                                <div class="text-[11px] font-normal text-slate-500 mt-0.5">Standardized data schema aligned with Singapore IMDA Peppol framework</div>
                            </td>
                            <td class="py-4 px-6 font-bold text-emerald-800 bg-[#ECFDF5]/30 border-x border-[#10B981]/20">
                                <i class="fa-solid fa-circle-check text-emerald-600 mr-1.5 text-sm"></i> Peppol-Ready Schema (Direct Gateway Q4)
                            </td>
                            <td class="py-4 px-6 text-slate-600">
                                Multi-step setup with secondary access point gate
                            </td>
                            <td class="py-4 px-6 text-red-600 font-medium">
                                <i class="fa-solid fa-circle-xmark text-red-400 mr-1.5"></i> Not supported
                            </td>
                        </tr>

                        <!-- Row 4: Payroll & CPF Sync -->
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-4 px-6 font-semibold text-slate-900">
                                <div>1-Click Payroll &amp; CPF Journal Sync</div>
                                <div class="text-[11px] font-normal text-slate-500 mt-0.5">Auto-post wages, employer CPF (17%), and SDL from HR into General Ledger</div>
                            </td>
                            <td class="py-4 px-6 font-bold text-emerald-800 bg-[#ECFDF5]/30 border-x border-[#10B981]/20">
                                <i class="fa-solid fa-circle-check text-emerald-600 mr-1.5 text-sm"></i> Native Sync with StraitsHR
                            </td>
                            <td class="py-4 px-6 text-slate-600">
                                Manual journal entry or S$80+/mo payroll plugin
                            </td>
                            <td class="py-4 px-6 text-red-600 font-medium">
                                <i class="fa-solid fa-circle-xmark text-red-400 mr-1.5"></i> Manual copy-paste across separate workbooks
                            </td>
                        </tr>

                        <!-- Row 5: Pricing & Cost -->
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-4 px-6 font-semibold text-slate-900">
                                <div>Pricing Transparency &amp; FX Fees</div>
                                <div class="text-[11px] font-normal text-slate-500 mt-0.5">True total cost of ownership for a growing Singapore SME</div>
                            </td>
                            <td class="py-4 px-6 font-bold text-emerald-800 bg-[#ECFDF5]/30 border-x border-[#10B981]/20">
                                <i class="fa-solid fa-circle-check text-emerald-600 mr-1.5 text-sm"></i> Transparent SGD (From S$19/mo)
                            </td>
                            <td class="py-4 px-6 text-slate-600">
                                Starts at S$70+/mo + surcharge for multi-currency
                            </td>
                            <td class="py-4 px-6 text-slate-500 font-medium">
                                Zero software cost, but 20+ hours of lost admin time
                            </td>
                        </tr>

                        <!-- Row 6: Data Sovereignty -->
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-4 px-6 font-semibold text-slate-900">
                                <div>Data Sovereignty &amp; Privacy (PDPA)</div>
                                <div class="text-[11px] font-normal text-slate-500 mt-0.5">Singapore corporate data residency and strict regulatory jurisdiction</div>
                            </td>
                            <td class="py-4 px-6 font-bold text-emerald-800 bg-[#ECFDF5]/30 border-x border-[#10B981]/20">
                                <i class="fa-solid fa-circle-check text-emerald-600 mr-1.5 text-sm"></i> Singapore Cloud
                            </td>
                            <td class="py-4 px-6 text-slate-600">
                                Foreign multi-tenant cloud storage
                            </td>
                            <td class="py-4 px-6 text-red-600 font-medium">
                                <i class="fa-solid fa-triangle-exclamation text-amber-500 mr-1.5"></i> Unencrypted files vulnerable to device loss
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Comparison Bottom CTA -->
            <div class="mt-8 text-center">
                <button onclick="openSignupModal()" class="inline-flex items-center space-x-2 bg-[#0A3B32] hover:bg-[#072A24] text-white font-bold px-6 py-3 rounded-xl text-sm shadow-md transition">
                    <span>Switch to StraitsLedger Risk-Free</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </button>
            </div>
        </div>
    </section>

    <!-- SECTION 3: 3-Step Zero-Downtime Migration Section -->
    <section id="migration" class="py-24 bg-gradient-to-b from-white to-[#F8FAFC] border-b border-[#E8E5DF]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="text-xs font-bold text-[#10B981] uppercase tracking-widest">Effortless Onboarding</span>
                <h2 class="font-display text-3xl md:text-4xl font-extrabold text-[#0A3B32] mt-2">Switch in Under 5 Minutes</h2>
                <p class="text-slate-500 text-sm max-w-2xl mx-auto mt-2">
                    Worried about migrating from Xero, QuickBooks, or Excel? Our Singapore auto-mapper transfers your historical data with zero downtime.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative">
                <!-- Step 1 -->
                <div class="bg-white p-8 rounded-2xl border border-[#E8E5DF] relative shadow-sm hover:shadow-md transition">
                    <div class="w-10 h-10 rounded-full bg-[#0A3B32] text-white font-display font-extrabold text-base flex items-center justify-center mb-6">
                        1
                    </div>
                    <h3 class="font-display font-bold text-lg text-[#0A3B32] mb-2">Export Current Data</h3>
                    <p class="text-slate-600 text-xs leading-relaxed">
                        Download your existing Chart of Accounts, Customers, Suppliers, and historical transactions from Xero, QuickBooks, or Excel as a standard CSV.
                    </p>
                    <div class="mt-6 pt-4 border-t border-slate-100 flex items-center text-[11px] font-semibold text-slate-500">
                        <i class="fa-solid fa-file-csv text-emerald-600 mr-2"></i> Standard CSV &bull; Excel Supported
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="bg-white p-8 rounded-2xl border-2 border-[#10B981] relative shadow-md">
                    <div class="w-10 h-10 rounded-full bg-[#10B981] text-white font-display font-extrabold text-base flex items-center justify-center mb-6">
                        2
                    </div>
                    <div class="absolute top-6 right-6">
                        <span class="text-[10px] font-bold bg-[#ECFDF5] text-emerald-800 border border-emerald-200 px-2 py-0.5 rounded-full">AUTO-MAP</span>
                    </div>
                    <h3 class="font-display font-bold text-lg text-[#0A3B32] mb-2">1-Click Smart Mapping</h3>
                    <p class="text-slate-600 text-xs leading-relaxed">
                        StraitsLedger automatically aligns your ledger codes with Singapore Financial Reporting Standards (SFRS) and applies current IRAS 9% GST rules in seconds.
                    </p>
                    <div class="mt-6 pt-4 border-t border-slate-100 flex items-center text-[11px] font-semibold text-emerald-700">
                        <i class="fa-solid fa-wand-magic-sparkles mr-2"></i> Automated SFRS &amp; GST Alignment
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="bg-white p-8 rounded-2xl border border-[#E8E5DF] relative shadow-sm hover:shadow-md transition">
                    <div class="w-10 h-10 rounded-full bg-[#0A3B32] text-white font-display font-extrabold text-base flex items-center justify-center mb-6">
                        3
                    </div>
                    <h3 class="font-display font-bold text-lg text-[#0A3B32] mb-2">Reconcile &amp; Invoice</h3>
                    <p class="text-slate-600 text-xs leading-relaxed">
                        Connect your DBS, OCBC, or UOB bank statement, verify your opening balance, and issue your first PayNow SGQR e-invoice immediately.
                    </p>
                    <div class="mt-6 pt-4 border-t border-slate-100 flex items-center text-[11px] font-semibold text-slate-500">
                        <i class="fa-solid fa-qrcode text-purple-600 mr-2"></i> Ready for Immediate Invoicing
                    </div>
                </div>
            </div>

            <!-- Concierge Migration Callout -->
            <div class="mt-12 bg-white rounded-2xl border border-slate-200 p-6 max-w-3xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-6 shadow-xs">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-xl bg-[#ECFDF5] text-emerald-700 flex items-center justify-center text-xl flex-shrink-0">
                        <i class="fa-solid fa-headset"></i>
                    </div>
                    <div>
                        <h4 class="font-display font-bold text-sm text-[#0A3B32]">Need Migration Assistance?</h4>
                        <p class="text-xs text-slate-500 mt-0.5">Our Singapore support team provides complimentary assisted migration for all Business Pro and Practice accounts.</p>
                    </div>
                </div>
                <button onclick="openSignupModal('Assisted Migration')" class="whitespace-nowrap bg-slate-100 hover:bg-slate-200 text-[#0A3B32] text-xs font-bold px-4 py-2.5 rounded-lg border border-slate-200 transition">
                    Request Assisted Migration
                </button>
            </div>
        </div>
    </section>

    <!-- Cross-Product Suite Section: Straits Ecosystem -->
    <section id="ecosystem" class="py-24 bg-white border-b border-[#E8E5DF]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="text-xs font-bold text-[#10B981] uppercase tracking-widest">Part of the Straits Operating Platform</span>
                <h2 class="font-display text-3xl font-extrabold text-[#0A3B32] mt-2">The Unified Straits Business Suite</h2>
                <p class="text-[#4B5563] text-sm max-w-2xl mx-auto mt-2">A sovereign trio of Singapore business solutions engineered to communicate natively under one unified login.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Pillar 1: Straits Parent Platform -->
                <a href="https://straits.thethinkthank.com" target="_blank" class="block bg-[#FAFAFA] p-8 rounded-2xl border border-[#E8E5DF] hover:border-[#10B981] hover:shadow-lg transition group text-decoration-none flex flex-col justify-between">
                    <div>
                        <div class="flex items-center space-x-4 mb-4">
                            <div class="w-12 h-12 bg-[#0A3B32] text-white rounded-xl flex items-center justify-center font-bold text-xl">
                                <svg width="28" height="28" viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <polygon points="60,6 106,33 106,87 60,114 14,87 14,33" fill="#FFFFFF"/>
                                    <polygon points="60,6 106,33 106,87 60,114 60,60" fill="#10B981"/>
                                    <polygon points="60,20 94,40 94,80 60,100 26,80 26,40" fill="#0A3B32"/>
                                    <path d="M 42 42 L 72 42 L 72 50 L 52 50 L 52 58 L 78 58 L 78 78 L 42 78 L 42 70 L 68 70 L 68 64 L 42 64 Z" fill="#FFFFFF"/>
                                </svg>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h3 class="font-display text-xl font-bold text-[#0A3B32] group-hover:text-[#10B981] transition">Straits</h3>
                                    <span class="text-[10px] bg-slate-100 text-slate-700 font-bold px-2 py-0.5 rounded-full border border-slate-200">PARENT OS</span>
                                </div>
                                <p class="text-xs text-[#4B5563]">The Straits Platform</p>
                            </div>
                        </div>
                        <p class="text-sm text-[#4B5563] leading-relaxed mb-6">
                            Master holding portal and unified corporate identity provider connecting accounting, workforce payroll, and statutory reporting under single sign-on.
                        </p>
                    </div>
                    <span class="text-xs font-bold text-[#0A3B32] group-hover:text-[#10B981] inline-flex items-center pt-4 border-t border-slate-200">
                        Visit Straits Portal <i class="fa-solid fa-arrow-right ml-2 text-xs"></i>
                    </span>
                </a>

                <!-- Pillar 2: StraitsLedger (Current) -->
                <div class="block bg-white p-8 rounded-2xl border-2 border-[#10B981] shadow-lg relative flex flex-col justify-between">
                    <div class="absolute -top-3 left-1/2 -translate-x-1/2 bg-[#10B981] text-white text-[10px] font-bold px-3 py-0.5 rounded-full uppercase tracking-wider">
                        Current Application
                    </div>
                    <div>
                        <div class="flex items-center space-x-4 mb-4">
                            <div class="w-12 h-12 bg-[#0A3B32] text-white rounded-xl flex items-center justify-center font-bold text-xl">
                                <svg width="28" height="28" viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <polygon points="60,6 106,33 106,87 60,114 14,87 14,33" fill="#FFFFFF"/>
                                    <polygon points="60,6 106,33 106,87 60,114 60,60" fill="#10B981"/>
                                    <polygon points="60,20 94,40 94,80 60,100 26,80 26,40" fill="#0A3B32"/>
                                    <path d="M 42 42 L 72 42 L 72 50 L 52 50 L 52 58 L 78 58 L 78 78 L 42 78 L 42 70 L 68 70 L 68 64 L 42 64 Z" fill="#FFFFFF"/>
                                </svg>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h3 class="font-display text-xl font-bold text-[#0A3B32]">StraitsLedger</h3>
                                    <span class="text-[10px] bg-[#ECFDF5] text-[#059669] font-bold px-2 py-0.5 rounded-full border border-emerald-200">ACCOUNTING</span>
                                </div>
                                <p class="text-xs text-[#4B5563]">IRAS Tax &amp; General Ledger</p>
                            </div>
                        </div>
                        <p class="text-sm text-[#4B5563] leading-relaxed mb-6">
                            Automate IRAS 9% GST Form 5, issue PayNow Corporate QR invoices, and maintain double-entry books with native InvoiceNow Peppol capabilities.
                        </p>
                    </div>
                    <span class="text-xs font-bold text-emerald-700 inline-flex items-center pt-4 border-t border-slate-100">
                        <i class="fa-solid fa-circle-check text-emerald-500 mr-2 text-xs"></i> Active Suite Component
                    </span>
                </div>

                <!-- Pillar 3: StraitsHR -->
                <a href="https://hr.thethinkthank.com" target="_blank" class="block bg-[#FAFAFA] p-8 rounded-2xl border border-[#E8E5DF] hover:border-[#10B981] hover:shadow-lg transition group text-decoration-none flex flex-col justify-between">
                    <div>
                        <div class="flex items-center space-x-4 mb-4">
                            <div class="w-12 h-12 bg-[#0A3B32] text-white rounded-xl flex items-center justify-center font-bold text-xl">
                                HR
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h3 class="font-display text-xl font-bold text-[#0A3B32] group-hover:text-[#10B981] transition">StraitsHR</h3>
                                    <span class="text-[10px] bg-[#ECFDF5] text-[#059669] font-bold px-2 py-0.5 rounded-full border border-emerald-200">MOM COMPLIANT</span>
                                </div>
                                <p class="text-xs text-[#4B5563]">Cloud Payroll &amp; Workforce Engine</p>
                            </div>
                        </div>
                        <p class="text-sm text-[#4B5563] leading-relaxed mb-6">
                            Automated CPF 2026 statutory rates, itemized payslips, GIRO disbursement files, and 1-click payroll journal posting into StraitsLedger.
                        </p>
                    </div>
                    <span class="text-xs font-bold text-[#0A3B32] group-hover:text-[#10B981] inline-flex items-center pt-4 border-t border-slate-200">
                        Launch StraitsHR Portal <i class="fa-solid fa-arrow-right ml-2 text-xs"></i>
                    </span>
                </a>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="py-24 bg-[#FAFAFA] border-b border-[#E8E5DF]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-xs font-bold text-[#10B981] uppercase tracking-widest">Simple &amp; Transparent Pricing</h2>
                <p class="font-display text-3xl font-extrabold text-[#0A3B32] mt-2">Predictable Plans for Growing Singapore Businesses</p>
                <p class="text-slate-500 text-sm max-w-2xl mx-auto mt-2">All plans include 14-day free trial. No credit card required. Cancel anytime.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Starter -->
                <div class="bg-white p-8 rounded-2xl border border-[#E8E5DF] flex flex-col justify-between shadow-xs">
                    <div>
                        <h3 class="font-display font-bold text-lg text-[#0A3B32]">Micro-SME Starter</h3>
                        <p class="text-xs text-[#4B5563] mt-1">For early-stage Singapore businesses</p>
                        <div class="my-6">
                            <span class="tabular-num text-4xl font-extrabold text-[#0A3B32]">S$ 19</span>
                            <span class="text-[#4B5563] text-sm">/ month</span>
                        </div>
                        <ul class="space-y-3 text-xs text-[#4B5563]">
                            <li class="flex items-center"><i class="fa-solid fa-check text-[#10B981] mr-2"></i> Up to 50 Sales Invoices / mo</li>
                            <li class="flex items-center"><i class="fa-solid fa-check text-[#10B981] mr-2"></i> IRAS 9% GST Form 5 Calculator</li>
                            <li class="flex items-center"><i class="fa-solid fa-check text-[#10B981] mr-2"></i> PayNow SG QR Invoicing</li>
                            <li class="flex items-center"><i class="fa-solid fa-check text-[#10B981] mr-2"></i> 1 Free Accountant Seat</li>
                        </ul>
                    </div>
                    <button onclick="openSignupModal('Starter')" class="mt-8 w-full bg-white hover:bg-[#F4F1EA] text-[#0A3B32] font-semibold py-3 rounded-xl text-xs border border-[#E8E5DF] transition">Select Starter</button>
                </div>

                <!-- Pro -->
                <div class="bg-white p-8 rounded-2xl border-2 border-[#0A3B32] relative flex flex-col justify-between shadow-xl">
                    <div class="absolute -top-3 left-1/2 -translate-x-1/2 bg-[#0A3B32] text-white text-[10px] font-bold px-3 py-0.5 rounded-full uppercase">Most Popular</div>
                    <div>
                        <h3 class="font-display font-bold text-lg text-[#0A3B32]">Business Pro</h3>
                        <p class="text-xs text-[#4B5563] mt-1">Includes StraitsHR Payroll Sync</p>
                        <div class="my-6">
                            <span class="tabular-num text-4xl font-extrabold text-[#0A3B32]">S$ 49</span>
                            <span class="text-[#4B5563] text-sm">/ month</span>
                        </div>
                        <ul class="space-y-3 text-xs text-[#4B5563]">
                            <li class="flex items-center"><i class="fa-solid fa-check text-[#10B981] mr-2"></i> Unlimited Invoices &amp; Expenses</li>
                            <li class="flex items-center"><i class="fa-solid fa-check text-[#10B981] mr-2"></i> IRAS GST Form 5 Auto-Filing Export</li>
                            <li class="flex items-center"><i class="fa-solid fa-check text-[#10B981] mr-2"></i> <strong>1-Click StraitsHR Payroll Sync</strong></li>
                            <li class="flex items-center"><i class="fa-solid fa-check text-[#10B981] mr-2"></i> Multi-Currency SGD/USD/MYR</li>
                            <li class="flex items-center"><i class="fa-solid fa-check text-[#10B981] mr-2"></i> Free Assisted Data Migration</li>
                        </ul>
                    </div>
                    <button onclick="openSignupModal('Pro')" class="mt-8 w-full bg-[#0A3B32] hover:bg-[#072A24] text-white font-bold py-3 rounded-xl text-xs shadow-md transition">Start 14-Day Free Trial</button>
                </div>

                <!-- Agency -->
                <div class="bg-white p-8 rounded-2xl border border-[#E8E5DF] flex flex-col justify-between shadow-xs">
                    <div>
                        <h3 class="font-display font-bold text-lg text-[#0A3B32]">Accounting Practice</h3>
                        <p class="text-xs text-[#4B5563] mt-1">For firms managing multiple client UENs</p>
                        <div class="my-6">
                            <span class="tabular-num text-4xl font-extrabold text-[#0A3B32]">S$ 89</span>
                            <span class="text-[#4B5563] text-sm">/ month</span>
                        </div>
                        <ul class="space-y-3 text-xs text-[#4B5563]">
                            <li class="flex items-center"><i class="fa-solid fa-check text-[#10B981] mr-2"></i> Multi-Entity Client Portal (10 UENs)</li>
                            <li class="flex items-center"><i class="fa-solid fa-check text-[#10B981] mr-2"></i> Batch IRAS GST Return Export</li>
                            <li class="flex items-center"><i class="fa-solid fa-check text-[#10B981] mr-2"></i> InvoiceNow Peppol Access Point</li>
                            <li class="flex items-center"><i class="fa-solid fa-check text-[#10B981] mr-2"></i> Priority Dedicated Support</li>
                        </ul>
                    </div>
                    <button onclick="openSignupModal('Agency')" class="mt-8 w-full bg-white hover:bg-[#F4F1EA] text-[#0A3B32] font-semibold py-3 rounded-xl text-xs border border-[#E8E5DF] transition">Select Practice Plan</button>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 4: Objection-Buster FAQ Accordion -->
    <section id="faq" class="py-24 bg-white border-b border-[#E8E5DF]">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="text-xs font-bold text-[#10B981] uppercase tracking-widest">Frequently Asked Questions</span>
                <h2 class="font-display text-3xl font-extrabold text-[#0A3B32] mt-2">Clear Answers to Common Questions</h2>
                <p class="text-slate-500 text-sm max-w-xl mx-auto mt-2">Everything you need to know about IRAS compliance, data security, and seamless migration.</p>
            </div>

            <div class="space-y-4">
                <!-- FAQ Item 1 -->
                <details class="group bg-[#FAFAFA] rounded-2xl border border-[#E8E5DF] p-6 transition open:bg-white open:shadow-sm" open>
                    <summary class="flex justify-between items-center cursor-pointer list-none font-display font-bold text-base text-[#0A3B32]">
                        <span>Is StraitsLedger accepted by IRAS for corporate GST filing?</span>
                        <span class="faq-icon text-[#10B981] transition transform duration-200 ml-4 flex-shrink-0">
                            <i class="fa-solid fa-chevron-down"></i>
                        </span>
                    </summary>
                    <div class="mt-4 text-xs text-slate-600 leading-relaxed border-t border-slate-200/60 pt-3">
                        Yes. StraitsLedger is engineered specifically around Singapore GST tax legislation. The system automatically categorizes your transactions into standard-rated (9%), zero-rated, and exempt supplies, generating accurate figures for Boxes 1 through 8 on IRAS Form 5. You can export complete IRAS audit trails and e-Tax files for instant online submission.
                    </div>
                </details>

                <!-- FAQ Item 2 -->
                <details class="group bg-[#FAFAFA] rounded-2xl border border-[#E8E5DF] p-6 transition open:bg-white open:shadow-sm">
                    <summary class="flex justify-between items-center cursor-pointer list-none font-display font-bold text-base text-[#0A3B32]">
                        <span>Can my external accountant or tax agent access my accounts?</span>
                        <span class="faq-icon text-[#10B981] transition transform duration-200 ml-4 flex-shrink-0">
                            <i class="fa-solid fa-chevron-down"></i>
                        </span>
                    </summary>
                    <div class="mt-4 text-xs text-slate-600 leading-relaxed border-t border-slate-200/60 pt-3">
                        Yes. Every StraitsLedger subscription includes complimentary accountant and bookkeeper access. You can invite your external certified accountant or corporate secretarial provider with tailored roles (Auditor, Bookkeeper, or Administrator) without paying for extra software seats.
                    </div>
                </details>

                <!-- FAQ Item 3 -->
                <details class="group bg-[#FAFAFA] rounded-2xl border border-[#E8E5DF] p-6 transition open:bg-white open:shadow-sm">
                    <summary class="flex justify-between items-center cursor-pointer list-none font-display font-bold text-base text-[#0A3B32]">
                        <span>How does StraitsLedger integrate with StraitsHR for payroll and CPF?</span>
                        <span class="faq-icon text-[#10B981] transition transform duration-200 ml-4 flex-shrink-0">
                            <i class="fa-solid fa-chevron-down"></i>
                        </span>
                    </summary>
                    <div class="mt-4 text-xs text-slate-600 leading-relaxed border-t border-slate-200/60 pt-3">
                        Because StraitsLedger and StraitsHR are built on the same sovereign Straits Business Suite architecture, monthly payroll runs automatically generate balanced double-entry journals. With 1 click, gross wages, employer CPF contributions (17%), employee CPF deductions (20%), and Skills Development Levy (SDL) are posted directly into your General Ledger without manual re-keying.
                    </div>
                </details>

                <!-- FAQ Item 4 -->
                <details class="group bg-[#FAFAFA] rounded-2xl border border-[#E8E5DF] p-6 transition open:bg-white open:shadow-sm">
                    <summary class="flex justify-between items-center cursor-pointer list-none font-display font-bold text-base text-[#0A3B32]">
                        <span>Can I import statements from DBS, OCBC, and UOB corporate accounts?</span>
                        <span class="faq-icon text-[#10B981] transition transform duration-200 ml-4 flex-shrink-0">
                            <i class="fa-solid fa-chevron-down"></i>
                        </span>
                    </summary>
                    <div class="mt-4 text-xs text-slate-600 leading-relaxed border-t border-slate-200/60 pt-3">
                        Yes. StraitsLedger supports statement import formats from all major Singapore financial institutions, including DBS IDEAL, OCBC Velocity, UOB Infinity, and Standard Chartered. The system’s reconciliation engine matches customer payments, PayNow settlement references, and vendor bills automatically.
                    </div>
                </details>

                <!-- FAQ Item 5 -->
                <details class="group bg-[#FAFAFA] rounded-2xl border border-[#E8E5DF] p-6 transition open:bg-white open:shadow-sm">
                    <summary class="flex justify-between items-center cursor-pointer list-none font-display font-bold text-base text-[#0A3B32]">
                        <span>What happens if I ever decide to leave? Is my data locked in?</span>
                        <span class="faq-icon text-[#10B981] transition transform duration-200 ml-4 flex-shrink-0">
                            <i class="fa-solid fa-chevron-down"></i>
                        </span>
                    </summary>
                    <div class="mt-4 text-xs text-slate-600 leading-relaxed border-t border-slate-200/60 pt-3">
                        You retain 100% ownership of your business data at all times. There is zero vendor lock-in. You can download complete ledger archives, balance sheets, profit &amp; loss statements, and invoice attachments in open CSV and PDF formats whenever you wish.
                    </div>
                </details>

                <!-- FAQ Item 6 -->
                <details class="group bg-[#FAFAFA] rounded-2xl border border-[#E8E5DF] p-6 transition open:bg-white open:shadow-sm">
                    <summary class="flex justify-between items-center cursor-pointer list-none font-display font-bold text-base text-[#0A3B32]">
                        <span>Is our company data hosted securely in Singapore?</span>
                        <span class="faq-icon text-[#10B981] transition transform duration-200 ml-4 flex-shrink-0">
                            <i class="fa-solid fa-chevron-down"></i>
                        </span>
                    </summary>
                    <div class="mt-4 text-xs text-slate-600 leading-relaxed border-t border-slate-200/60 pt-3">
                        Yes. All data is stored in ISO 27001-certified infrastructure with local Singapore data residency, fully complying with Singapore’s Personal Data Protection Act (PDPA) and aligning with Monetary Authority of Singapore (MAS) Technology Risk Management guidelines. All connections are secured via 256-bit TLS/SSL encryption.
                    </div>
                </details>
            </div>

            <!-- Still have questions prompt -->
            <div class="mt-12 text-center">
                <p class="text-xs text-slate-500 mb-3">Still have questions about Singapore tax rules or data migration?</p>
                <a href="mailto:support@thethinkthank.com" class="text-xs font-bold text-[#0A3B32] hover:text-[#10B981] transition inline-flex items-center">
                    <i class="fa-solid fa-envelope mr-1.5"></i> Speak with our Singapore Accounting Specialists
                </a>
            </div>
        </div>
    </section>

    <!-- Modal: Signup / Free Trial -->
    <div id="signup-modal" class="fixed inset-0 bg-[#0A3B32]/40 backdrop-blur-sm z-50 flex items-center justify-center hidden">
        <div class="bg-white border border-[#E8E5DF] rounded-2xl max-w-md w-full p-6 shadow-2xl relative">
            <button onclick="closeSignupModal()" class="absolute top-4 right-4 text-[#4B5563] hover:text-[#0F172A]"><i class="fa-solid fa-xmark text-lg"></i></button>
            
            <h3 class="font-display text-xl font-bold text-[#0A3B32] mb-1">Start Your StraitsLedger Trial</h3>
            <p class="text-xs text-[#4B5563] mb-6">Full access for 14 days. No credit card required.</p>

            <form action="/auth/signup" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-[#0F172A] mb-1">Company Name</label>
                    <input type="text" name="company_name" placeholder="e.g. Acme Tech Solutions Pte. Ltd." class="w-full bg-[#FAFAFA] border border-[#E8E5DF] rounded-xl p-3 text-sm text-[#0F172A] focus:ring-2 focus:ring-[#10B981] outline-none" required>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-[#0F172A] mb-1">Work Email</label>
                    <input type="email" name="email" placeholder="you@company.sg" class="w-full bg-[#FAFAFA] border border-[#E8E5DF] rounded-xl p-3 text-sm text-[#0F172A] focus:ring-2 focus:ring-[#10B981] outline-none" required>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-[#0F172A] mb-1">Password</label>
                    <input type="password" name="password" placeholder="••••••••" class="w-full bg-[#FAFAFA] border border-[#E8E5DF] rounded-xl p-3 text-sm text-[#0F172A] focus:ring-2 focus:ring-[#10B981] outline-none" required>
                </div>
                <button type="submit" class="w-full bg-[#0A3B32] hover:bg-[#072A24] text-white font-bold py-3.5 rounded-xl text-sm shadow-md transition">
                    Create StraitsLedger Account
                </button>
            </form>
        </div>
    </div>

    <!-- Institutional 4-Column Footer -->
    <footer class="bg-white border-t border-[#E8E5DF] pt-16 pb-12 text-xs text-slate-600">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-10 pb-12 border-b border-slate-200">
                <!-- Col 1: Brand & Trust Badges -->
                <div class="md:col-span-2 pr-4">
                    <div class="flex items-center space-x-3 mb-4">
                        <svg width="34" height="34" viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <polygon points="60,6 106,33 106,87 60,114 14,87 14,33" fill="#0A3B32"/>
                            <polygon points="60,6 106,33 106,87 60,114 60,60" fill="#10B981" fill-opacity="0.85"/>
                            <polygon points="60,20 94,40 94,80 60,100 26,80 26,40" fill="#FFFFFF" stroke="#E8E5DF" stroke-width="1.5"/>
                            <path d="M 42 42 L 72 42 L 72 50 L 52 50 L 52 58 L 78 58 L 78 78 L 42 78 L 42 70 L 68 70 L 68 64 L 42 64 Z" fill="#0A3B32"/>
                        </svg>
                        <div>
                            <div class="font-display font-extrabold text-lg text-[#0A3B32] leading-none">STRAITS LEDGER</div>
                            <div class="text-[9px] font-semibold text-[#10B981] tracking-widest mt-0.5">SINGAPORE CLOUD ACCOUNTING</div>
                        </div>
                    </div>
                    <p class="text-slate-500 leading-relaxed mb-5 max-w-sm">
                        Singapore’s sovereign cloud accounting platform purpose-built for micro-SMEs, corporate service providers, and certified accounting practitioners.
                    </p>
                    <div class="flex flex-wrap gap-2">
                        <span class="inline-flex items-center text-[10px] font-semibold text-emerald-800 bg-emerald-50 px-2.5 py-1 rounded-md border border-emerald-200">
                            <i class="fa-solid fa-shield-halved text-emerald-600 mr-1.5"></i> IRAS 9% GST Ready
                        </span>
                        <span class="inline-flex items-center text-[10px] font-semibold text-purple-800 bg-purple-50 px-2.5 py-1 rounded-md border border-purple-200">
                            <i class="fa-solid fa-qrcode text-purple-600 mr-1.5"></i> PayNow SGQR
                        </span>
                        <span class="inline-flex items-center text-[10px] font-semibold text-slate-700 bg-slate-100 px-2.5 py-1 rounded-md border border-slate-200">
                            <i class="fa-solid fa-lock text-slate-600 mr-1.5"></i> 256-bit TLS SSL
                        </span>
                    </div>
                </div>

                <!-- Col 2: Product -->
                <div>
                    <h4 class="font-display font-bold text-xs uppercase tracking-wider text-[#0A3B32] mb-4">Core Platform</h4>
                    <ul class="space-y-2.5">
                        <li><a href="#features" class="hover:text-emerald-700 transition">IRAS 9% GST Form 5</a></li>
                        <li><a href="#features" class="hover:text-emerald-700 transition">PayNow SGQR Invoicing</a></li>
                        <li><a href="#comparison" class="hover:text-emerald-700 transition">Why StraitsLedger</a></li>
                        <li><a href="#migration" class="hover:text-emerald-700 transition">5-Minute Migration</a></li>
                        <li><a href="#features" class="hover:text-emerald-700 transition">InvoiceNow (Peppol)</a></li>
                        <li><a href="#features" class="hover:text-emerald-700 transition">Multi-Currency Ledger</a></li>
                    </ul>
                </div>

                <!-- Col 3: Straits Ecosystem -->
                <div>
                    <h4 class="font-display font-bold text-xs uppercase tracking-wider text-[#0A3B32] mb-4">Straits Suite</h4>
                    <ul class="space-y-2.5">
                        <li><a href="https://straits.thethinkthank.com" target="_blank" class="hover:text-emerald-700 transition">Straits Parent Portal</a></li>
                        <li><a href="/" class="text-emerald-700 font-semibold">StraitsLedger (Accounting)</a></li>
                        <li><a href="https://hr.thethinkthank.com" target="_blank" class="hover:text-emerald-700 transition">StraitsHR (Payroll &amp; CPF)</a></li>
                        <li><a href="#pricing" class="hover:text-emerald-700 transition">Accounting Firm Edition</a></li>
                        <li><a href="/auth/login" class="hover:text-emerald-700 transition">Client Login Portal</a></li>
                    </ul>
                </div>

                <!-- Col 4: Trust & Compliance -->
                <div>
                    <h4 class="font-display font-bold text-xs uppercase tracking-wider text-[#0A3B32] mb-4">Trust &amp; Legal</h4>
                    <ul class="space-y-2.5">
                        <li><a href="#faq" class="hover:text-emerald-700 transition">FAQ &amp; Knowledge Base</a></li>
                        <li><span class="text-slate-400">Singapore Data Residency</span></li>
                        <li><span class="text-slate-400">PDPA Compliant</span></li>
                        <li><span class="text-slate-400">MAS TRM Aligned</span></li>
                        <li><a href="#signup" onclick="openSignupModal()" class="text-emerald-700 font-semibold hover:underline">14-Day Free Trial</a></li>
                    </ul>
                </div>
            </div>

            <!-- Bottom Copyright Bar -->
            <div class="pt-8 flex flex-col sm:flex-row items-center justify-between text-slate-400 text-[11px] gap-4">
                <p>&copy; 2026 Straits &bull; A business unit of Think Thank Pte Ltd (UEN: 200415432K). All rights reserved. Singapore Cloud Infrastructure.</p>
                <div class="flex items-center space-x-6">
                    <a href="#comparison" class="hover:text-slate-600 transition">Why Us</a>
                    <a href="#migration" class="hover:text-slate-600 transition">Migration Guide</a>
                    <a href="#faq" class="hover:text-slate-600 transition">FAQ</a>
                    <a href="/auth/login" class="hover:text-slate-600 transition">Staff Sign In</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        function openSignupModal(plan = '') {
            document.getElementById('signup-modal').classList.remove('hidden');
        }
        function closeSignupModal() {
            document.getElementById('signup-modal').classList.add('hidden');
        }
    </script>
</body>
</html>
