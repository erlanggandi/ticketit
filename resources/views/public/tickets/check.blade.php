@extends('layouts.public')

@section('title', 'Cek Status Tiket')

@section('content')
    <div class="space-y-6">
        <div class="text-center">
            <h2 class="display text-2xl sm:text-3xl">Cek Status Tiket</h2>
            <p class="mx-auto mt-2 max-w-md text-sm leading-relaxed text-muted">
                Masukkan nomor tiket Anda (contoh: <span class="font-mono text-[13px]">TCK-{{ date('Y') }}-00001</span>) untuk melihat progres penanganan.
            </p>
        </div>

        <x-card :padded="true" class="mx-auto max-w-xl">
            <form method="GET" action="{{ route('tickets.check') }}" class="flex flex-col gap-3 sm:flex-row" autocomplete="off">
                <div class="relative flex-1">
                    <svg class="pointer-events-none absolute left-3 top-1/2 h-[18px] w-[18px] -translate-y-1/2 text-faint" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                    <input type="text" name="number" value="{{ $number }}" placeholder="Masukkan nomor tiket…"
                        class="field py-2.5 pl-9 font-mono uppercase tracking-wide placeholder:font-sans placeholder:normal-case placeholder:tracking-normal" />
                </div>
                <x-button type="submit">Cari Tiket</x-button>
            </form>
        </x-card>

        @if ($searched)
            @if ($ticket)
                @php
                    $steps = ['Open', 'In Progress', 'Resolved', 'Closed'];
                    $currentStep = array_search($ticket->status, $steps);
                    $bars = collect(str_split($ticket->ticket_number))
                        ->map(fn ($ch, $i) => (object) [
                            'w' => 1 + (ord($ch) + $i * 3) % 3,
                            'g' => 1 + (ord($ch) * ($i + 5)) % 4,
                        ]);
                @endphp

                <div class="mx-auto max-w-xl tear-bottom sheet px-6 pb-8 pt-6 sm:px-8">
                    <div class="flex flex-wrap items-start justify-between gap-3 border-b border-dashed border-rule pb-4">
                        <div>
                            <p class="eyebrow text-faint">Status tiket</p>
                            <p class="mt-1.5 select-all font-mono text-lg font-semibold tracking-tight text-ink">{{ $ticket->ticket_number }}</p>
                            <p class="mt-1 font-mono text-[10px] uppercase tracking-wider text-muted">Dibuat {{ $ticket->created_at->format('d M Y, H.i') }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            @if ($ticket->isActive() && $ticket->isOverdue())
                                <span class="chip border-critical-500 bg-critical-500 text-white">Melewati SLA</span>
                            @endif
                            <x-badge :value="$ticket->status" />
                        </div>
                    </div>

                    <dl class="grid grid-cols-2 gap-x-6 gap-y-4 pt-5 sm:grid-cols-5">
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
                            <dt class="font-mono text-[9px] uppercase tracking-[0.18em] text-faint">Target SLA</dt>
                            <dd class="mt-1 font-mono text-sm font-semibold tabular-nums {{ $ticket->isOverdue() ? 'text-critical-600' : 'text-ink' }}">
                                {{ $ticket->sla_due_at->format('d M, H.i') }}
                                @if ($ticket->status === 'Resolved')
                                    <span class="block text-[11px] font-normal normal-case tracking-normal text-ok-600">selesai {{ $ticket->resolved_at->format('d M, H.i') }}</span>
                                @elseif ($ticket->status === 'Closed')
                                    <span class="block text-[11px] font-normal text-faint">(ditutup)</span>
                                @endif
                            </dd>
                        </div>
                    </dl>

                    <div class="border-t border-dashed border-rule pt-4 mt-4">
                        <dt class="font-mono text-[9px] uppercase tracking-[0.18em] text-faint">Deskripsi</dt>
                        <p class="mt-1 whitespace-pre-line text-sm leading-relaxed text-muted">{{ $ticket->description }}</p>
                    </div>

                    {{-- Progres penanganan --}}
                    <div class="mt-7 border-t border-dashed border-rule pt-6">
                        <ol class="flex items-start">
                            @foreach ($steps as $index => $step)
                                @if ($index > 0)
                                    <li class="mt-3 h-[3px] w-full min-w-4 flex-1 {{ $index <= $currentStep ? 'bg-stamp-600' : 'bg-rule' }}" aria-hidden="true"></li>
                                @endif
                                <li class="flex shrink-0 flex-col items-center gap-2">
                                    <span class="flex h-6 w-6 items-center justify-center rounded-[3px] border-2 font-mono text-[11px] font-semibold
                                        {{ $index < $currentStep ? 'border-stamp-600 bg-stamp-600 text-white' : '' }}
                                        {{ $index === $currentStep ? 'border-ink bg-sheet text-ink' : '' }}
                                        {{ $index > $currentStep ? 'border-rule bg-sheet text-faint' : '' }}">
                                        @if ($index < $currentStep)
                                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                        @else
                                            {{ $index + 1 }}
                                        @endif
                                    </span>
                                    <span class="font-mono text-[9px] font-medium uppercase tracking-wide {{ $index <= $currentStep ? 'text-ink' : 'text-faint' }}">{{ $step }}</span>
                                </li>
                            @endforeach
                        </ol>
                    </div>

                    <div class="mt-7 border-t border-dashed border-rule pt-5" aria-hidden="true">
                        <div class="barcode mx-auto max-w-[200px] text-muted/70">
                            @foreach ($bars as $bar)
                                <span style="width: {{ $bar->w }}px; margin-right: {{ $bar->g }}px"></span>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                <div class="mx-auto max-w-xl sheet-flat border-l-4 border-l-signal-500 p-7 text-center sm:p-8">
                    <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-[3px] border-2 border-signal-500 bg-signal-50 text-signal-600">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                        </svg>
                    </span>
                    <p class="display mt-4 text-base">Tiket tidak ditemukan</p>
                    <p class="mx-auto mt-2 max-w-sm text-sm leading-relaxed text-muted">
                        Nomor <span class="font-mono font-semibold text-ink">{{ $number }}</span> tidak terdaftar di sistem. Periksa kembali penulisannya atau hubungi tim IT.
                    </p>
                </div>
            @endif
        @endif

        <p class="text-center font-mono text-[11px] uppercase tracking-[0.14em] text-faint">
            Belum punya tiket?
            <a href="{{ route('tickets.create') }}" class="font-semibold text-stamp-700 underline decoration-stamp-300 underline-offset-4 hover:text-stamp-800">Buat tiket baru</a>
        </p>
    </div>
@endsection
