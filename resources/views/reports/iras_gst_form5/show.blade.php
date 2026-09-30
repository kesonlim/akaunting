<x-layouts.admin>
    <x-slot name="title">
        {{ $class->model->name ?? 'IRAS GST Form 5' }}
    </x-slot>

    <x-slot name="favorite"
        title="{{ $class->model->name ?? 'IRAS GST Form 5' }}"
        icon="{{ $class->icon }}"
        :route="['reports.show', $class->model->id]"
    ></x-slot>

    <x-slot name="buttons">
        <x-link href="{{ url($class->getUrl('print')) }}" target="_blank" class="inline-flex items-center bg-gray-100 hover:bg-gray-200 text-gray-800 text-sm font-medium px-4 py-2 rounded-lg ltr:mr-2 rtl:ml-2">
            <span class="material-icons-outlined text-base ltr:mr-1 rtl:ml-1">print</span>
            {{ trans('general.print') }}
        </x-link>

        <x-link href="{{ url($class->getUrl('export')) }}" class="inline-flex items-center bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium px-4 py-2 rounded-lg">
            <span class="material-icons-outlined text-base ltr:mr-1 rtl:ml-1">file_download</span>
            {{ trans('general.export') }}
        </x-link>
    </x-slot>

    <x-slot name="moreButtons">
        <x-dropdown id="dropdown-more-actions">
            <x-slot name="trigger">
                <span class="material-icons pointer-events-none">more_horiz</span>
            </x-slot>

            <x-dropdown.link href="{{ url($class->getUrl('pdf')) }}" id="show-more-actions-pdf-report">
                {{ trans('general.download_pdf') }}
            </x-dropdown.link>

            <x-dropdown.divider />

            @can('update-common-reports')
                <x-dropdown.link href="{{ url($class->getUrl('edit')) }}" id="index-more-actions-edit-report">
                    {{ trans('general.edit') }}
                </x-dropdown.link>
            @endcan

            @can('delete-common-reports')
                <x-delete-link :model="$class->model" route="reports.destroy" />
            @endcan
        </x-dropdown>
    </x-slot>

    <x-slot name="content">
        <div class="my-6">
            <x-loading.content />

            {{-- Standard Period / Year / Basis Filters --}}
            @include($class->views['filter'])

            {{-- Official IRAS Header Card --}}
            <div class="mt-6 mb-8 rounded-2xl bg-gradient-to-r from-[#0A3B32] to-[#0f5145] p-6 text-white shadow-xl">
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 border-b border-emerald-700/60 pb-5">
                    <div class="flex items-center space-x-3.5 rtl:space-x-reverse">
                        <div class="w-12 h-12 rounded-xl bg-emerald-400/20 flex items-center justify-center border border-emerald-400/30">
                            <span class="material-icons-outlined text-3xl text-emerald-300">account_balance</span>
                        </div>
                        <div>
                            <div class="flex items-center space-x-2 rtl:space-x-reverse">
                                <span class="px-2.5 py-0.5 text-xs font-semibold uppercase tracking-wider rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-400/30">
                                    Singapore Statutory Return
                                </span>
                                <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-white/10 text-white">
                                    IRAS GST Form 5
                                </span>
                            </div>
                            <h1 class="text-2xl font-bold tracking-tight text-white mt-1">
                                GST Return for Prescribed Accounting Period
                            </h1>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3 rtl:space-x-reverse">
                        <div class="text-right rtl:text-left">
                            <div class="text-xs text-emerald-200">Statutory GST Rate</div>
                            <div class="text-lg font-bold text-white">9.0% (Current)</div>
                        </div>
                    </div>
                </div>

                {{-- Taxpayer Metadata Grid --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 pt-5 text-sm">
                    <div>
                        <div class="text-xs text-emerald-300/80 font-medium">Taxable Person / Entity</div>
                        <div class="font-bold text-white mt-0.5">{{ $class->company_name }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-emerald-300/80 font-medium">Singapore UEN / GST Reg No.</div>
                        <div class="font-bold text-white mt-0.5">{{ $class->company_uen }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-emerald-300/80 font-medium">Accounting Basis</div>
                        <div class="font-bold text-white mt-0.5 capitalize">{{ $class->getBasis() }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-emerald-300/80 font-medium">Statutory Filing Deadline</div>
                        <div class="font-bold text-emerald-200 mt-0.5">{{ $class->filing_due_date }}</div>
                    </div>
                </div>
            </div>

            {{-- Net GST Position Highlight Hero Banner --}}
            <div class="mb-8 rounded-2xl p-6 border shadow-sm {{ $class->box_8 >= 0 ? 'bg-emerald-50/70 border-emerald-200' : 'bg-blue-50/70 border-blue-200' }}">
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                    <div class="flex items-center space-x-4 rtl:space-x-reverse">
                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center {{ $class->box_8 >= 0 ? 'bg-emerald-600 text-white' : 'bg-blue-600 text-white' }}">
                            <span class="material-icons-outlined text-3xl">
                                {{ $class->box_8 >= 0 ? 'payments' : 'savings' }}
                            </span>
                        </div>
                        <div>
                            <div class="text-xs uppercase tracking-wider font-semibold {{ $class->box_8 >= 0 ? 'text-emerald-800' : 'text-blue-800' }}">
                                Box 8 • Net GST Position
                            </div>
                            <div class="text-xl md:text-2xl font-black text-gray-900 mt-0.5">
                                {{ $class->box_8 >= 0 ? 'Net GST to be Paid to IRAS' : 'Net GST to be Refunded by IRAS' }}
                            </div>
                            <div class="text-xs text-gray-500 mt-0.5">
                                Output Tax (Box 5: S${{ number_format($class->box_5, 2) }}) minus Input Tax Claimed (Box 7: S${{ number_format($class->box_7, 2) }})
                            </div>
                        </div>
                    </div>

                    <div class="text-left md:text-right">
                        <div class="text-3xl md:text-4xl font-black {{ $class->box_8 >= 0 ? 'text-emerald-700' : 'text-blue-700' }}">
                            S${{ number_format(abs($class->box_8), 2) }}
                        </div>
                        <div class="text-xs font-medium text-gray-600 mt-1">
                            {{ $class->box_8 >= 0 ? 'Payable via GIRO, PayNow Corporate or Internet Banking' : 'Will be directly credited to your registered bank account' }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Official Singapore IRAS GST Form 5 Breakdown Cards --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">

                {{-- Supplies & Output Tax (Parts 1 & 2) --}}
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="bg-gray-50/80 px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                        <div class="flex items-center space-x-2.5 rtl:space-x-reverse">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                            <h2 class="text-base font-bold text-gray-900">Supplies & Output Tax Due</h2>
                        </div>
                        <span class="text-xs font-semibold px-2 py-0.5 rounded bg-gray-200/80 text-gray-700">Parts 1 & 2</span>
                    </div>

                    <div class="divide-y divide-gray-100">
                        {{-- Box 1 --}}
                        <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50/50 transition">
                            <div class="pr-4">
                                <div class="flex items-center space-x-2">
                                    <span class="px-2 py-0.5 text-xs font-bold bg-gray-100 text-gray-800 rounded">Box 1</span>
                                    <span class="text-sm font-semibold text-gray-900">Standard-Rated Supplies</span>
                                </div>
                                <div class="text-xs text-gray-500 mt-0.5">Total value of standard-rated supplies (excluding GST)</div>
                            </div>
                            <div class="text-right">
                                <div class="text-base font-bold text-gray-900">S${{ number_format($class->box_1, 2) }}</div>
                            </div>
                        </div>

                        {{-- Box 2 --}}
                        <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50/50 transition">
                            <div class="pr-4">
                                <div class="flex items-center space-x-2">
                                    <span class="px-2 py-0.5 text-xs font-bold bg-gray-100 text-gray-800 rounded">Box 2</span>
                                    <span class="text-sm font-semibold text-gray-900">Zero-Rated Supplies</span>
                                </div>
                                <div class="text-xs text-gray-500 mt-0.5">Total value of zero-rated supplies (exports & international services)</div>
                            </div>
                            <div class="text-right">
                                <div class="text-base font-bold text-gray-900">S${{ number_format($class->box_2, 2) }}</div>
                            </div>
                        </div>

                        {{-- Box 3 --}}
                        <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50/50 transition">
                            <div class="pr-4">
                                <div class="flex items-center space-x-2">
                                    <span class="px-2 py-0.5 text-xs font-bold bg-gray-100 text-gray-800 rounded">Box 3</span>
                                    <span class="text-sm font-semibold text-gray-900">Exempt Supplies</span>
                                </div>
                                <div class="text-xs text-gray-500 mt-0.5">Financial services, residential properties, etc.</div>
                            </div>
                            <div class="text-right">
                                <div class="text-base font-bold text-gray-900">S${{ number_format($class->box_3, 2) }}</div>
                            </div>
                        </div>

                        {{-- Box 4 --}}
                        <div class="px-6 py-4 flex items-center justify-between bg-gray-50 font-bold">
                            <div class="pr-4">
                                <div class="flex items-center space-x-2">
                                    <span class="px-2 py-0.5 text-xs font-bold bg-emerald-100 text-emerald-800 rounded">Box 4</span>
                                    <span class="text-sm font-bold text-gray-900">Total Supplies</span>
                                </div>
                                <div class="text-xs text-gray-500 mt-0.5">Sum of Box 1 + Box 2 + Box 3</div>
                            </div>
                            <div class="text-right">
                                <div class="text-base font-black text-gray-900">S${{ number_format($class->box_4, 2) }}</div>
                            </div>
                        </div>

                        {{-- Box 5 --}}
                        <div class="px-6 py-5 flex items-center justify-between bg-emerald-50/50 border-t-2 border-emerald-200">
                            <div class="pr-4">
                                <div class="flex items-center space-x-2">
                                    <span class="px-2 py-0.5 text-xs font-bold bg-emerald-600 text-white rounded">Box 5</span>
                                    <span class="text-sm font-bold text-emerald-950">Output Tax Due</span>
                                </div>
                                <div class="text-xs text-emerald-800 mt-0.5">Total GST collected on standard-rated supplies</div>
                            </div>
                            <div class="text-right">
                                <div class="text-lg font-black text-emerald-700">S${{ number_format($class->box_5, 2) }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Purchases & Input Tax (Part 3) & Declarations --}}
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden flex flex-col justify-between">
                    <div>
                        <div class="bg-gray-50/80 px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                            <div class="flex items-center space-x-2.5 rtl:space-x-reverse">
                                <span class="w-2.5 h-2.5 rounded-full bg-purple-500"></span>
                                <h2 class="text-base font-bold text-gray-900">Purchases & Input Tax Claimed</h2>
                            </div>
                            <span class="text-xs font-semibold px-2 py-0.5 rounded bg-gray-200/80 text-gray-700">Part 3</span>
                        </div>

                        <div class="divide-y divide-gray-100">
                            {{-- Box 6 --}}
                            <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50/50 transition">
                                <div class="pr-4">
                                    <div class="flex items-center space-x-2">
                                        <span class="px-2 py-0.5 text-xs font-bold bg-gray-100 text-gray-800 rounded">Box 6</span>
                                        <span class="text-sm font-semibold text-gray-900">Taxable Purchases</span>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-0.5">Total value of taxable purchases (excluding GST)</div>
                                </div>
                                <div class="text-right">
                                    <div class="text-base font-bold text-gray-900">S${{ number_format($class->box_6, 2) }}</div>
                                </div>
                            </div>

                            {{-- Box 7 --}}
                            <div class="px-6 py-5 flex items-center justify-between bg-purple-50/40 border-t-2 border-purple-200">
                                <div class="pr-4">
                                    <div class="flex items-center space-x-2">
                                        <span class="px-2 py-0.5 text-xs font-bold bg-purple-600 text-white rounded">Box 7</span>
                                        <span class="text-sm font-bold text-purple-950">Input Tax Claimed</span>
                                    </div>
                                    <div class="text-xs text-purple-800 mt-0.5">GST paid on business purchases & import GST claimed</div>
                                </div>
                                <div class="text-right">
                                    <div class="text-lg font-black text-purple-700">S${{ number_format($class->box_7, 2) }}</div>
                                </div>
                            </div>

                            {{-- Box 9 --}}
                            <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50/50 transition">
                                <div class="pr-4">
                                    <div class="flex items-center space-x-2">
                                        <span class="px-2 py-0.5 text-xs font-bold bg-gray-100 text-gray-800 rounded">Box 9</span>
                                        <span class="text-sm font-semibold text-gray-900">Total Revenue</span>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-0.5">Gross revenue from income statement</div>
                                </div>
                                <div class="text-right">
                                    <div class="text-base font-bold text-gray-900">S${{ number_format($class->box_9, 2) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Supplementary Boxes 10 - 13 --}}
                    <div class="bg-gray-50/90 px-6 py-4 border-t border-gray-200">
                        <div class="text-xs font-bold uppercase tracking-wider text-gray-600 mb-2">Supplementary Declarations</div>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-xs">
                            <div class="bg-white p-2.5 rounded-lg border border-gray-200/80">
                                <span class="font-bold text-gray-800">Box 10:</span> Tourist Refunds<br>
                                <span class="font-semibold text-gray-900">S${{ number_format($class->box_10, 2) }}</span>
                            </div>
                            <div class="bg-white p-2.5 rounded-lg border border-gray-200/80">
                                <span class="font-bold text-gray-800">Box 11:</span> Bad Debt Relief<br>
                                <span class="font-semibold text-gray-900">S${{ number_format($class->box_11, 2) }}</span>
                            </div>
                            <div class="bg-white p-2.5 rounded-lg border border-gray-200/80">
                                <span class="font-bold text-gray-800">Box 12:</span> Pre-reg GST<br>
                                <span class="font-semibold text-gray-900">S${{ number_format($class->box_12, 2) }}</span>
                            </div>
                            <div class="bg-white p-2.5 rounded-lg border border-gray-200/80">
                                <span class="font-bold text-gray-800">Box 13:</span> MES Scheme<br>
                                <span class="font-semibold text-gray-900">S${{ number_format($class->box_13, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- IRAS myTax Portal Quick-Copy Drawer --}}
            <div class="mb-8 bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 border-b border-gray-200 pb-4 mb-4">
                    <div class="flex items-center space-x-3 rtl:space-x-reverse">
                        <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold">
                            <span class="material-icons-outlined">content_copy</span>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-gray-900">IRAS myTax Portal One-Click Copy Assistant</h3>
                            <p class="text-xs text-gray-500">Easily copy each verified value directly into your filing form on mytax.iras.gov.sg</p>
                        </div>
                    </div>
                    <button type="button" onclick="navigator.clipboard.writeText('Box 1: {{ $class->box_1 }}\nBox 2: {{ $class->box_2 }}\nBox 3: {{ $class->box_3 }}\nBox 4: {{ $class->box_4 }}\nBox 5: {{ $class->box_5 }}\nBox 6: {{ $class->box_6 }}\nBox 7: {{ $class->box_7 }}\nBox 8: {{ $class->box_8 }}\nBox 9: {{ $class->box_9 }}'); alert('All IRAS Form 5 box values copied to clipboard!');" class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg shadow-sm transition">
                        <span class="material-icons-outlined text-sm ltr:mr-1.5 rtl:ml-1.5">copy_all</span>
                        Copy All Boxes
                    </button>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3">
                    @php
                        $boxes = [
                            ['num' => '1', 'name' => 'Std-Rated Supplies', 'val' => $class->box_1],
                            ['num' => '2', 'name' => 'Zero-Rated Supplies', 'val' => $class->box_2],
                            ['num' => '3', 'name' => 'Exempt Supplies', 'val' => $class->box_3],
                            ['num' => '4', 'name' => 'Total Supplies', 'val' => $class->box_4],
                            ['num' => '5', 'name' => 'Output Tax Due', 'val' => $class->box_5],
                            ['num' => '6', 'name' => 'Taxable Purchases', 'val' => $class->box_6],
                            ['num' => '7', 'name' => 'Input Tax Claimed', 'val' => $class->box_7],
                            ['num' => '8', 'name' => 'Net GST Position', 'val' => $class->box_8],
                            ['num' => '9', 'name' => 'Total Revenue', 'val' => $class->box_9],
                        ];
                    @endphp

                    @foreach($boxes as $b)
                    <div class="p-3 bg-gray-50 hover:bg-gray-100 rounded-xl border border-gray-200 transition flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between text-xs text-gray-500">
                                <span class="font-bold text-gray-800">Box {{ $b['num'] }}</span>
                                <button type="button" title="Copy value" onclick="navigator.clipboard.writeText('{{ $b['val'] }}'); this.innerText='done'; setTimeout(() => this.innerText='content_copy', 1500);" class="material-icons-outlined text-sm text-gray-400 hover:text-emerald-600">content_copy</button>
                            </div>
                            <div class="text-[11px] text-gray-600 truncate mt-0.5">{{ $b['name'] }}</div>
                        </div>
                        <div class="text-sm font-bold text-gray-900 mt-2">
                            S${{ number_format($b['val'], 2) }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Audit Trail: Supporting Invoices & Purchases Breakdown --}}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden mb-8">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-bold text-gray-900">Audit Trail: Supporting Sales Supplies (Output Tax)</h3>
                        <p class="text-xs text-gray-500">All customer invoices and revenues recognized within this filing period</p>
                    </div>
                    <span class="text-xs font-semibold px-2.5 py-1 bg-emerald-100 text-emerald-800 rounded-full">
                        {{ count($class->sales_transactions) }} Records
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left rtl:text-right text-xs">
                        <thead class="bg-gray-50/50 border-b border-gray-200 text-gray-500 uppercase tracking-wider font-semibold">
                            <tr>
                                <th class="py-3 px-6">Date</th>
                                <th class="py-3 px-6">Invoice #</th>
                                <th class="py-3 px-6">Customer Legal Name</th>
                                <th class="py-3 px-6">Item Description</th>
                                <th class="py-3 px-6">Tax Classification</th>
                                <th class="py-3 px-6 text-right">Taxable Net (SGD)</th>
                                <th class="py-3 px-6 text-right">GST Collected (SGD)</th>
                                <th class="py-3 px-6 text-right">Total (SGD)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($class->sales_transactions as $tx)
                            <tr class="hover:bg-gray-50/50">
                                <td class="py-3 px-6 font-medium text-gray-600">{{ $tx['date'] }}</td>
                                <td class="py-3 px-6 font-bold text-emerald-700">{{ $tx['document_number'] }}</td>
                                <td class="py-3 px-6 font-medium text-gray-900">{{ $tx['contact_name'] }}</td>
                                <td class="py-3 px-6 text-gray-600 max-w-xs truncate">{{ $tx['description'] }}</td>
                                <td class="py-3 px-6">
                                    <span class="px-2 py-0.5 text-[11px] font-semibold rounded bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        {{ $tx['tax_type'] }}
                                    </span>
                                </td>
                                <td class="py-3 px-6 text-right font-medium text-gray-900">S${{ number_format($tx['net_amount'], 2) }}</td>
                                <td class="py-3 px-6 text-right font-bold text-emerald-700">S${{ number_format($tx['tax_amount'], 2) }}</td>
                                <td class="py-3 px-6 text-right font-black text-gray-900">S${{ number_format($tx['total_amount'], 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="py-6 text-center text-gray-400">
                                    No sales transactions recorded in this period.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                        @if(count($class->sales_transactions))
                        <tfoot class="bg-gray-50 border-t border-gray-200 font-bold text-gray-900">
                            <tr>
                                <td colspan="5" class="py-3 px-6">Totals</td>
                                <td class="py-3 px-6 text-right">S${{ number_format($class->box_1, 2) }}</td>
                                <td class="py-3 px-6 text-right text-emerald-700">S${{ number_format($class->box_5, 2) }}</td>
                                <td class="py-3 px-6 text-right font-black">S${{ number_format($class->box_1 + $class->box_5, 2) }}</td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
            </div>

            {{-- Audit Trail: Supporting Purchase Bills & Expenses --}}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden mb-8">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-bold text-gray-900">Audit Trail: Supporting Purchases & Expenses (Input Tax Claimed)</h3>
                        <p class="text-xs text-gray-500">All supplier bills and expenses incurred for business purposes</p>
                    </div>
                    <span class="text-xs font-semibold px-2.5 py-1 bg-purple-100 text-purple-800 rounded-full">
                        {{ count($class->purchase_transactions) }} Records
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left rtl:text-right text-xs">
                        <thead class="bg-gray-50/50 border-b border-gray-200 text-gray-500 uppercase tracking-wider font-semibold">
                            <tr>
                                <th class="py-3 px-6">Date</th>
                                <th class="py-3 px-6">Bill / Ref #</th>
                                <th class="py-3 px-6">Supplier Legal Name</th>
                                <th class="py-3 px-6">Item Description</th>
                                <th class="py-3 px-6">Tax Classification</th>
                                <th class="py-3 px-6 text-right">Taxable Net (SGD)</th>
                                <th class="py-3 px-6 text-right">GST Paid (SGD)</th>
                                <th class="py-3 px-6 text-right">Total (SGD)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($class->purchase_transactions as $tx)
                            <tr class="hover:bg-gray-50/50">
                                <td class="py-3 px-6 font-medium text-gray-600">{{ $tx['date'] }}</td>
                                <td class="py-3 px-6 font-bold text-purple-700">{{ $tx['document_number'] }}</td>
                                <td class="py-3 px-6 font-medium text-gray-900">{{ $tx['contact_name'] }}</td>
                                <td class="py-3 px-6 text-gray-600 max-w-xs truncate">{{ $tx['description'] }}</td>
                                <td class="py-3 px-6">
                                    <span class="px-2 py-0.5 text-[11px] font-semibold rounded bg-purple-50 text-purple-700 border border-purple-200">
                                        {{ $tx['tax_type'] }}
                                    </span>
                                </td>
                                <td class="py-3 px-6 text-right font-medium text-gray-900">S${{ number_format($tx['net_amount'], 2) }}</td>
                                <td class="py-3 px-6 text-right font-bold text-purple-700">S${{ number_format($tx['tax_amount'], 2) }}</td>
                                <td class="py-3 px-6 text-right font-black text-gray-900">S${{ number_format($tx['total_amount'], 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="py-6 text-center text-gray-400">
                                    No purchase transactions recorded in this period.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                        @if(count($class->purchase_transactions))
                        <tfoot class="bg-gray-50 border-t border-gray-200 font-bold text-gray-900">
                            <tr>
                                <td colspan="5" class="py-3 px-6">Totals</td>
                                <td class="py-3 px-6 text-right">S${{ number_format($class->box_6, 2) }}</td>
                                <td class="py-3 px-6 text-right text-purple-700">S${{ number_format($class->box_7, 2) }}</td>
                                <td class="py-3 px-6 text-right font-black">S${{ number_format($class->box_6 + $class->box_7, 2) }}</td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
            </div>

            {{-- Statutory Declarations & Compliance Footer --}}
            <div class="p-6 rounded-2xl bg-gray-50 border border-gray-200 text-xs text-gray-600">
                <div class="font-bold text-gray-800 mb-1">Inland Revenue Authority of Singapore (IRAS) Compliance Note</div>
                <p>This return is prepared in accordance with the Singapore Goods and Services Tax Act 1993 and IRAS e-Tax Guides. The figures reflected in Boxes 1 through 9 represent verified ledger balances under the selected accounting basis. Ensure that official tax invoices for standard-rated sales and tax invoices from GST-registered suppliers for input tax claims are retained for a minimum of 5 years.</p>
            </div>
        </div>
    </x-slot>

    <x-script folder="common" file="reports" />
</x-layouts.admin>
