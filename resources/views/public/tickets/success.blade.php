@extends('layouts.public')

@section('title', 'Tiket Berhasil Dibuat')

@section('content')
    <h1 class="sr-only">Laporan terkirim — nomor tiket Anda</h1>

    <p class="eyebrow mb-5 text-center">Laporan Anda sudah tercatat di sistem</p>

    <div class="relative mx-auto max-w-md">
        {{-- Mulut dispenser --}}
        <div class="relative z-10 h-3.5 rounded-full bg-ink shadow-[var(--shadow-card-sm)]" aria-hidden="true">
            <div class="absolute left-1/2 top-1/2 h-1 w-28 -translate-x-1/2 -translate-y-1/2 rounded-full bg-white/20"></div>
        </div>

        <div class="relative z-0 -mt-0.5 overflow-hidden pt-0.5">
            <div class="stub-anim tear-bottom sheet mt-[-2px] px-6 pt-7 sm:px-8">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="eyebrow text-faint">Bukti pelaporan masalah</p>
                        <p class="mt-2 font-mono text-xs uppercase tracking-wider text-muted">{{ $ticket->created_at->translatedFormat('d M Y · H.i') }}</p>
                    </div>
                    <span class="flex h-9 items-center rounded-[4px] bg-ink px-2.5 font-mono text-[11px] font-semibold uppercase tracking-[0.14em] text-signal-400">
                        No. Tiket
                    </span>
                </div>

                <p class="select-all break-all mt-5 font-mono text-[27px] font-semibold leading-tight tracking-tight text-ink sm:text-[32px]">
                    {{ $ticket->ticket_number }}
                </p>

                <div class="mt-6 grid grid-cols-2 gap-x-5 gap-y-4 border-t border-dashed border-rule pt-5">
                    <div>
                        <dt class="font-mono text-[9px] uppercase tracking-[0.18em] text-faint">Pelapor</dt>
                        <dd class="mt-1 truncate text-sm font-semibold text-ink">{{ $ticket->requester_name }}</dd>
                    </div>
                    <div>
                        <dt class="font-mono text-[9px] uppercase tracking-[0.18em] text-faint">Departemen</dt>
                        <dd class="mt-1 truncate text-sm font-semibold text-ink">{{ $ticket->department ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="font-mono text-[9px] uppercase tracking-[0.18em] text-faint">Kategori</dt>
                        <dd class="mt-1 truncate text-sm font-semibold text-ink">{{ $ticket->category?->category_name }}</dd>
                    </div>
                    <div>
                        <dt class="font-mono text-[9px] uppercase tracking-[0.18em] text-faint">Urgensi</dt>
                        <dd class="mt-1"><x-badge :value="$ticket->priority" size="sm" /></dd>
                    </div>
                    <div>
                        <dt class="font-mono text-[9px] uppercase tracking-[0.18em] text-faint">Status</dt>
                        <dd class="mt-1"><x-badge :value="$ticket->status" size="sm" /></dd>
                    </div>
                    <div class="col-span-2">
                        <dt class="font-mono text-[9px] uppercase tracking-[0.18em] text-faint">Target Penyelesaian (SLA)</dt>
                        <dd class="mt-1 font-mono text-sm font-semibold tabular-nums text-ink">
                            {{ $ticket->sla_due_at->format('d M Y, H.i') }} WIB
                        </dd>
                    </div>
                </div>

                <div class="relative mt-7 border-t border-dashed border-rule pt-6">
                    <span class="absolute -top-2 left-1/2 -translate-x-1/2 bg-sheet px-3 font-mono text-[9px] uppercase tracking-[0.22em] text-faint">sobek di sini</span>

                    @php
                        $bars = collect(str_split($ticket->ticket_number))
                            ->map(fn ($ch, $i) => (object) [
                                'w' => 1 + (ord($ch) + $i * 3) % 3,
                                'g' => 1 + (ord($ch) * ($i + 5)) % 4,
                            ]);
                    @endphp
                    <div class="barcode mx-auto max-w-[240px] text-ink" aria-hidden="true">
                        @foreach ($bars as $bar)
                            <span style="width: {{ $bar->w }}px; margin-right: {{ $bar->g }}px"></span>
                        @endforeach
                    </div>
                    <p class="mt-2 text-center font-mono text-[10px] tracking-[0.35em] text-muted">{{ $ticket->ticket_number }}</p>
                </div>
            </div>
        </div>

        {{-- Cap karet — di luar wrapper agar tidak terpotong --}}
        <span class="stamp-anim stamp-mark absolute -top-10 right-0 z-20 -rotate-[5deg] border-stamp-600 bg-sheet/80 text-stamp-600 mix-blend-multiply sm:-right-6">
            Tercatat
        </span>
    </div>

    <div class="mx-auto mt-8 flex max-w-md flex-col gap-3 sm:flex-row">
        <x-button href="{{ route('tickets.check', ['number' => $ticket->ticket_number]) }}" variant="dark" class="w-full sm:w-auto sm:flex-1">
            Cek Status Tiket Ini
        </x-button>
        <x-button href="{{ route('tickets.create') }}" variant="secondary" class="w-full sm:w-auto">
            Buat Tiket Baru
        </x-button>
    </div>

    <p class="mx-auto mt-5 max-w-md text-center text-xs leading-relaxed text-faint">
        Simpan nomor tiket di atas untuk memantau status penanganan kapan saja — tanpa perlu menghubungi IT langsung.
    </p>
@endsection
