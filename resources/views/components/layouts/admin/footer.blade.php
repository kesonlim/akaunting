@stack('footer_start')
    <footer class="footer container">
        <div class="flex flex-col sm:flex-row items-center justify-between lg:mt-20 py-7 text-xs text-slate-400 font-normal">
            <div>
                <span>StraitsLedger</span> &bull; Part of <x-link href="https://straits.thethinkthank.com" target="_blank" class="text-emerald-700 hover:underline" override="class">Straits Business Suite</x-link>
                &nbsp;&bull;&nbsp;
                Singapore Compliance Edition (v{{ version('short') }})
            </div>
            <div class="mt-2 sm:mt-0 flex items-center gap-3">
                <span class="inline-flex items-center text-[11px] text-emerald-800 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span> IRAS 9% GST Form 5 Active
                </span>
            </div>
        </div>
    </footer>
@stack('footer_end')

