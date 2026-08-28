@extends('layouts.admin')

@section('title', 'Laporan')
@section('page_title', 'Laporan & Statistik')
@section('page_subtitle', 'Analitik tiket dan performa SLA tim IT')

@section('content')
    <x-card :padded="false" class="mb-6">
        <form method="GET" action="{{ route('admin.reports.index') }}" class="flex flex-wrap items-end gap-3 border-b border-dashed border-rule bg-paper/50 p-4">
            <div>
                <label class="label" for="report-range">Rentang</label>
                <select id="report-range" name="range" class="field appearance-none py-2 pr-8">
                    <option value="custom" @selected(($range ?? 'custom') === 'custom')>Custom</option>
                    <option value="all" @selected(($range ?? '') === 'all')>Semua Periode</option>
                </select>
            </div>
            <div>
                <label class="label" for="report-from">Dari tanggal</label>
                <input id="report-from" type="date" name="date_from" value="{{ $from }}" @disabled(($range ?? '') === 'all') class="field py-2" />
            </div>
            <div>
                <label class="label" for="report-to">Sampai tanggal</label>
                <input id="report-to" type="date" name="date_to" value="{{ $to }}" @disabled(($range ?? '') === 'all') class="field py-2" />
            </div>
            <x-button type="submit" variant="secondary" size="md">Terapkan</x-button>
        </form>
    </x-card>

    <p class="-mt-2 mb-4 font-mono text-[10px] uppercase tracking-[0.14em] text-faint">
        Periode aktif: <strong class="text-muted">{{ $periodLabel }}</strong>
    </p>

    <script>
        const reportRange = document.getElementById('report-range');

        function syncReportRange() {
            const all = reportRange.value === 'all';
            ['report-from', 'report-to'].forEach((id) => {
                const input = document.getElementById(id);
                if (input) {
                    input.disabled = all;
                }
            });
        }

        reportRange.addEventListener('change', syncReportRange);
    </script>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <x-stat-card title="Total Tiket" tone="brand" value="{{ number_format($totalTickets) }}">
            <x-slot:icon><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></x-slot:icon>
        </x-stat-card>

        <x-stat-card title="Rata-rata Waktu Penyelesaian" tone="emerald" value="{{ $avgResolutionLabel ?? '—' }}">
            <x-slot:icon><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></x-slot:icon>
        </x-stat-card>

        <x-stat-card title="SLA Compliance" tone="{{ ($slaComplianceRate ?? 100) >= 90 ? 'emerald' : 'red' }}" value="{{ $slaComplianceRate !== null ? $slaComplianceRate.'%' : '—' }}">
            <x-slot:icon><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 002.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 012.916.52 6.003 6.003 0 01-5.395 4.972m0 0a6.726 6.726 0 01-2.749 1.35m0 0a6.772 6.772 0 01-3.044 0"/></x-slot:icon>
            <x-slot:meta>tiket selesai tepat waktu</x-slot:meta>
        </x-stat-card>

        <x-stat-card title="Status Terbanyak" tone="amber" value="{{ $byStatus->sortDesc()->keys()->first() ?? '—' }}">
            <x-slot:icon><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></x-slot:icon>
        </x-stat-card>
    </div>

    <x-card title="KPI Tim Teknis" class="mt-6">
        @if ($kpiByTechnician->isEmpty())
            <x-empty-state description="Belum ada aktivitas penugasan teknisi pada periode ini." />
        @else
            <div class="overflow-x-auto">
                <table class="w-full min-w-[720px] text-left text-sm">
                    <thead>
                        <tr class="border-b border-rule bg-paper/60 font-mono text-[10px] uppercase tracking-[0.14em] text-muted">
                            <th class="px-6 py-2.5 font-semibold">Teknisi</th>
                            <th class="px-6 py-2.5 text-right font-semibold">Ditangani</th>
                            <th class="px-6 py-2.5 text-right font-semibold">Eskalasi Diterima</th>
                            <th class="px-6 py-2.5 text-right font-semibold">Selesai</th>
                            <th class="px-6 py-2.5 text-right font-semibold">Rata-rata Resolusi</th>
                            <th class="px-6 py-2.5 text-right font-semibold">SLA Compliance</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-rule/70">
                        @foreach ($kpiByTechnician as $row)
                            <tr class="transition hover:bg-paper/70">
                                <td class="px-6 py-3">
                                    <span class="font-medium text-ink">{{ $row['name'] }}</span>
                                    @if ($row['expertise'])
                                        <span class="block font-mono text-[10px] uppercase tracking-wide text-faint">{{ $row['expertise'] }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-3 text-right font-mono tabular-nums text-ink">{{ $row['handled'] }}</td>
                                <td class="px-6 py-3 text-right font-mono tabular-nums {{ $row['escalations'] > 0 ? 'text-signal-600' : 'text-muted' }}">{{ $row['escalations'] }}</td>
                                <td class="px-6 py-3 text-right font-mono tabular-nums font-semibold text-ok-700">{{ $row['resolved'] }}</td>
                                <td class="px-6 py-3 text-right font-mono tabular-nums text-muted">
                                    {{ $row['avg_label'] ?? '—' }}
                                </td>
                                <td class="px-6 py-3 text-right font-mono tabular-nums font-semibold
                                    {{ $row['sla_rate'] === null ? 'text-faint' : ($row['sla_rate'] >= 90 ? 'text-ok-600' : 'text-critical-600') }}">
                                    {{ $row['sla_rate'] !== null ? number_format($row['sla_rate'], 1).'%' : '—' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <p class="mt-3 border-t border-dashed border-rule pt-3 text-xs leading-relaxed text-faint">
                Ditangani = tiket yang pernah ditugaskan pada periode ini. Selesai = atribusi ke teknisi yang memegang tiket saat resolved.
                Eskalasi diterima = menerima tiket yang sebelumnya sudah ditangani teknisi lain.
            </p>
        @endif
    </x-card>

    <div class="mt-6 grid max-w-6xl grid-cols-1 gap-6 lg:grid-cols-2">
        <x-card title="Tiket per Kategori">
            @if ($byCategory->isEmpty())
                <x-empty-state description="Tidak ada data pada periode ini." />
            @else
                @php($max = $byCategory->max('total'))
                <ul class="space-y-3.5">
                    @foreach ($byCategory as $row)
                        <li>
                            <div class="mb-1 flex items-center justify-between text-sm">
                                <span class="font-medium text-ink">{{ $row->category_name }}</span>
                                <span class="font-mono tabular-nums text-muted">{{ $row->total }} ({{ round($row->total / max($totalTickets, 1) * 100) }}%)</span>
                            </div>
                            <div class="h-2.5 overflow-hidden rounded-[2px] border border-rule bg-paper">
                                <div class="h-full border-r border-stamp-700 bg-stamp-500" style="width: {{ round($row->total / $max * 100) }}%"></div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </x-card>

        <x-card title="Distribusi Status & Prioritas">
            <p class="eyebrow mb-2">Status</p>
            <div class="flex flex-wrap gap-2">
                @foreach ($statuses as $status)
                    <span class="inline-flex items-center gap-2 rounded-[4px] border border-rule bg-sheet px-3 py-1.5">
                        <x-badge :value="$status" size="sm" />
                        <strong class="font-mono tabular-nums text-ink">{{ $byStatus[$status] ?? 0 }}</strong>
                    </span>
                @endforeach
            </div>

            <p class="eyebrow mb-2 mt-5">Prioritas</p>
            <div class="grid grid-cols-4 gap-3">
                @foreach (array_reverse($priorities) as $priority)
                    @php($count = $byPriority[$priority] ?? 0)
                    <div class="rounded-[4px] border border-rule p-3 text-center">
                        <p class="font-mono text-lg font-semibold tabular-nums text-ink">{{ $count }}</p>
                        <div class="mt-1.5"><x-badge :value="$priority" size="sm" /></div>
                    </div>
                @endforeach
            </div>
        </x-card>

        <x-card title="Tren Tiket per Bulan (6 bulan terakhir)" class="lg:col-span-2">
            @php($maxTrend = $monthlyTrend->max() ?: 1)
            <div class="flex h-44 items-end gap-3 px-2">
                @forelse ($monthlyTrend as $month => $count)
                    <div class="flex h-full min-w-0 flex-1 flex-col items-center justify-end gap-1.5">
                        <span class="font-mono text-xs font-semibold tabular-nums text-muted">{{ $count }}</span>
                        <div class="w-full rounded-t-[3px] border-x border-t border-stamp-800/40 bg-stamp-600"
                             style="height: {{ max(round($count / $maxTrend * 100), 3) }}%"></div>
                        <span class="font-mono text-[9px] font-medium uppercase tracking-wide text-faint">{{ \Carbon\Carbon::createFromFormat('Y-m', $month)->translatedFormat('M y') }}</span>
                    </div>
                @empty
                    <div class="w-full"><x-empty-state description="Belum ada data tren." /></div>
                @endforelse
            </div>
        </x-card>
    </div>
@endsection