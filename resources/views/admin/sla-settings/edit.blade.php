@extends('layouts.admin')

@section('title', 'SLA Setting')
@section('page_title', 'SLA Setting')
@section('page_subtitle', 'Durasi target penyelesaian per tingkat prioritas')

@section('content')
    <div class="max-w-2xl">
        <x-card title="Durasi SLA (jam)">
            <form method="POST" action="{{ route('admin.sla-settings.update') }}" data-disable-on-submit class="space-y-5">
                @csrf
                @method('PUT')

                @foreach ($durations as $priority => $hours)
                    <div class="flex items-center justify-between gap-4 rounded-[5px] border border-dashed border-rule bg-paper/60 px-4 py-3">
                        <div class="flex items-center gap-3">
                            <x-badge :value="$priority" size="sm" />
                            <span class="hidden text-xs text-muted sm:inline">
                                {{ ['Critical' => 'Operasional berhenti total', 'High' => 'Mengganggu kerja signifikan', 'Medium' => 'Mengganggu namun ada workaround', 'Low' => 'Tidak mendesak'][$priority] }}
                            </span>
                        </div>

                        <div class="flex w-32 items-center gap-2">
                            <input type="number" name="durations[{{ strtolower($priority) }}]" value="{{ $hours }}"
                                   min="1" max="8760" required aria-label="Durasi SLA {{ $priority }} (jam)"
                                   class="field py-2 font-mono font-semibold tabular-nums @error('durations.'.strtolower($priority)) field-error @enderror" />
                            <span class="font-mono text-[10px] uppercase text-muted">jam</span>
                        </div>
                    </div>
                    @error('durations.'.strtolower($priority))
                        <p class="-mt-3 text-right font-mono text-[11px] uppercase tracking-wide text-critical-600">{{ $message }}</p>
                    @enderror
                @endforeach

                <p class="border-t border-dashed border-rule pt-4 text-xs leading-relaxed text-faint">
                    Perubahan berlaku untuk tiket baru dan langsung menghitung ulang due date tiket aktif (Open / In Progress) dari waktu tiket dibuat.
                    Tiket Resolved / Closed tetap menggunakan due date lama agar laporan tetap akurat.
                    Jika Admin mengubah prioritas tiket, due date dihitung ulang dari waktu tiket dibuat.
                </p>

                <x-button type="submit">Simpan Pengaturan</x-button>
            </form>
        </x-card>
    </div>
@endsection
