<x-layouts.admin>
    <x-slot name="title">
        Preview Migration Data — {{ $typeLabel }}
    </x-slot>

    <x-slot name="content">
    <div class="my-6">

        {{-- ── Hero Header ── --}}
        <div class="mb-8 rounded-2xl p-6 text-white shadow-xl" style="background: linear-gradient(135deg, #92400e 0%, #b45309 60%, #d97706 100%);">
            <div class="flex items-center justify-between border-b border-amber-500/40 pb-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center border border-white/20">
                        <span class="material-icons-outlined text-2xl text-amber-200">fact_check</span>
                    </div>
                    <div>
                        <p class="text-xs text-amber-200 font-semibold uppercase tracking-wider">Step 2 of 4</p>
                        <h1 class="text-xl font-bold text-white">Preview: {{ $typeLabel }}</h1>
                    </div>
                </div>
                <a href="{{ route('wave-migration.index') }}" class="inline-flex items-center bg-white/10 hover:bg-white/20 text-white text-xs font-semibold px-3 py-2 rounded-lg border border-white/20 transition">
                    <span class="material-icons-outlined text-sm ltr:mr-1 rtl:ml-1">arrow_back</span>
                    Back to Upload
                </a>
            </div>

            {{-- Step Progress --}}
            <div class="grid grid-cols-4 gap-2 pt-4">
                @foreach ([['1','check','Upload CSV'], ['2','fact_check','Preview Data'], ['3','check_circle','Confirm Import'], ['4','celebration','Done']] as $idx => $step)
                <div class="flex flex-col items-center text-center {{ $idx <= 1 ? 'opacity-100' : 'opacity-50' }}">
                    <div class="w-9 h-9 rounded-full {{ $idx <= 1 ? 'bg-white text-amber-700' : 'bg-white/20 text-white' }} flex items-center justify-center font-bold text-sm mb-1">
                        <span class="material-icons-outlined text-lg">{{ $step[1] }}</span>
                    </div>
                    <span class="text-xs text-amber-100 font-medium hidden sm:block">{{ $step[2] }}</span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- ── Summary Stats ── --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 flex items-center space-x-4">
                <div class="w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center">
                    <span class="material-icons-outlined text-green-600 text-2xl">table_rows</span>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800">{{ $count }}</p>
                    <p class="text-xs text-gray-500">Rows Ready to Import</p>
                </div>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 flex items-center space-x-4">
                <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center">
                    <span class="material-icons-outlined text-amber-600 text-2xl">warning_amber</span>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800">{{ count($warnings) }}</p>
                    <p class="text-xs text-gray-500">Warnings (rows may be skipped)</p>
                </div>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 flex items-center space-x-4">
                <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center">
                    <span class="material-icons-outlined text-blue-600 text-2xl">timer</span>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800">&lt; 10s</p>
                    <p class="text-xs text-gray-500">Estimated Import Time</p>
                </div>
            </div>
        </div>

        {{-- ── Warnings ── --}}
        @if(count($warnings) > 0)
        <div class="mb-6 bg-amber-50 border border-amber-200 rounded-xl p-5">
            <div class="flex items-center space-x-2 mb-3">
                <span class="material-icons-outlined text-amber-600">warning_amber</span>
                <p class="text-sm font-bold text-amber-800">{{ count($warnings) }} Warning(s)</p>
            </div>
            <ul class="space-y-1">
                @foreach($warnings as $w)
                <li class="text-xs text-amber-700 flex items-start space-x-2">
                    <span class="material-icons-outlined text-xs mt-0.5">chevron_right</span>
                    <span>{{ $w }}</span>
                </li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- ── Data Preview Table ── --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm mb-8 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <p class="text-sm font-bold text-gray-800">Data Preview</p>
                    <p class="text-xs text-gray-500">Showing first {{ min(5, $count) }} of {{ $count }} rows</p>
                </div>
                <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-700 border border-amber-200">
                    {{ $typeLabel }}
                </span>
            </div>

            @if(count($preview) > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            @foreach(array_keys($preview[0]) as $col)
                            <th class="px-4 py-3 text-left font-semibold text-gray-600 whitespace-nowrap">
                                {{ ucfirst(str_replace('_', ' ', $col)) }}
                            </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($preview as $row)
                        <tr class="hover:bg-gray-50/50">
                            @foreach($row as $val)
                            <td class="px-4 py-3 text-gray-700 whitespace-nowrap max-w-[200px] overflow-hidden text-ellipsis">
                                {{ $val ?: '—' }}
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="p-8 text-center text-gray-400">
                <span class="material-icons-outlined text-4xl mb-2">inbox</span>
                <p class="text-sm">No rows to preview</p>
            </div>
            @endif
        </div>

        {{-- ── Action Buttons ── --}}
        <div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-4 p-4 bg-white rounded-2xl border border-gray-200 shadow-sm">
            <a href="{{ route('wave-migration.index') }}"
               class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-3 rounded-xl border border-gray-200 bg-gray-50 text-sm font-semibold text-gray-700 hover:bg-gray-100 transition">
                <span class="material-icons-outlined text-sm ltr:mr-2 rtl:ml-2">arrow_back</span>
                Back — Upload Different File
            </a>

            <form action="{{ route('wave-migration.confirm') }}" method="POST" class="w-full sm:w-auto">
                @csrf
                <button type="submit"
                        class="w-full sm:w-auto px-8 py-3 rounded-xl text-sm font-bold text-white shadow-md hover:shadow-lg transition flex items-center justify-center cursor-pointer"
                        style="background: linear-gradient(135deg, #b45309 0%, #d97706 100%);">
                    <span class="material-icons-outlined text-base ltr:mr-2 rtl:ml-2">check_circle</span>
                    Confirm & Import {{ $count }} {{ $typeLabel }} into StraitsLedger
                </button>
            </form>
        </div>

        <p class="text-xs text-gray-400 text-center mt-4">
            <span class="material-icons-outlined text-xs align-middle mr-1">info</span>
            Duplicate records (same name or email) will be automatically skipped to prevent double-importing.
        </p>

    </div>
    </x-slot>
</x-layouts.admin>
