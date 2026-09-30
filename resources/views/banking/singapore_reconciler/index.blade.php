@extends('layouts.admin')

@section('title', 'Singapore Bank Statement Reconciler & Transaction Matcher')

@section('content')
    <div class="my-6">
        {{-- Hero Header Card --}}
        <div class="mb-8 rounded-2xl p-6 text-white shadow-xl" style="background: linear-gradient(135deg, #0A3B32 0%, #0f5145 100%); color: #ffffff;">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 border-b border-emerald-600/40 pb-5">
                <div class="flex items-center space-x-3.5 rtl:space-x-reverse">
                    <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center border border-white/20">
                        <span class="material-icons-outlined text-3xl text-emerald-300">account_balance</span>
                    </div>
                    <div>
                        <div class="flex items-center space-x-2 rtl:space-x-reverse">
                            <span class="px-2.5 py-0.5 text-xs font-semibold uppercase tracking-wider rounded-full bg-emerald-400/20 text-emerald-200 border border-emerald-300/30">
                                Singapore Banking Suite
                            </span>
                            <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-white/20 text-white">
                                Multi-Bank Feed & Matcher
                            </span>
                        </div>
                        <h1 class="text-2xl font-bold tracking-tight text-white mt-1">
                            Singapore Bank Statement Reconciler
                        </h1>
                    </div>
                </div>

                <div class="flex items-center space-x-2 rtl:space-x-reverse">
                    <a href="{{ route('reconciliations.index') }}" class="inline-flex items-center bg-white/10 hover:bg-white/20 text-white text-xs font-semibold px-3 py-2 rounded-lg border border-white/20">
                        <span class="material-icons-outlined text-sm ltr:mr-1 rtl:ml-1">history</span>
                        Standard Reconciliations
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-5 text-sm">
                <div>
                    <div class="text-xs font-semibold uppercase tracking-wider" style="color: #6ee7b7;">Target Bank Account</div>
                    <div class="font-bold text-white text-base mt-0.5">{{ $selectedAccount->name ?? 'Default Operating Account' }}</div>
                    <div class="text-xs text-emerald-200/80">{{ $selectedAccount->number ?? '003-902-841-2' }} ({{ $selectedAccount->currency_code ?? 'SGD' }})</div>
                </div>
                <div>
                    <div class="text-xs font-semibold uppercase tracking-wider" style="color: #6ee7b7;">Supported Singapore Formats</div>
                    <div class="font-bold text-white text-base mt-0.5">DBS IDEAL • OCBC Velocity • UOB Infinity</div>
                    <div class="text-xs text-emerald-200/80">Also supports Aspire, Airwallex, Wise & Standard CSV</div>
                </div>
                <div>
                    <div class="text-xs font-semibold uppercase tracking-wider" style="color: #6ee7b7;">Smart Matching Logic</div>
                    <div class="font-bold text-white text-base mt-0.5">PayNow UEN & Invoice Number Auto-Link</div>
                    <div class="text-xs text-emerald-200/80">Auto-detects CPF, IRAS, Telco & Bank service charges</div>
                </div>
            </div>
        </div>

        {{-- Upload & Import Form Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-8">
            <h2 class="text-lg font-bold text-gray-900 mb-1 flex items-center">
                <span class="material-icons-outlined text-emerald-600 mr-2">upload_file</span>
                Import & Reconcile Bank Statement
            </h2>
            <p class="text-sm text-gray-500 mb-6">
                Upload your downloaded statement from your Singapore bank's corporate banking portal (CSV or text format). StraitsLedger will automatically parse transaction lines and identify matching invoices and expenses.
            </p>

            <form action="{{ route('singapore-reconciler.parse') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Select Company Bank Account</label>
                        <select name="account_id" class="w-full rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 text-sm shadow-sm py-2.5 px-3">
                            @foreach ($accounts as $acc)
                                <option value="{{ $acc->id }}" {{ $acc->id == $selectedAccount->id ? 'selected' : '' }}>
                                    {{ $acc->name }} ({{ $acc->number }}) - {{ $acc->currency_code }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Bank Statement Format</label>
                        <select name="format" class="w-full rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 text-sm shadow-sm py-2.5 px-3">
                            <option value="auto">Auto-Detect Format (Recommended)</option>
                            <option value="dbs">DBS / POSB IDEAL Corporate CSV</option>
                            <option value="ocbc">OCBC Velocity Statement CSV</option>
                            <option value="uob">UOB Infinity / BIBPlus CSV</option>
                            <option value="generic">Generic Singapore Bank / Neobank CSV</option>
                        </select>
                    </div>
                </div>

                {{-- Drag and Drop Upload Zone --}}
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Upload Statement File (.csv, .txt, .ofx)</label>
                    <div class="border-2 border-dashed border-gray-300 hover:border-emerald-500 rounded-2xl p-8 text-center bg-gray-50/50 hover:bg-emerald-50/30 transition-colors cursor-pointer" onclick="document.getElementById('statement_file_input').click()">
                        <input type="file" id="statement_file_input" name="statement_file" class="hidden" accept=".csv,.txt,.ofx" onchange="updateFileName(this)">
                        <div class="w-12 h-12 mx-auto rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mb-3">
                            <span class="material-icons-outlined text-2xl">cloud_upload</span>
                        </div>
                        <div class="text-sm font-semibold text-gray-800" id="file_name_display">Click to select bank statement file or drag and drop here</div>
                        <div class="text-xs text-gray-500 mt-1">Exported directly from DBS IDEAL, OCBC Velocity, UOB Infinity, Aspire, or Wise</div>
                    </div>
                </div>

                {{-- Alternative Paste Text Box --}}
                <details class="mb-6 group">
                    <summary class="text-xs font-semibold text-emerald-700 hover:text-emerald-800 cursor-pointer list-none flex items-center space-x-1">
                        <span class="material-icons-outlined text-sm">content_paste</span>
                        <span>Or click here to paste raw CSV text directly</span>
                    </summary>
                    <div class="mt-3">
                        <textarea name="statement_text" rows="4" class="w-full rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 text-xs font-mono p-3" placeholder="Paste raw CSV rows from your bank portal here..."></textarea>
                    </div>
                </details>

                <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                    <div class="flex items-center space-x-2 text-xs text-gray-500">
                        <span class="material-icons-outlined text-sm text-emerald-600">verified_user</span>
                        <span>Bank data is processed securely within your tenant environment.</span>
                    </div>

                    <button type="submit" class="inline-flex items-center px-6 py-2.5 rounded-xl font-semibold text-sm text-white shadow-sm transition-all" style="background: linear-gradient(135deg, #0A3B32 0%, #10B981 100%);">
                        <span class="material-icons-outlined text-base mr-1.5">auto_awesome</span>
                        Parse & Run Smart Match
                    </button>
                </div>
            </form>
        </div>

        {{-- 1-Click Interactive Demo & Sample Presets --}}
        <div class="bg-gradient-to-r from-emerald-50 to-teal-50 rounded-2xl border border-emerald-200/80 p-6 mb-8">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mb-4">
                <div>
                    <h3 class="text-base font-bold text-emerald-950 flex items-center">
                        <span class="material-icons-outlined text-emerald-700 mr-2">bolt</span>
                        Instant Singapore Bank Feed Simulator (1-Click Test)
                    </h3>
                    <p class="text-xs text-emerald-800 mt-0.5">
                        Test the reconciler immediately using official statement feeds containing realistic PayNow incoming invoices, CPF payments, StarHub telecommunications, and bank charges.
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                {{-- DBS Preset --}}
                <form action="{{ route('singapore-reconciler.parse') }}" method="POST" class="bg-white p-4 rounded-xl border border-emerald-200 shadow-sm hover:border-emerald-400 transition-all flex flex-col justify-between">
                    @csrf
                    <input type="hidden" name="account_id" value="{{ $selectedAccount->id }}">
                    <input type="hidden" name="sample_type" value="dbs">
                    <input type="hidden" name="format" value="dbs">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="px-2 py-0.5 text-xs font-bold rounded bg-red-100 text-red-700">DBS IDEAL</span>
                            <span class="text-xs text-gray-400 font-mono">.CSV</span>
                        </div>
                        <div class="text-sm font-bold text-gray-900">DBS Corporate Statement</div>
                        <div class="text-xs text-gray-500 mt-1">FAST PayNow receipt (S$1,850.00), CPF Board Giro (S$950.00), StarHub & DBS monthly fee.</div>
                    </div>
                    <button type="submit" class="mt-4 w-full py-2 px-3 rounded-lg text-xs font-semibold text-emerald-800 bg-emerald-100 hover:bg-emerald-200 flex items-center justify-center">
                        <span class="material-icons-outlined text-sm mr-1">play_arrow</span>
                        Test DBS IDEAL Feed
                    </button>
                </form>

                {{-- OCBC Preset --}}
                <form action="{{ route('singapore-reconciler.parse') }}" method="POST" class="bg-white p-4 rounded-xl border border-emerald-200 shadow-sm hover:border-emerald-400 transition-all flex flex-col justify-between">
                    @csrf
                    <input type="hidden" name="account_id" value="{{ $selectedAccount->id }}">
                    <input type="hidden" name="sample_type" value="ocbc">
                    <input type="hidden" name="format" value="ocbc">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="px-2 py-0.5 text-xs font-bold rounded bg-red-100 text-red-800">OCBC Velocity</span>
                            <span class="text-xs text-gray-400 font-mono">.CSV</span>
                        </div>
                        <div class="text-sm font-bold text-gray-900">OCBC Business Statement</div>
                        <div class="text-xs text-gray-500 mt-1">PayNow UEN deposit, Singtel broadband, employee CPF GIRO, and Velocity account fee.</div>
                    </div>
                    <button type="submit" class="mt-4 w-full py-2 px-3 rounded-lg text-xs font-semibold text-emerald-800 bg-emerald-100 hover:bg-emerald-200 flex items-center justify-center">
                        <span class="material-icons-outlined text-sm mr-1">play_arrow</span>
                        Test OCBC Velocity Feed
                    </button>
                </form>

                {{-- UOB Preset --}}
                <form action="{{ route('singapore-reconciler.parse') }}" method="POST" class="bg-white p-4 rounded-xl border border-emerald-200 shadow-sm hover:border-emerald-400 transition-all flex flex-col justify-between">
                    @csrf
                    <input type="hidden" name="account_id" value="{{ $selectedAccount->id }}">
                    <input type="hidden" name="sample_type" value="uob">
                    <input type="hidden" name="format" value="uob">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="px-2 py-0.5 text-xs font-bold rounded bg-blue-100 text-blue-800">UOB Infinity</span>
                            <span class="text-xs text-gray-400 font-mono">.CSV</span>
                        </div>
                        <div class="text-sm font-bold text-gray-900">UOB Infinity (BIBPlus)</div>
                        <div class="text-xs text-gray-500 mt-1">PayNow UEN incoming wire, CPF Board GIRO, M1 Fibre Telecom, and monthly maintenance.</div>
                    </div>
                    <button type="submit" class="mt-4 w-full py-2 px-3 rounded-lg text-xs font-semibold text-emerald-800 bg-emerald-100 hover:bg-emerald-200 flex items-center justify-center">
                        <span class="material-icons-outlined text-sm mr-1">play_arrow</span>
                        Test UOB Infinity Feed
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function updateFileName(input) {
            if (input.files && input.files[0]) {
                document.getElementById('file_name_display').innerText = 'Selected: ' + input.files[0].name + ' (' + (input.files[0].size / 1024).toFixed(1) + ' KB)';
            }
        }
    </script>
@endsection
