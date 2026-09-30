<x-layouts.admin>
    <x-slot name="title">
        WaveApps Migration Wizard
    </x-slot>

    <x-slot name="content">
    <div class="my-6">

        {{-- ── Hero Header ── --}}
        <div class="mb-8 rounded-2xl p-6 text-white shadow-xl" style="background: linear-gradient(135deg, #92400e 0%, #b45309 60%, #d97706 100%);">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 border-b border-amber-500/40 pb-5">
                <div class="flex items-center space-x-3.5 rtl:space-x-reverse">
                    <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center border border-white/20">
                        <span class="material-icons-outlined text-3xl text-amber-200">swap_horiz</span>
                    </div>
                    <div>
                        <div class="flex items-center space-x-2 rtl:space-x-reverse">
                            <span class="px-2.5 py-0.5 text-xs font-semibold uppercase tracking-wider rounded-full bg-amber-400/20 text-amber-100 border border-amber-300/30">
                                Free Migration Tool
                            </span>
                            <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-white/20 text-white">
                                Singapore Ready
                            </span>
                        </div>
                        <h1 class="text-2xl font-bold tracking-tight text-white mt-1">
                            WaveApps → StraitsLedger Migration Wizard
                        </h1>
                        <p class="text-sm text-amber-100/80 mt-0.5">Move your customers, vendors, accounts & transactions in minutes.</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <a href="{{ route('customers.index') }}" class="inline-flex items-center bg-white/10 hover:bg-white/20 text-white text-xs font-semibold px-3 py-2 rounded-lg border border-white/20 transition">
                        <span class="material-icons-outlined text-sm ltr:mr-1 rtl:ml-1">groups</span>
                        View Contacts
                    </a>
                </div>
            </div>

            {{-- Step Progress --}}
            <div class="grid grid-cols-4 gap-2 pt-5">
                @foreach ([['1','upload','Upload CSV'], ['2','preview','Preview Data'], ['3','check_circle','Confirm Import'], ['4','celebration','Done']] as $step)
                <div class="flex flex-col items-center text-center {{ $loop->first ? 'opacity-100' : 'opacity-50' }}">
                    <div class="w-9 h-9 rounded-full {{ $loop->first ? 'bg-white text-amber-700' : 'bg-white/20 text-white' }} flex items-center justify-center font-bold text-sm mb-1">
                        <span class="material-icons-outlined text-lg">{{ $step[1] }}</span>
                    </div>
                    <span class="text-xs text-amber-100 font-medium hidden sm:block">{{ $step[2] }}</span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Alert Messages --}}
        @if(session('error'))
        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 flex items-start space-x-3">
            <span class="material-icons-outlined text-red-500 mt-0.5">error_outline</span>
            <div>
                <p class="text-sm font-semibold text-red-700">Upload Error</p>
                <p class="text-sm text-red-600 mt-0.5">{{ session('error') }}</p>
            </div>
        </div>
        @endif

        {{-- ── How It Works ── --}}
        <div class="mb-8 grid grid-cols-1 md:grid-cols-4 gap-4">
            @foreach([
                ['upload_file', 'Export from Wave', 'Go to WaveApps → Accounting → Export. Download CSV files for each data type.', 'amber'],
                ['upload', 'Upload Here', 'Select the CSV file below for the data type you want to migrate.', 'amber'],
                ['fact_check', 'Preview & Validate', 'Review the mapped data and any warnings before importing.', 'amber'],
                ['check_circle', 'Import & Go Live', 'Confirm the import — data lands instantly in StraitsLedger.', 'amber'],
            ] as $step)
            <div class="bg-white rounded-xl border border-amber-100 p-4 shadow-sm">
                <div class="w-10 h-10 rounded-lg bg-amber-50 flex items-center justify-center mb-3">
                    <span class="material-icons-outlined text-amber-600">{{ $step[0] }}</span>
                </div>
                <p class="text-sm font-semibold text-gray-800">{{ $step[1] }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ $step[2] }}</p>
            </div>
            @endforeach
        </div>

        {{-- ── Upload Cards (one per data type) ── --}}
        <h2 class="text-lg font-bold text-gray-800 mb-4">Choose a WaveApps Export File to Import</h2>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-10">
            @foreach($types as $typeKey => $info)
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm hover:shadow-md transition overflow-hidden">
                {{-- Card Header --}}
                <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                    <div class="flex items-center space-x-3 rtl:space-x-reverse">
                        <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center">
                            <span class="material-icons-outlined text-amber-600 text-xl">{{ $info['icon'] }}</span>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-800">{{ $info['label'] }}</p>
                            <p class="text-xs text-gray-500">{{ $info['description'] }}</p>
                        </div>
                    </div>
                    <a href="{{ $info['sample'] }}" class="inline-flex items-center text-xs text-amber-600 hover:text-amber-700 font-medium whitespace-nowrap" title="Download sample CSV">
                        <span class="material-icons-outlined text-sm ltr:mr-0.5 rtl:ml-0.5">download</span>
                        Sample
                    </a>
                </div>

                {{-- Upload Form --}}
                <form action="{{ route('wave-migration.upload') }}" method="POST" enctype="multipart/form-data" class="p-5">
                    @csrf
                    <div class="relative border-2 border-dashed border-gray-200 rounded-xl p-6 text-center hover:border-amber-400 transition-colors cursor-pointer"
                         onclick="document.getElementById('csv_{{ $typeKey }}').click()">
                        <span class="material-icons-outlined text-3xl text-gray-300 mb-2">cloud_upload</span>
                        <p class="text-sm font-medium text-gray-600">Drop your <strong>{{ $info['label'] }}</strong> CSV here</p>
                        <p class="text-xs text-gray-400 mt-1">or click to browse · Max 10 MB</p>
                        <input type="file" name="csv_file" id="csv_{{ $typeKey }}" accept=".csv,.txt"
                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                               onchange="this.closest('form').querySelector('.file-name').textContent = this.files[0]?.name || ''; this.closest('form').querySelector('.upload-btn').disabled = !this.files[0];">
                        <p class="file-name text-xs text-amber-600 mt-2 font-medium"></p>
                    </div>
                    <button type="submit" class="upload-btn mt-3 w-full py-2 rounded-xl text-sm font-semibold text-white transition disabled:opacity-40 disabled:cursor-not-allowed"
                            style="background: linear-gradient(135deg, #b45309, #d97706);" disabled>
                        <span class="material-icons-outlined text-sm align-middle ltr:mr-1 rtl:ml-1">upload</span>
                        Upload & Preview {{ $info['label'] }}
                    </button>
                </form>
            </div>
            @endforeach
        </div>

        {{-- ── SEO: Why Switch from Wave ── --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm mb-8" x-data="{ open: false }">
            <button @click="open = !open"
                    class="w-full flex items-center justify-between px-6 py-5 text-left hover:bg-gray-50 rounded-2xl transition">
                <div class="flex items-center space-x-3">
                    <span class="material-icons-outlined text-amber-600">lightbulb</span>
                    <div>
                        <p class="text-base font-bold text-gray-800">Why Switch from WaveApps to StraitsLedger?</p>
                        <p class="text-xs text-gray-500">Singapore-specific advantages for SMEs & freelancers</p>
                    </div>
                </div>
                <span class="material-icons-outlined text-gray-400" x-text="open ? 'expand_less' : 'expand_more'">expand_more</span>
            </button>

            <div x-show="open" x-transition class="px-6 pb-6" style="display:none;">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-2">

                    <div class="space-y-4">
                        <div class="flex items-start space-x-3">
                            <span class="material-icons-outlined text-green-500 mt-0.5">verified</span>
                            <div>
                                <p class="text-sm font-semibold text-gray-800">Singapore IRAS GST Form 5 — Built In</p>
                                <p class="text-xs text-gray-500 mt-0.5">StraitsLedger automatically calculates all 13 GST boxes (Box 1–13) to IRAS standards. No manual exports, no spreadsheets — submit-ready in one click.</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <span class="material-icons-outlined text-green-500 mt-0.5">qr_code_2</span>
                            <div>
                                <p class="text-sm font-semibold text-gray-800">PayNow QR on Every Invoice</p>
                                <p class="text-xs text-gray-500 mt-0.5">Every invoice you send includes a scannable PayNow QR code linked to your UEN or mobile number — your Singapore clients pay instantly.</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <span class="material-icons-outlined text-green-500 mt-0.5">account_balance</span>
                            <div>
                                <p class="text-sm font-semibold text-gray-800">DBS, OCBC & UOB Statement Reconciliation</p>
                                <p class="text-xs text-gray-500 mt-0.5">Upload your local bank statement CSV and StraitsLedger auto-matches transactions with PayNow/UEN detection, CPF recognition, and IRAS tax payments.</p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-start space-x-3">
                            <span class="material-icons-outlined text-green-500 mt-0.5">lock</span>
                            <div>
                                <p class="text-sm font-semibold text-gray-800">Your Data, Your Server</p>
                                <p class="text-xs text-gray-500 mt-0.5">WaveApps is US-based. StraitsLedger keeps your financial data within Singapore-compliant infrastructure — important for PDPA and MAS-aligned data residency.</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <span class="material-icons-outlined text-green-500 mt-0.5">attach_money</span>
                            <div>
                                <p class="text-sm font-semibold text-gray-800">No Hidden Subscription Fees</p>
                                <p class="text-xs text-gray-500 mt-0.5">WaveApps now charges monthly for payroll, payments, and premium features. StraitsLedger's pricing is transparent and SME-friendly with no surprise add-ons.</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <span class="material-icons-outlined text-green-500 mt-0.5">support_agent</span>
                            <div>
                                <p class="text-sm font-semibold text-gray-800">Singapore-Based Support</p>
                                <p class="text-xs text-gray-500 mt-0.5">Get help from a team that understands local tax codes, CPF treatment, IRAS filing, and Singapore accounting standards — not a generic North American support desk.</p>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="mt-5 p-4 bg-amber-50 rounded-xl border border-amber-200">
                    <p class="text-xs text-amber-800 font-medium">
                        <span class="material-icons-outlined text-sm align-middle mr-1">info</span>
                        <strong>Migration is completely free.</strong> All your WaveApps data (customers, vendors, transactions, invoices) migrates over in minutes using this wizard. No manual data entry required.
                    </p>
                </div>
            </div>
        </div>

    </div>
    </x-slot>
</x-layouts.admin>
