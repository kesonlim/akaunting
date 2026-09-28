<!DOCTYPE html>
<html dir="{{ language()->direction() }}" lang="{{ app()->getLocale() }}">
    <x-layouts.auth.head>
        <x-slot name="title">
            {!! !empty($title->attributes->has('title')) ? $title->attributes->get('title') : $title !!}
        </x-slot>
    </x-layouts.auth.head>

    @mobile
    <body class="bg-body">
    @elsemobile
    <body class="bg-body overflow-y-overlay">
    @endmobile

        @stack('body_start')

        <div id="app" class="min-h-screen bg-slate-50 flex items-center justify-center relative overflow-hidden" style="background: radial-gradient(circle at 15% 15%, rgba(16, 185, 129, 0.06) 0%, rgba(250, 250, 250, 1) 85%);">
            <div class="relative w-full lg:max-w-7xl flex items-center m-auto">
                <x-layouts.auth.slider>
                    {!! $slider ?? '' !!}
                </x-layouts.auth.slider>

                <x-layouts.auth.content>
                    {!! $content !!}

                    <x-layouts.auth.footer />
                </x-layouts.auth.content>
            </div>
        </div>

        @stack('body_end')

        <x-layouts.auth.scripts />
    </body>
</html>
