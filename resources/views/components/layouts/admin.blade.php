<!DOCTYPE html>
<html dir="{{ language()->direction() }}" lang="{{ app()->getLocale() }}">
    <x-layouts.admin.head>
        @if (! empty($metaTitle))
        <x-slot name="metaTitle">
            {!! !empty($metaTitle->attributes->has('title')) ? $metaTitle->attributes->get('title') : $metaTitle !!}
        </x-slot>
        @endif

        <x-slot name="title">
            {!! !empty($title->attributes->has('title')) ? $title->attributes->get('title') : $title !!}
        </x-slot>
    </x-layouts.admin.head>

    @mobile
    <body class="bg-body">
    @elsemobile
    <body class="bg-body overflow-y-overlay">
    @endmobile

        @stack('body_start')

        <x-layouts.admin.menu />

        <!-- loading component will add this line -->

        <div class="main-content xl:ltr:ml-64 xl:rtl:mr-64 transition-all ease-in-out" id="panel">
            @if (auth()->check())
                <div style="background: linear-gradient(90deg, #0A3B32 0%, #104C40 60%, #0F6B58 100%); color: #ffffff; padding: 8px 16px; display: flex; align-items: center; justify-content: space-between; font-size: 12px; border-bottom: 1px solid #062620; z-index: 30; position: relative;">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <span style="background: #10B981; color: #0A3B32; font-weight: 800; font-size: 9px; padding: 2px 6px; border-radius: 4px; text-transform: uppercase; letter-spacing: 0.05em;">SaaS Master Owner</span>
                        <span style="color: #a7f3d0;">Viewing Tenant Workspace: <strong style="color: #ffffff;">{{ setting('general.company_name', setting('company.name', 'Tenant Entity')) }}</strong></span>
                    </div>
                    <div>
                        <a href="/platform" style="background: #ffffff; color: #0A3B32; padding: 4px 10px; border-radius: 6px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 1px 2px rgba(0,0,0,0.1); font-size: 11px;">
                            <i class="fa-solid fa-crown" style="color: #f59e0b;"></i>
                            <span>SaaS Master Console</span>
                        </a>
                    </div>
                </div>
            @endif

            <div id="main-body">
                <div class="container">
                    <x-layouts.admin.header>
                        <x-slot name="title">
                            {!! ! empty($title->attributes->has('title')) ? $title->attributes->get('title') : $title !!}
                        </x-slot>

                        @if (! empty($status))
                            <x-slot name="status">
                                {!! $status !!}
                            </x-slot>
                        @endif

                        @if (! empty($info))
                            <x-slot name="info">
                                {!! $info !!}
                            </x-slot>
                        @endif

                        @if (! empty($favorite) || (! empty($favorite) && $favorite->attributes->has('title')))
                            @if (! $favorite->attributes->has('title'))
                                <x-slot name="favorite">
                                    {!! $favorite !!}
                                </x-slot>
                            @else
                                @php
                                    $favorite = [
                                        'title' => $favorite->attributes->get('title'),
                                        'icon' => $favorite->attributes->get('icon'),
                                        'route' => !empty($favorite->attributes->has('route')) ? $favorite->attributes->get('route') : '',
                                        'url' => !empty($favorite->attributes->has('url')) ? $favorite->attributes->get('url') : '',
                                    ];
                                @endphp

                                <x-slot name="favorite">
                                    <x-menu.favorite
                                        :title="$favorite['title']"
                                        :icon="$favorite['icon']"
                                        :route="$favorite['route']"
                                        :url="$favorite['url']"
                                    />
                                </x-slot>
                            @endif
                        @endif

                        <x-slot name="buttons">
                            {!! $buttons ?? '' !!}
                        </x-slot>

                        <x-slot name="moreButtons">
                            {!! $moreButtons ?? '' !!}
                        </x-slot>
                    </x-layouts.admin.header>

                    <x-layouts.admin.content>
                        <livewire:notification.browser />

                        <x-layouts.admin.notifications />

                        {!! $content !!}
                    </x-layouts.admin.content>

                    <x-layouts.admin.footer />
                </div>
            </div>
        </div>

        @stack('body_end')

        <x-layouts.admin.scripts />
    </body>
</html>
