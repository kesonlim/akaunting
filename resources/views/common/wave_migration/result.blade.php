<x-layouts.admin>
    <x-slot name="title">
        Migration Result — {{ $typeLabel }}
    </x-slot>

    <x-slot name="content">
    <div class="my-6">

        {{-- ── Hero Header ── --}}
        <div class="mb-8 rounded-2xl p-6 text-white shadow-xl" style="background: linear-gradient(135deg, #065f46 0%, #047857 60%, #059669 100%);">
            <div class="flex items-center justify-between border-b border-emerald-500/40 pb-4">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center border border-white/20">
                        <span class="material-icons-outlined text-3xl text-emerald-200">celebration</span>
                    </div>
                    <div>
                        <p class="text-xs text-emerald-200 font-semibold uppercase tracking-wider">Step 4 of 4</p>
                        <h1 class="text-2xl font-bold text-white">Migration Complete!</h1>
                        <p class="text-xs text-emerald-100 mt-0.5">Your WaveApps {{ strtolower($typeLabel) }} have been imported into StraitsLedger.</p>
                    </div>
                </div>
                <a href="{{ route('wave-migration.index') }}" class="inline-flex items-center bg-white/10 hover:bg-white/20 text-white text-xs font-semibold px-3 py-2 rounded-lg border border-white/20 transition">
                    <span class="material-icons-outlined text-sm ltr:mr-1 rtl:ml-1">upload_file</span>
                    Import Another File
                </a>
            </div>

            {{-- Step Progress --}}
            <div class="grid grid-cols-4 gap-2 pt-4">
                @foreach ([['1','check','Upload CSV'], ['2','check','Preview Data'], ['3','check','Confirm Import'], ['4','celebration','Done']] as $step)
                <div class="flex flex-col items-center text-center opacity-100">
                    <div class="w-9 h-9 rounded-full bg-white text-emerald-700 flex items-center justify-center font-bold text-sm mb-1 shadow-sm">
                        <span class="material-icons-outlined text-lg">{{ $step[1] }}</span>
                    </div>
                    <span class="text-xs text-emerald-100 font-medium hidden sm:block">{{ $step[2] }}</span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- ── Summary Card ── --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 flex items-center space-x-4">
                <div class="w-14 h-14 rounded-2xl bg-emerald-50 flex items-center justify-center">
                    <span class="material-icons-outlined text-emerald-600 text-3xl">check_circle</span>
                </div>
                <div>
                    <p class="text-3xl font-extrabold text-emerald-600">{{ $imported }}</p>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Successfully Imported</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 flex items-center space-x-4">
                <div class="w-14 h-14 rounded-2xl bg-amber-50 flex items-center justify-center">
                    <span class="material-icons-outlined text-amber-600 text-3xl">content_copy</span>
                </div>
                <div>
                    <p class="text-3xl font-extrabold text-amber-600">{{ $skipped }}</p>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Duplicates Skipped</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 flex items-center space-x-4">
                <div class="w-14 h-14 rounded-2xl bg-{{ count($errors) > 0 ? 'red' : 'gray' }}-50 flex items-center justify-center">
                    <span class="material-icons-outlined text-{{ count($errors) > 0 ? 'red' : 'gray' }}-500 text-3xl">
                        {{ count($errors) > 0 ? 'error' : 'task_alt' }}
                    </span>
                </div>
                <div>
                    <p class="text-3xl font-extrabold text-{{ count($errors) > 0 ? 'red-600' : 'gray-700' }}">{{ count($errors) }}</p>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Errors Encountered</p>
                </div>
            </div>
        </div>

        {{-- ── Errors List if any ── --}}
        @if(count($errors) > 0)
        <div class="mb-8 bg-red-50 border border-red-200 rounded-2xl p-6">
            <div class="flex items-center space-x-2 mb-3">
                <span class="material-icons-outlined text-red-600">error_outline</span>
                <h3 class="text-sm font-bold text-red-800">Errors encountered during ingestion:</h3>
            </div>
            <ul class="space-y-1.5 pl-6 list-disc text-xs text-red-700">
                @foreach($errors as $err)
                <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- ── Navigation & Next Steps ── --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 mb-8">
            <h2 class="text-base font-bold text-gray-800 mb-4">Recommended Next Steps</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @if($type === 'customers')
                <a href="{{ route('customers.index') }}" class="flex items-center space-x-3 p-4 rounded-xl border border-gray-100 hover:border-emerald-300 hover:bg-emerald-50/30 transition">
                    <div class="w-10 h-10 rounded-lg bg-emerald-100 flex items-center justify-center">
                        <span class="material-icons-outlined text-emerald-700">groups</span>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-800">Review Customers</p>
                        <p class="text-xs text-gray-500">View newly imported client accounts</p>
                    </div>
                </a>
                @elseif($type === 'vendors')
                <a href="{{ route('vendors.index') }}" class="flex items-center space-x-3 p-4 rounded-xl border border-gray-100 hover:border-emerald-300 hover:bg-emerald-50/30 transition">
                    <div class="w-10 h-10 rounded-lg bg-emerald-100 flex items-center justify-center">
                        <span class="material-icons-outlined text-emerald-700">local_shipping</span>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-800">Review Vendors</p>
                        <p class="text-xs text-gray-500">Check imported supplier profiles</p>
                    </div>
                </a>
                @elseif($type === 'chart_of_accounts')
                <a href="{{ route('categories.index') }}" class="flex items-center space-x-3 p-4 rounded-xl border border-gray-100 hover:border-emerald-300 hover:bg-emerald-50/30 transition">
                    <div class="w-10 h-10 rounded-lg bg-emerald-100 flex items-center justify-center">
                        <span class="material-icons-outlined text-emerald-700">category</span>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-800">Review Categories</p>
                        <p class="text-xs text-gray-500">Check imported accounts/categories</p>
                    </div>
                </a>
                @else
                <a href="{{ route('transactions.index') }}" class="flex items-center space-x-3 p-4 rounded-xl border border-gray-100 hover:border-emerald-300 hover:bg-emerald-50/30 transition">
                    <div class="w-10 h-10 rounded-lg bg-emerald-100 flex items-center justify-center">
                        <span class="material-icons-outlined text-emerald-700">receipt_long</span>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-800">Review Transactions</p>
                        <p class="text-xs text-gray-500">View imported ledger entries</p>
                    </div>
                </a>
                @endif

                <a href="{{ route('singapore-reconciler.index') }}" class="flex items-center space-x-3 p-4 rounded-xl border border-gray-100 hover:border-emerald-300 hover:bg-emerald-50/30 transition">
                    <div class="w-10 h-10 rounded-lg bg-emerald-100 flex items-center justify-center">
                        <span class="material-icons-outlined text-emerald-700">account_balance</span>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-800">Bank Reconciler</p>
                        <p class="text-xs text-gray-500">Auto-match DBS / OCBC / UOB feeds</p>
                    </div>
                </a>

                <a href="{{ route('wave-migration.index') }}" class="flex items-center space-x-3 p-4 rounded-xl border border-gray-100 hover:border-emerald-300 hover:bg-emerald-50/30 transition">
                    <div class="w-10 h-10 rounded-lg bg-amber-100 flex items-center justify-center">
                        <span class="material-icons-outlined text-amber-700">swap_horiz</span>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-800">Import Another File</p>
                        <p class="text-xs text-gray-500">Migrate invoices, vendors or accounts</p>
                    </div>
                </a>
            </div>
        </div>

    </div>
    </x-slot>
</x-layouts.admin>
