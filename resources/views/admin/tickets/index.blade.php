@extends('layouts.admin')

@section('title', 'Daftar Tiket')
@section('page_title', 'Tiket')
@section('page_subtitle', 'Kelola dan pantau seluruh tiket masuk')

@section('content')
    <x-card :padded="false">
        <form method="GET" action="{{ route('admin.tickets.index') }}" class="flex flex-wrap items-end gap-3 border-b border-dashed border-rule bg-paper/50 p-4">
            <div class="min-w-[200px] flex-1">
                <label class="label" for="filter-q">Cari</label>
                <input id="filter-q" type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="No. tiket / nama pelapor…" class="field py-2" />
            </div>

            <div class="w-36">
                <label class="label" for="filter-status">Status</label>
                <select id="filter-status" name="status" class="field appearance-none py-2 pr-8">
                    <option value="">Semua</option>
                    @foreach ($statuses as $s)
                        <option value="{{ $s }}" @selected(($filters['status'] ?? '') === $s)>{{ $s }}</option>
                    @endforeach
                </select>
            </div>

            <div class="w-32">
                <label class="label" for="filter-priority">Prioritas</label>
                <select id="filter-priority" name="priority" class="field appearance-none py-2 pr-8">
                    <option value="">Semua</option>
                    @foreach ($priorities as $p)
                        <option value="{{ $p }}" @selected(($filters['priority'] ?? '') === $p)>{{ $p }}</option>
                    @endforeach
                </select>
            </div>

            <div class="w-40">
                <label class="label" for="filter-category">Kategori</label>
                <select id="filter-category" name="category_id" class="field appearance-none py-2 pr-8">
                    <option value="">Semua</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected((string) ($filters['category_id'] ?? '') === (string) $category->id)>{{ $category->category_name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="label" for="filter-from">Dari</label>
                <input id="filter-from" type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}" @disabled(($filters['period_mode'] ?? 'custom') !== 'custom') class="field py-2" />
            </div>

            <div>
                <label class="label" for="filter-to">Sampai</label>
                <input id="filter-to" type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}" @disabled(($filters['period_mode'] ?? 'custom') !== 'custom') class="field py-2" />
            </div>

            <div>
                <label class="label" for="filter-period-mode">Mode Periode</label>
                <select id="filter-period-mode" name="period_mode" class="field appearance-none py-2 pr-8">
                    @foreach (['custom' => 'Custom', 'day' => 'Harian', 'month' => 'Bulanan', 'year' => 'Tahunan'] as $value => $label)
                        <option value="{{ $value }}" @selected(($filters['period_mode'] ?? 'custom') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div data-period-input="day" @if (($filters['period_mode'] ?? 'custom') !== 'day') hidden @endif>
                <label class="label" for="filter-period-date">Tanggal</label>
                <input id="filter-period-date" type="date" name="period_date" value="{{ $filters['period_date'] ?? '' }}" @disabled(($filters['period_mode'] ?? '') !== 'day') class="field py-2" />
            </div>

            <div data-period-input="month" @if (($filters['period_mode'] ?? 'custom') !== 'month') hidden @endif>
                <label class="label" for="filter-period-month">Bulan</label>
                <input id="filter-period-month" type="month" name="period_month" value="{{ $filters['period_month'] ?? '' }}" @disabled(($filters['period_mode'] ?? '') !== 'month') class="field py-2" />
            </div>

            <div data-period-input="year" @if (($filters['period_mode'] ?? 'custom') !== 'year') hidden @endif>
                <label class="label" for="filter-period-year">Tahun</label>
                <input id="filter-period-year" type="number" min="2000" max="2100" name="period_year" value="{{ $filters['period_year'] ?? '' }}" placeholder="{{ now()->year }}" @disabled(($filters['period_mode'] ?? '') !== 'year') class="field py-2" />
            </div>

            <x-button type="submit" variant="secondary" size="md">Filter</x-button>
            <a href="{{ route('admin.tickets.index') }}" class="px-2 py-2 font-mono text-[10px] font-semibold uppercase tracking-wide text-faint hover:text-muted">Reset</a>

            <span class="mx-1 h-5 w-px bg-rule"></span>

            <a href="{{ route('admin.tickets.export', array_filter(request()->query())) }}"
                class="px-2 py-2 font-mono text-[10px] font-semibold uppercase tracking-wide text-stamp-700 underline decoration-stamp-300 underline-offset-4 hover:text-stamp-900">Ekspor CSV &darr;</a>
            <button type="button" onclick="openDialog('ticket-import-dialog')"
                class="px-2 py-2 font-mono text-[10px] font-semibold uppercase tracking-wide text-stamp-700 underline decoration-stamp-300 underline-offset-4 hover:text-stamp-900">Impor CSV &uarr;</button>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[1000px] text-left text-sm">
                <thead>
                    <tr class="border-b border-rule bg-paper/60 font-mono text-[10px] uppercase tracking-[0.14em] text-muted">
                        <th class="px-6 py-2.5 font-semibold">No. Tiket</th>
                        <th class="px-6 py-2.5 font-semibold">Pelapor</th>
                        <th class="px-6 py-2.5 font-semibold">Departemen</th>
                        <th class="px-6 py-2.5 font-semibold">Kategori</th>
                        <th class="px-6 py-2.5 font-semibold">Prioritas</th>
                        <th class="px-6 py-2.5 font-semibold">Status</th>
                        <th class="px-6 py-2.5 font-semibold">Teknisi</th>
                        <th class="px-6 py-2.5 font-semibold">SLA Due</th>
                        <th class="px-6 py-2.5 text-right font-semibold"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-rule/70">
                    @forelse ($tickets as $ticket)
                        <tr class="transition hover:bg-paper/70 {{ $ticket->isOverdue() ? 'bg-critical-50/50' : '' }}">
                            <td class="whitespace-nowrap px-6 py-3.5">
                                <a href="{{ route('admin.tickets.show', $ticket) }}" class="font-mono text-[13px] font-semibold text-stamp-800 underline decoration-stamp-200 underline-offset-4 hover:text-stamp-600">{{ $ticket->ticket_number }}</a>
                                <span class="block font-mono text-[10px] uppercase tracking-wide text-faint">{{ $ticket->created_at->format('d M Y, H.i') }}</span>
                            </td>
                            <td class="max-w-[160px] truncate px-6 py-3.5 font-medium text-ink">{{ $ticket->requester_name }}</td>
                            <td class="px-6 py-3.5 text-muted">{{ $ticket->department ?? '—' }}</td>
                            <td class="px-6 py-3.5 text-muted">{{ $ticket->category?->category_name }}</td>
                            <td class="px-6 py-3.5"><x-badge :value="$ticket->priority" size="sm" /></td>
                            <td class="px-6 py-3.5"><x-badge :value="$ticket->status" size="sm" /></td>
                            <td class="whitespace-nowrap px-6 py-3.5 text-muted">
                                {{ $ticket->assignedTechnician?->technician_name ?? '—' }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-3.5 font-mono text-xs tabular-nums">
                                @if ($ticket->isOverdue())
                                    <span class="font-semibold text-critical-600">{{ $ticket->sla_due_at->format('d M H:i') }} — lewat</span>
                                @elseif ($ticket->isActive())
                                    <span class="text-muted">{{ $ticket->sla_due_at->format('d M H:i') }}</span>
                                @else
                                    <span class="text-faint">{{ $ticket->sla_due_at?->format('d M H:i') }}</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-6 py-3.5 text-right">
                                <a href="{{ route('admin.tickets.show', $ticket) }}" class="font-mono text-[10px] font-semibold uppercase tracking-wide text-stamp-700 underline decoration-stamp-300 underline-offset-4 hover:text-stamp-800">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="px-6 py-10"><x-empty-state description="Tidak ada tiket yang cocok dengan filter." /></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-dashed border-rule px-6 py-3">
            {{ $tickets->links() }}
        </div>
    </x-card>

    <x-modal-dialog id="ticket-import-dialog" title="Impor Tiket (CSV)">
        <form method="POST" action="{{ route('admin.tickets.import') }}" enctype="multipart/form-data" data-disable-on-submit class="space-y-4">
            @csrf

            <p class="text-xs leading-relaxed text-muted">
                Dua format didukung: <strong class="text-ink">hasil Ekspor CSV</strong> aplikasi ini, atau
                <strong class="text-ink">CSV Helpdesk lama</strong> (kolom TicketID, Tanggal dd/mm/yyyy, NamaLengkap, Kategori, Kendala, Priority, Status, Teknisi).
                Baris dengan No. Tiket yang sudah ada <strong class="text-ink">dilewati</strong>;
                format legacy mempertahankan status &amp; tanggal, SLA Due dihitung otomatis dari tanggal tiket dibuat,
                dan nama teknisi dinormalisasi ke master Teknisi.
            </p>

            <input type="file" name="file" accept=".csv,text/csv" required
                class="field w-full py-2 file:mr-3 file:rounded-[4px] file:border-0 file:bg-stamp-50 file:px-3 file:py-1.5 file:font-mono file:text-[10px] file:font-semibold file:uppercase file:tracking-wide file:text-stamp-800" />

            @error('file')
                <p class="font-mono text-[11px] uppercase tracking-wide text-critical-600">{{ $message }}</p>
            @enderror

            <div class="flex justify-end gap-2 pt-1">
                <x-button variant="secondary" size="sm" type="button" onclick="closeDialog('ticket-import-dialog')">Batal</x-button>
                <x-button size="sm" type="submit">Impor Sekarang</x-button>
            </div>
        </form>
    </x-modal-dialog>

    <script>
        const periodMode = document.getElementById('filter-period-mode');

        function syncPeriodInputs() {
            document.querySelectorAll('[data-period-input]').forEach((el) => {
                const active = el.dataset.periodInput === periodMode.value;
                el.hidden = !active;
                const field = el.querySelector('input');
                if (field) {
                    field.disabled = !active;
                }
            });

            const custom = periodMode.value === 'custom';
            ['filter-from', 'filter-to'].forEach((id) => {
                const input = document.getElementById(id);
                if (input) {
                    input.disabled = !custom;
                }
            });
        }

        periodMode.addEventListener('change', syncPeriodInputs);
    </script>
@endsection
