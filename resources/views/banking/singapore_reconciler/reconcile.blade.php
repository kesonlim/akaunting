<x-layouts.admin>
    <x-slot name="title">
        Reconcile Bank Transactions - {{ $format_name }}
    </x-slot>

    <x-slot name="content">
    <div class="my-6">
        {{-- Hero Header Card with Statement Metrics --}}
        <div class="mb-8 rounded-2xl p-6 text-white shadow-xl" style="background: linear-gradient(135deg, #0A3B32 0%, #0f5145 100%); color: #ffffff;">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 border-b border-emerald-600/40 pb-5">
                <div class="flex items-center space-x-3.5 rtl:space-x-reverse">
                    <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center border border-white/20">
                        <span class="material-icons-outlined text-3xl text-emerald-300">sync_alt</span>
                    </div>
                    <div>
                        <div class="flex items-center space-x-2 rtl:space-x-reverse">
                            <span class="px-2.5 py-0.5 text-xs font-semibold uppercase tracking-wider rounded-full bg-emerald-400/20 text-emerald-200 border border-emerald-300/30">
                                {{ $format_name }}
                            </span>
                            <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-white/20 text-white">
                                {{ $count }} Transactions Parsed
                            </span>
                        </div>
                        <h1 class="text-2xl font-bold tracking-tight text-white mt-1">
                            Smart Bank Reconciliation & Match Review
                        </h1>
                    </div>
                </div>

                <div class="flex items-center space-x-2 rtl:space-x-reverse">
                    <a href="{{ route('singapore-reconciler.index') }}" class="inline-flex items-center bg-white/10 hover:bg-white/20 text-white text-xs font-semibold px-3 py-2 rounded-lg border border-white/20">
                        <span class="material-icons-outlined text-sm ltr:mr-1 rtl:ml-1">arrow_back</span>
                        Upload New Statement
                    </a>
                </div>
            </div>

            {{-- Statement Telemetry Grid --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 pt-5 text-sm">
                <div>
                    <div class="text-xs font-semibold uppercase tracking-wider" style="color: #6ee7b7;">Total Deposits (Inflow)</div>
                    <div class="font-black text-white text-xl mt-0.5">+S${{ number_format($total_inflow, 2) }}</div>
                </div>
                <div>
                    <div class="text-xs font-semibold uppercase tracking-wider" style="color: #6ee7b7;">Total Debits (Outflow)</div>
                    <div class="font-black text-rose-200 text-xl mt-0.5">-S${{ number_format($total_outflow, 2) }}</div>
                </div>
                <div>
                    <div class="text-xs font-semibold uppercase tracking-wider" style="color: #6ee7b7;">Net Cash Movement</div>
                    <div class="font-black text-xl mt-0.5 {{ $net_movement >= 0 ? 'text-emerald-300' : 'text-amber-300' }}">
                        {{ $net_movement >= 0 ? '+' : '' }}S${{ number_format($net_movement, 2) }}
                    </div>
                </div>
                <div>
                    <div class="text-xs font-semibold uppercase tracking-wider" style="color: #6ee7b7;">Auto-Match Confidence</div>
                    <div class="font-black text-emerald-300 text-xl mt-0.5">{{ $high_confidence_count }} of {{ $count }} Ready</div>
                </div>
            </div>
        </div>

        {{-- Match Action Notification Banner --}}
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div class="flex items-center space-x-3">
                <span class="material-icons-outlined text-2xl text-emerald-600">auto_awesome</span>
                <div>
                    <div class="text-sm font-bold text-emerald-950">
                        Intelligent Rule Match Complete
                    </div>
                    <div class="text-xs text-emerald-700">
                        {{ $high_confidence_count }} transactions have been matched with high confidence against open Singapore PayNow invoices and recurring expense rules.
                    </div>
                </div>
            </div>

            @if($high_confidence_count > 0)
                <button type="button" onclick="batchApproveAll()" id="btn-batch-approve" class="px-5 py-2.5 rounded-xl font-bold text-xs text-white shadow-sm transition-all flex items-center" style="background: linear-gradient(135deg, #0A3B32 0%, #10B981 100%);">
                    <span class="material-icons-outlined text-sm mr-1.5">done_all</span>
                    Approve All {{ $high_confidence_count }} High-Confidence Matches (1-Click)
                </button>
            @endif
        </div>

        {{-- Side-by-Side Transaction Match List --}}
        <div class="space-y-4 mb-10">
            @foreach($transactions as $idx => $t)
                @php
                    $sugg = $t['suggestion'];
                    $isHighConf = $sugg['confidence'] >= 85;
                    $isIncome = $t['type'] === 'income';
                @endphp

                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 transition-all hover:border-emerald-300" id="card-tx-{{ $idx }}">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">
                        
                        {{-- Left Column: Bank Statement Line (5 cols) --}}
                        <div class="lg:col-span-5 border-b lg:border-b-0 lg:border-r border-gray-100 pb-4 lg:pb-0 lg:pr-6">
                            <div class="flex items-center justify-between mb-2">
                                <span class="px-2 py-0.5 text-xs font-bold rounded-full {{ $isIncome ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                    {{ $isIncome ? 'Deposit / Credit' : 'Withdrawal / Debit' }}
                                </span>
                                <span class="text-xs font-mono text-gray-500">{{ $t['date'] }}</span>
                            </div>

                            <div class="text-base font-bold text-gray-900 mb-1">
                                {{ $t['description'] }}
                            </div>

                            <div class="flex items-center space-x-3 text-xs text-gray-500">
                                @if(!empty($t['reference']))
                                    <span>Ref: <strong class="text-gray-700 font-mono">{{ $t['reference'] }}</strong></span>
                                @endif
                                @if(!empty($t['uen']))
                                    <span class="px-1.5 py-0.5 bg-gray-100 rounded text-gray-700 font-mono">UEN: {{ $t['uen'] }}</span>
                                @endif
                            </div>

                            <div class="mt-3 text-xl font-black {{ $isIncome ? 'text-emerald-600' : 'text-rose-600' }}">
                                {{ $isIncome ? '+' : '-' }}S${{ number_format($t['amount'], 2) }}
                            </div>
                        </div>

                        {{-- Middle Column: Match Indicator & Suggestion (5 cols) --}}
                        <div class="lg:col-span-5">
                            <div class="flex items-center space-x-2 mb-2">
                                <span class="px-2.5 py-0.5 text-xs font-bold rounded-full 
                                    @if($sugg['badge_color'] === 'emerald') bg-emerald-100 text-emerald-800
                                    @elseif($sugg['badge_color'] === 'purple') bg-purple-100 text-purple-800
                                    @elseif($sugg['badge_color'] === 'amber') bg-amber-100 text-amber-800
                                    @elseif($sugg['badge_color'] === 'blue') bg-blue-100 text-blue-800
                                    @else bg-gray-100 text-gray-700 @endif">
                                    {{ $sugg['badge'] }} ({{ $sugg['confidence'] }}%)
                                </span>
                                <span class="text-xs text-gray-500">Straits Match Engine</span>
                            </div>

                            <div class="text-sm font-bold text-gray-900 mb-1">
                                @if($sugg['action'] === 'match_invoice')
                                    Match & Settle Invoice: <span class="text-emerald-700 font-mono">#{{ $sugg['target_number'] }}</span> ({{ $sugg['target_entity'] }})
                                @elseif($sugg['action'] === 'match_bill')
                                    Match & Settle Bill: <span class="text-emerald-700 font-mono">#{{ $sugg['target_number'] }}</span> ({{ $sugg['target_entity'] }})
                                @elseif($sugg['action'] === 'create_expense')
                                    Record Expense: <span class="text-purple-700 font-semibold">{{ $sugg['category_name'] }}</span> ({{ $sugg['target_entity'] }})
                                @else
                                    Record Direct Sales: <span class="text-emerald-700 font-semibold">{{ $sugg['category_name'] ?? 'Sales' }}</span>
                                @endif
                            </div>

                            <p class="text-xs text-gray-600 mb-2">
                                {{ $sugg['reason'] }}
                            </p>

                            <div class="text-xs text-gray-400">
                                Allocated to: <strong class="text-gray-600">{{ $account['name'] ?? 'Operating Account' }}</strong>
                            </div>
                        </div>

                        {{-- Right Column: Action Buttons (2 cols) --}}
                        <div class="lg:col-span-2 flex flex-col items-stretch space-y-2" id="action-wrapper-{{ $idx }}">
                            <button type="button" 
                                onclick="approveSingle({{ $idx }})" 
                                id="btn-approve-{{ $idx }}" 
                                class="w-full py-2.5 px-3 rounded-xl text-xs font-bold text-white shadow-sm transition-all flex items-center justify-center" 
                                style="background: linear-gradient(135deg, #0A3B32 0%, #10B981 100%);">
                                <span class="material-icons-outlined text-sm mr-1">check_circle</span>
                                Approve Match
                            </button>

                            <button type="button" onclick="ignoreSingle({{ $idx }})" class="w-full py-1.5 px-3 rounded-xl text-xs font-medium text-gray-500 hover:text-gray-700 hover:bg-gray-100 flex items-center justify-center">
                                <span class="material-icons-outlined text-sm mr-1">close</span>
                                Ignore
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Interactive JavaScript for 1-Click Execution --}}
    <script>
        const transactionsData = @json($transactions);
        const accountId = {{ $account['id'] ?? 1 }};
        const reconcileUrl = "{{ route('singapore-reconciler.reconcile') }}";
        const batchUrl = "{{ route('singapore-reconciler.batch-reconcile') }}";
        const csrfToken = "{{ csrf_token() }}";

        async function approveSingle(index) {
            const tx = transactionsData[index];
            const btn = document.getElementById('btn-approve-' + index);
            btn.disabled = true;
            btn.innerHTML = '<span class="material-icons-outlined text-sm mr-1 animate-spin">refresh</span> Processing...';

            const payload = {
                _token: csrfToken,
                action: tx.suggestion.action,
                amount: tx.amount,
                date: tx.date,
                description: tx.description,
                reference: tx.reference,
                account_id: accountId,
                target_id: tx.suggestion.target_id,
                category_id: tx.suggestion.category_id
            };

            try {
                const res = await fetch(reconcileUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify(payload)
                });
                const data = await res.json();

                if (data.success) {
                    markSuccess(index, data.message);
                } else {
                    btn.disabled = false;
                    btn.innerHTML = 'Retry';
                    alert(data.error || 'Failed to reconcile transaction.');
                }
            } catch (err) {
                btn.disabled = false;
                btn.innerHTML = 'Retry';
                alert('Network error during reconciliation: ' + err.message);
            }
        }

        async function batchApproveAll() {
            const btn = document.getElementById('btn-batch-approve');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<span class="material-icons-outlined text-sm mr-1 animate-spin">refresh</span> Processing All Matches...';
            }

            const highConfidenceMatches = [];
            transactionsData.forEach((tx, idx) => {
                const card = document.getElementById('card-tx-' + idx);
                if (card && !card.dataset.reconciled && tx.suggestion.confidence >= 85) {
                    highConfidenceMatches.push({
                        action: tx.suggestion.action,
                        amount: tx.amount,
                        date: tx.date,
                        description: tx.description,
                        reference: tx.reference,
                        account_id: accountId,
                        target_id: tx.suggestion.target_id,
                        category_id: tx.suggestion.category_id,
                        _client_index: idx
                    });
                }
            });

            if (highConfidenceMatches.length === 0) {
                alert('No high-confidence matches pending.');
                if (btn) btn.disabled = false;
                return;
            }

            try {
                const res = await fetch(batchUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        _token: csrfToken,
                        matches: highConfidenceMatches
                    })
                });
                const data = await res.json();

                if (data.success) {
                    highConfidenceMatches.forEach(m => {
                        markSuccess(m._client_index, 'Reconciled via 1-click batch.');
                    });
                    if (btn) {
                        btn.classList.remove('bg-emerald-600');
                        btn.classList.add('bg-gray-400');
                        btn.innerHTML = '<span class="material-icons-outlined text-sm mr-1">done</span> All ' + data.success_count + ' Matches Settled!';
                    }
                } else {
                    alert(data.error || 'Batch reconciliation encountered issues.');
                    if (btn) btn.disabled = false;
                }
            } catch (err) {
                alert('Network error during batch reconciliation: ' + err.message);
                if (btn) btn.disabled = false;
            }
        }

        function markSuccess(index, message) {
            const card = document.getElementById('card-tx-' + index);
            if (card) {
                card.dataset.reconciled = "true";
                card.classList.remove('hover:border-emerald-300');
                card.classList.add('bg-emerald-50/40', 'border-emerald-400');
            }
            const wrapper = document.getElementById('action-wrapper-' + index);
            if (wrapper) {
                wrapper.innerHTML = `
                    <div class="py-2.5 px-3 rounded-xl bg-emerald-100 text-emerald-800 text-xs font-bold text-center flex items-center justify-center">
                        <span class="material-icons-outlined text-sm mr-1">verified</span> Reconciled
                    </div>
                `;
            }
        }

        function ignoreSingle(index) {
            const card = document.getElementById('card-tx-' + index);
            if (card) {
                card.style.opacity = '0.4';
            }
            const wrapper = document.getElementById('action-wrapper-' + index);
            if (wrapper) {
                wrapper.innerHTML = '<span class="text-xs text-gray-400 italic text-center py-2">Ignored</span>';
            }
        }
    </script>
    </x-slot>
</x-layouts.admin>
