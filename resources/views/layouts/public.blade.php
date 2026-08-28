<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'IT Helpdesk') — {{ $siteSetting?->app_name ?? config('app.name') }}</title>

    @if ($siteSetting?->favicon_path)
        <link rel="icon" href="{{ asset('storage/'.$siteSetting->favicon_path) }}">
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full">
    @php
        $ss = $siteSetting ?? (object) [
            'app_name' => config('app.name'),
            'app_short_name' => 'IT',
            'tagline' => 'Biro Dukungan Teknis',
            'form_title' => 'Lapor Masalah IT',
            'form_description' => null,
            'footer_text' => 'Formulir FRM-IT-001 · Rev.02',
            'logo_path' => null,
            'favicon_path' => null,
        ];
    @endphp

    <div class="flex min-h-screen flex-col">
        <header class="border-b border-ink bg-sheet">
            <nav class="mx-auto flex h-16 max-w-3xl items-center justify-between gap-4 px-4 sm:px-6">
                <a href="{{ route('home') }}" class="group flex items-center gap-3" aria-label="{{ $ss->app_name }} — beranda">
                    @if ($ss->logo_path)
                        <img src="{{ asset('storage/'.$ss->logo_path) }}" alt="{{ $ss->app_name }}" class="h-9 w-9 rounded-[4px] border border-ink/30 object-contain bg-sheet p-0.5" />
                    @else
                        <span class="flex h-9 w-9 items-center justify-center rounded-[4px] bg-ink font-mono text-sm font-semibold tracking-tight text-white shadow-[2px_2px_0_0_var(--color-stamp-600)]">
                            {{ $ss->app_short_name }}
                        </span>
                    @endif
                    <span class="flex flex-col leading-tight">
                        <span class="display text-[13px] leading-none">{{ $ss->app_name }}</span>
                        <span class="mt-1 font-mono text-[9px] font-medium uppercase tracking-[0.24em] text-muted">{{ $ss->tagline }}</span>
                    </span>
                </a>

                <div class="flex items-center gap-1 sm:gap-5">
                    <a href="{{ route('tickets.create') }}" aria-current="{{ request()->routeIs('tickets.create') ? 'page' : false }}"
                       class="px-1 py-2 font-mono text-[11px] font-semibold uppercase tracking-[0.18em] {{ request()->routeIs('tickets.create') ? 'text-ink underline decoration-signal-500 decoration-[3px] underline-offset-8' : 'text-muted hover:text-ink' }}">
                        Buat Tiket
                    </a>
                    <a href="{{ route('tickets.check') }}" aria-current="{{ request()->routeIs('tickets.check') ? 'page' : false }}"
                       class="px-1 py-2 font-mono text-[11px] font-semibold uppercase tracking-[0.18em] {{ request()->routeIs('tickets.check') ? 'text-ink underline decoration-signal-500 decoration-[3px] underline-offset-8' : 'text-muted hover:text-ink' }}">
                        Cek Status
                    </a>
                </div>
            </nav>
        </header>

        <main class="mx-auto w-full max-w-3xl flex-1 px-4 py-10 sm:px-6 sm:py-12">
            @yield('content')
        </main>

        <footer class="border-t border-rule/70 pb-10 pt-5 text-center font-mono text-[10px] uppercase tracking-[0.18em] text-faint">
            {{ $ss->footer_text }} · {{ date('Y') }}
        </footer>
    </div>

    @stack('scripts')
</body>
</html>
