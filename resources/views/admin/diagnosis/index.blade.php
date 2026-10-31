@extends('layouts.admin')

@section('title', 'Diagnosa')

@section('page_title', 'Diagnosa IT')

@section('page_subtitle', 'Analisis diagnosis tiket oleh AI')

@section('content')
    <x-card :padded="false">
        <form method="GET" action="{{ route('admin.diagnosis.index') }}" class="space-y-4 border-b border-dashed border-rule bg-paper/50 p-4">
            <div>
                <label class="label" for="ticket_id">Nomor Tiket Open</label>
                <select id="ticket_id" name="ticket_id" class="field appearance-none pr-9 {{ $ticket === null ? 'text-faint' : '' }}">
                    <option value="" @selected($ticket === null)>Pilih nomor tiket yang masih open</option>

                    @foreach ($openTickets as $ticket)
                        <option value="{{ $ticket->id }}" @selected((string) $ticket->id === (string) ($ticket?->id))>
                            No. Tiket {{ $ticket->ticket_number }} — {{ $ticket->requester_name }} ({{ $ticket->category?->category_name ?? '-' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex justify-end">
                <x-button type="submit">Jalankan Diagnosa</x-button>
            </div>
        </form>
    </x-card>

    @if ($ticket === null)
        <x-card class="mt-6">
            <x-empty-state description="Belum ada tiket open yang tersedia untuk didiagnosis." />
        </x-card>
    @elseif ($diagnosis === null)
        <x-card class="mt-6">
            <x-empty-state description="Belum ada diagnosa untuk tiket ini." />
        </x-card>
    @elseif ($diagnosis['success'])
        <x-card title="Hasil Diagnosis" class="mt-6">
            <p class="text-sm leading-relaxed text-ink">{{ $diagnosis['diagnosis'] }}</p>

            <div class="mt-5 grid gap-3 border-t border-dashed border-rule pt-4 font-mono text-[11px] uppercase tracking-wide text-muted sm:grid-cols-2">
                <div>
                    <span class="text-faint">Confidence</span>
                    <p class="mt-1 font-semibold text-ink">{{ $diagnosis['confidence'] }}%</p>
                </div>
                <div>
                    <span class="text-faint">Metode</span>
                    <p class="mt-1 font-semibold text-ink">{{ $diagnosis['method'] }}</p>
                </div>
            </div>
        </x-card>
    @else
        <x-alert type="error" class="mt-6">
            {{ $diagnosis['message'] ?? $diagnosis['diagnosis'] }}
        </x-alert>
        <div class="mt-6">
            <x-card>
                <p class="text-sm leading-relaxed text-muted">
                    Pastikan provider AI dan API Key sudah benar pada halaman <a href="{{ route('admin.ai-settings.show') }}" class="font-semibold text-stamp-700 underline decoration-stamp-300 underline-offset-4">Pengaturan AI</a>.
                </p>
            </x-card>
        </div>
    @endif
@endsection
