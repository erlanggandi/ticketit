<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Admin {{ config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full">
    <div class="min-h-screen">
        @php
            $currentRoute = request()->route()?->getName();

            $mainNav = [
                ['route' => 'admin.dashboard', 'href' => route('admin.dashboard'), 'label' => 'Dashboard', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75" />'],
                ['route' => 'admin.tickets.index', 'href' => route('admin.tickets.index'), 'label' => 'Tiket', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />'],
                ['route' => 'admin.diagnosis.index', 'href' => route('admin.diagnosis.index'), 'label' => 'Diagnosa', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 0 1-2 2v12a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2h-2a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h2Z M9 13h6" />'],
                ['route' => 'admin.categories.index', 'href' => route('admin.categories.index'), 'label' => 'Kategori', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z M6 6h.008v.008H6V6z" />'],
                ['route' => 'admin.employees.index', 'href' => route('admin.employees.index'), 'label' => 'Karyawan', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />'],
                ['route' => 'admin.technicians.index', 'href' => route('admin.technicians.index'), 'label' => 'Teknisi', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437l1.745-1.437m6.486 1.149c.181-.506 1.138-.506 1.319 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.246.413 1.246 2.16 0 2.573a1.724 1.724 0 00-1.065 2.572c.12.297.09.913-.046 1.163" />'],
                ['route' => 'admin.reports.index', 'href' => route('admin.reports.index'), 'label' => 'Laporan', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />'],
                ['route' => 'admin.audit-logs.index', 'href' => route('admin.audit-logs.index'), 'label' => 'Audit Log', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />'],
            ];

            $settingNav = [
                ['route' => 'admin.site-settings.edit', 'href' => route('admin.site-settings.edit'), 'label' => 'Website', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418" />'],
                ['route' => 'admin.auth-settings.show', 'href' => route('admin.auth-settings.show'), 'label' => 'Autentikasi', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />'],
                ['route' => 'admin.sla-settings.edit', 'href' => route('admin.sla-settings.edit'), 'label' => 'SLA', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z" />'],
                ['route' => 'admin.telegram-settings.show', 'href' => route('admin.telegram-settings.show'), 'label' => 'Telegram', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />'],
                ['route' => 'admin.ai-settings.show', 'href' => route('admin.ai-settings.show'), 'label' => 'AI', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.75H5.625c-.621 0-1.125.504-1.125 1.125v12.75c0 .621.504 1.125 1.125 1.125H9.75A1.125 1.125 0 0111.625 15H9.75a3 3 0 0 1-3-3V5.625a3 3 0 0 1 3-3h3.375c.621 0 1.125.504 1.125 1.125v12.75Z M9.75 3.75V1.5A1.5 1.5 0 0 1 11.25 0h3.375c.621 0 1.125.504 1.125 1.125v2.25A1.5 1.5 0 0 1 14.625 5.25H9.75Zm-2.25 9.5h5.5" />'],
                ['route' => 'admin.admin-manage.index', 'href' => route('admin.admin-manage.index'), 'label' => 'Admin', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />'],
            ];
        @endphp

        <div class="flex min-h-screen">
            <aside id="sidebar" class="fixed inset-y-0 left-0 z-40 flex w-64 -translate-x-full transform flex-col bg-ink transition-transform duration-200 lg:static lg:translate-x-0">
                <div class="flex h-16 items-center gap-3 border-b border-white/15 px-5">
                    <span class="flex h-8 w-8 items-center justify-center rounded-[4px] bg-stamp-600 font-mono text-sm font-semibold text-white shadow-[2px_2px_0_0_var(--color-signal-500)]">
                        IT
                    </span>
                    <span class="flex flex-col leading-tight">
                        <span class="display text-[13px] text-paper">{{ config('app.name') }}</span>
                        <span class="mt-0.5 font-mono text-[9px] uppercase tracking-[0.22em] text-paper/50">Meja Penanganan</span>
                    </span>
                </div>

                <nav class="flex-1 space-y-0.5 overflow-y-auto px-3 py-4">
                    @foreach ($mainNav as $item)
                        <x-nav-item :href="$item['href']" :label="$item['label']" :active="str_starts_with($currentRoute ?? '', $item['route'])">
                            <svg class="h-[18px] w-[18px] shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor">
                                {!! $item['icon'] !!}
                            </svg>
                        </x-nav-item>
                    @endforeach

                    <div class="my-2 border-t border-white/10"></div>

                    <p class="mb-1 px-4 pt-1 font-mono text-[9px] font-semibold uppercase tracking-[0.22em] text-white/30">Pengaturan</p>

                    @foreach ($settingNav as $item)
                        <x-nav-item :href="$item['href']" :label="$item['label']" :active="str_starts_with($currentRoute ?? '', $item['route'])">
                            <svg class="h-[18px] w-[18px] shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor">
                                {!! $item['icon'] !!}
                            </svg>
                        </x-nav-item>
                    @endforeach
                </nav>

                <div class="border-t border-white/15 p-3">
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="flex w-full items-center gap-3 rounded-[4px] border-l-[3px] border-transparent px-4 py-2 text-sm font-medium text-paper/60 transition hover:border-critical-400 hover:bg-critical-500/10 hover:text-critical-300">
                            <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                            </svg>
                            Keluar
                        </button>
                    </form>
                </div>
            </aside>

            <div id="sidebar-backdrop" class="fixed inset-0 z-30 hidden bg-ink/60 backdrop-blur-sm lg:hidden"></div>

            <div class="flex min-w-0 flex-1 flex-col">
                <header class="sticky top-0 z-20 border-b border-ink bg-sheet/95 backdrop-blur">
                    <div class="flex h-16 items-center justify-between gap-3 px-4 sm:px-6 lg:px-8">
                        <div class="flex items-center gap-3">
                            <button type="button" id="sidebar-toggle" class="rounded-[4px] p-2 text-muted hover:bg-paper lg:hidden" aria-label="Menu">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                                </svg>
                            </button>

                            <div>
                                <h1 class="display text-base leading-none">@yield('page_title', 'Dashboard')</h1>
                                <p class="mt-1 hidden font-mono text-[10px] uppercase tracking-[0.14em] text-faint sm:block">@yield('page_subtitle', 'Kelola tiket dan layanan IT internal')</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 sm:gap-4">
                            <div id="notification-bell" class="relative" data-poll-url="{{ route('admin.notifications.poll') }}" data-csrf="{{ csrf_token() }}">
                                <button type="button" id="bell-button" class="relative rounded-[4px] p-2 text-muted transition hover:bg-paper" aria-label="Notifikasi">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                                    </svg>
                                    <span id="bell-badge" class="absolute -right-0.5 -top-0.5 hidden h-4 min-w-4 rounded-full bg-critical-500 px-1 text-center font-mono text-[9px] font-bold leading-4 text-white"></span>
                                </button>

                                <div id="bell-dropdown" class="absolute right-0 mt-2 hidden w-80 origin-top-right overflow-hidden rounded-md border border-ink bg-sheet shadow-[var(--shadow-pop)]">
                                    <div class="flex items-center justify-between border-b border-rule bg-paper/70 px-4 py-2.5">
                                        <span class="font-mono text-[10px] font-semibold uppercase tracking-[0.16em] text-muted">Notifikasi</span>
                                        <button type="button" id="mark-all-read" class="font-mono text-[10px] font-semibold uppercase tracking-wide text-stamp-700 hover:underline">Tandai dibaca</button>
                                    </div>
                                    <ul id="bell-items" class="max-h-80 divide-y divide-rule overflow-y-auto"></ul>
                                    <div id="bell-empty" class="hidden px-4 py-8 text-center font-mono text-[10px] uppercase tracking-widest text-faint">Tidak ada notifikasi</div>
                                </div>
                            </div>

                            <div class="flex items-center gap-2.5 border-l border-rule pl-2 sm:pl-4">
                                <span class="flex h-8 w-8 items-center justify-center rounded-[3px] border border-stamp-600/60 bg-stamp-50 font-mono text-[11px] font-semibold text-stamp-800">
                                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 2)) }}
                                </span>
                                <span class="hidden text-sm font-semibold text-ink sm:block">{{ auth()->user()->name }}</span>
                            </div>
                        </div>
                    </div>
                </header>

                <main class="flex-1 px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
                    @if (session('success'))
                        <div class="mb-5"><x-alert type="success">{{ session('success') }}</x-alert></div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-5 space-y-1">
                            <x-alert type="error">{{ implode(' ', array_unique($errors->all())) }}</x-alert>
                        </div>
                    @endif

                    @yield('content')
                </main>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');

            document.getElementById('sidebar-toggle')?.addEventListener('click', () => {
                sidebar.classList.toggle('-translate-x-full');
                backdrop.classList.toggle('hidden');
            });

            backdrop?.addEventListener('click', () => {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
            });
        })();
    </script>

    <script>
        (function () {
            const bell = document.getElementById('notification-bell');
            if (!bell) return;

            const button = document.getElementById('bell-button');
            const dropdown = document.getElementById('bell-dropdown');
            const badge = document.getElementById('bell-badge');
            const list = document.getElementById('bell-items');
            const empty = document.getElementById('bell-empty');
            const pollUrl = bell.dataset.pollUrl;
            const csrf = bell.dataset.csrf;

            function render(items, unreadCount) {
                list.innerHTML = '';
                empty.classList.toggle('hidden', unreadCount > 0 || items.length > 0);

                if (unreadCount > 0) {
                    badge.textContent = unreadCount > 99 ? '99+' : unreadCount;
                    badge.classList.remove('hidden');
                } else {
                    badge.classList.add('hidden');
                }

                const colors = {
                    new_ticket: 'bg-stamp-500',
                    sla_approaching: 'bg-signal-500',
                    sla_breach: 'bg-critical-500',
                };

                items.forEach((item) => {
                    const li = document.createElement('li');
                    li.innerHTML = `
                        <a href="${item.url ?? '#'}" class="flex gap-3 px-4 py-3 transition hover:bg-paper ${item.is_unread ? 'bg-stamp-50/40' : ''}">
                            <span class="mt-1.5 h-2 w-2 shrink-0 ${colors[item.type] ?? 'bg-faint'}"></span>
                            <span class="min-w-0">
                                <span class="block truncate text-[13px] font-semibold ${item.is_unread ? 'text-ink' : 'text-muted'}">${item.title}</span>
                                <span class="block truncate text-xs text-muted">${item.body}</span>
                                <span class="font-mono text-[10px] uppercase tracking-wide text-faint">${item.time_ago}</span>
                            </span>
                        </a>`;
                    list.appendChild(li);
                });
            }

            async function poll() {
                try {
                    const res = await fetch(pollUrl, { headers: { Accept: 'application/json' } });
                    if (!res.ok) return;
                    const data = await res.json();
                    render(data.items, data.unread_count);
                } catch {}
            }

            button.addEventListener('click', () => dropdown.classList.toggle('hidden'));

            document.addEventListener('click', (e) => {
                if (!bell.contains(e.target)) dropdown.classList.add('hidden');
            });

            document.getElementById('mark-all-read')?.addEventListener('click', async () => {
                await fetch("{{ url('/admin/notifications/read-all') }}", {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrf, Accept: 'application/json' },
                });
                poll();
            });

            poll();
            setInterval(poll, 15000);
        })();
    </script>

    @stack('scripts')
</body>
</html>
