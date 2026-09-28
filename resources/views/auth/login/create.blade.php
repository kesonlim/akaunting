<x-layouts.auth>
    <x-slot name="title">
        {{ trans('auth.login') }} &bull; StraitsLedger Singapore
    </x-slot>

    <x-slot name="content">
        <div class="mb-4">
            <a href="{{ url('/') }}" class="inline-flex items-center text-xs font-semibold text-emerald-800 hover:text-emerald-950 mb-5 transition group">
                <svg class="w-3.5 h-3.5 mr-1.5 transform group-hover:-translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to StraitsLedger Home
            </a>

            <div class="flex items-center space-x-3 mb-4">
                <svg width="42" height="42" viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <polygon points="60,6 106,33 106,87 60,114 14,87 14,33" fill="#0A3B32"/>
                    <polygon points="60,6 106,33 106,87 60,114 60,60" fill="#10B981" fill-opacity="0.85"/>
                    <polygon points="60,114 106,87 60,60" fill="#E8E5DF" fill-opacity="0.4"/>
                    <polygon points="60,114 14,87 60,60" fill="#062620" fill-opacity="0.6"/>
                    <polygon points="60,20 94,40 94,80 60,100 26,80 26,40" fill="#FFFFFF" stroke="#E8E5DF" stroke-width="1.5"/>
                    <path d="M 42 42 L 72 42 L 72 50 L 52 50 L 52 58 L 78 58 L 78 78 L 42 78 L 42 70 L 68 70 L 68 64 L 42 64 Z" fill="#0A3B32"/>
                    <path d="M 40 40 L 70 40 L 70 48 L 50 48 L 50 56 L 76 56 L 76 76 L 40 76 L 40 68 L 66 68 L 66 62 L 40 62 Z" fill="#E8E5DF"/>
                </svg>
                <div>
                    <div class="font-extrabold text-xl tracking-tight text-[#0A3B32] leading-none" style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">STRAITS LEDGER</div>
                    <div class="text-[10px] font-semibold text-[#10B981] tracking-widest mt-0.5">SINGAPORE CLOUD ACCOUNTING</div>
                </div>
            </div>

            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">
                Sign in to your organization
            </h1>
            <p class="text-xs text-slate-500 mt-1">
                Access your IRAS GST Form 5 filings, general ledger, and PayNow invoices.
            </p>
        </div>

        <div :class="(form.response.success) ? 'w-full bg-emerald-50 text-emerald-800 border border-emerald-200 p-3 rounded-xl font-semibold text-xs mb-3' : 'hidden'"
            v-if="form.response.success"
            v-html="form.response.message"
            v-cloak
        ></div>

        <div :class="(form.response.error) ? 'w-full bg-red-50 text-red-700 border border-red-200 p-3 rounded-xl font-semibold text-xs mb-3' : 'hidden'"
            v-if="form.response.error"
            v-html="form.response.message"
            v-cloak
        ></div>

        <x-form id="auth" route="login">
            <div class="grid sm:grid-cols-6 gap-x-8 gap-y-5 my-2">
                <x-form.group.email
                    name="email"
                    label="{{ trans('general.email') }}"
                    placeholder="name@company.sg"
                    form-group-class="sm:col-span-6"
                    input-group-class="input-group-alternative"
                />

                <x-form.group.password
                    name="password"
                    label="{{ trans('auth.password.pass') }}"
                    placeholder="••••••••"
                    form-group-class="sm:col-span-6"
                    input-group-class="input-group-alternative"
                />

                <div class="sm:col-span-6 flex flex-row justify-between items-center -mt-1">
                    @stack('remember_input_start')
                    <div>
                        <x-form.input.checkbox
                            name="remember"
                            label="{{ trans('auth.remember_me') }}"
                            value="1"
                            v-model="form.remember"
                            id="checkbox-remember"
                            class="text-emerald-700 focus:outline-none focus:ring-emerald-700 focus:border-emerald-700"
                        />
                    </div>
                    @stack('remember_input_end')

                    @stack('forgotten-password-start')
                    <x-link href="{{ route('forgot') }}" class="text-slate-500 hover:text-emerald-800 text-xs font-medium" override="class">
                        {{ trans('auth.forgot_password') }}
                    </x-link>
                    @stack('forgotten-password-end')
                </div>

                <x-button
                    type="submit"
                    ::disabled="form.loading"
                    class="relative flex items-center justify-center text-white px-6 py-3 text-sm font-bold rounded-xl shadow-md transition disabled:opacity-50 sm:col-span-6 cursor-pointer"
                    style="background-color: #0A3B32;"
                    onmouseover="this.style.backgroundColor='#072A24'"
                    onmouseout="this.style.backgroundColor='#0A3B32'"
                    override="class"
                >
                    <x-button.loading>
                        {{ trans('auth.login') }}
                    </x-button.loading>
                </x-button>

                <div class="sm:col-span-6 text-center pt-2">
                    <span class="text-xs text-slate-500">Don't have an account yet?</span>
                    <a href="{{ url('/#pricing') }}" class="text-xs font-bold text-[#0A3B32] hover:text-[#10B981] ml-1 transition">
                        Start 14-day free trial &rarr;
                    </a>
                </div>

                <div class="sm:col-span-6 flex items-center justify-center space-x-2 text-[11px] text-slate-400 mt-2 pt-3 border-t border-slate-100">
                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    <span>256-bit TLS &bull; PDPA Compliant &bull; Singapore Data Residency</span>
                </div>
            </div>
        </x-form>
    </x-slot>

    <x-script folder="auth" file="common" />
</x-layouts.auth>

