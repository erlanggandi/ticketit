@extends('layouts.admin')

@section('title', 'Detail Tiket')
@section('page_title', $ticket->ticket_number)
@section('page_subtitle', 'Detail dan penanganan tiket')

@section('content')
    <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
        <div class="space-y-6 xl:col-span-2">
            <x-card>
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div>
                        <p class="eyebrow text-faint">Nomor tiket</p>
                        <p class="mt-1 select-all font-mono text-xl font-semibold tracking-tight text-ink">{{ $ticket->ticket_number }}</p>
                        <p class="mt-1 font-mono text-[10px] uppercase tracking-wider text-muted">Dibuat {{ $ticket->created_at->format('d M Y, H.i') }}</p>
                    </div>
                    <div class="flex items-center gap-2 pt-1">
                        @if ($ticket->isOverdue())
                            <span class="chip border-critical-500 bg-critical-500 text-white">Melewati SLA</span>
                        @endif
                        <x-badge :value="$ticket->status" />
                    </div>
                </div>

                <dl class="mt-5 grid grid-cols-2 gap-x-6 gap-y-4 border-t border-dashed border-rule pt-5 sm:grid-cols-5">
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
                        <dt class="font-mono text-[9px] uppercase tracking-[0.18em] text-faint">Prioritas</dt>
                        <dd class="mt-1"><x-badge :value="$ticket->priority" size="sm" /></dd>
                    </div>
                    <div>
                        <dt class="font-mono text-[9px] uppercase tracking-[0.18em] text-faint">SLA Due</dt>
                        <dd class="mt-1 font-mono text-sm font-semibold tabular-nums {{ $ticket->isOverdue() ? 'text-critical-600' : 'text-ink' }}">
                            {{ $ticket->sla_due_at->format('d M, H.i') }}
                        </dd>
                    </div>
                </dl>

                <div class="mt-5 rounded-[5px] border border-dashed border-rule bg-paper/60 p-4">
                    <dt class="font-mono text-[9px] uppercase tracking-[0.18em] text-muted">Deskripsi Masalah</dt>
                    <p class="mt-2 whitespace-pre-line text-sm leading-relaxed text-ink">{{ $ticket->description }}</p>
                </div>

                @if ($ticket->resolved_at || $ticket->closed_at)
                    <div class="mt-5 flex flex-wrap items-center gap-x-8 gap-y-2 border-t border-dashed border-rule pt-4 font-mono text-xs tabular-nums">
                        @if ($ticket->resolved_at)
                            <span class="text-muted">Resolved: <strong class="text-ok-700">{{ $ticket->resolved_at->format('d M Y, H.i') }}</strong></span>
                            @php($compliant = $ticket->slaCompliant())
                            <span class="{{ $compliant ? 'text-ok-600' : 'text-critical-600' }} font-semibold uppercase tracking-wide">
                                {{ $compliant ? 'Sesuai SLA' : 'Melebihi target SLA ('.$ticket->created_at->diffInHours($ticket->resolved_at).' jam)' }}
                            </span>
                        @endif
                        @if ($ticket->closed_at)
                            <span class="text-muted">Closed: <strong class="text-ink">{{ $ticket->closed_at->format('d M Y, H.i') }}</strong></span>
                        @endif
                    </div>
                @endif
            </x-card>

            <x-card title="Riwayat Aktivitas (Audit Trail)">
                @forelse ($trail as $log)
                    <div class="relative pb-5 pl-7 last:pb-0">
                        <span class="absolute left-0 top-1 flex h-3.5 w-3.5 items-center justify-center rounded-[2px] ring-4 ring-sheet
                            {{ str_contains($log->action, 'status') ? 'bg-stamp-600' : 'bg-rule' }}"></span>
                        <span class="absolute left-[6.5px] top-5 h-full w-px bg-rule last:hidden"></span>
                        <p class="font-mono text-[13px] font-semibold capitalize text-ink">{{ str_replace('_', ' ', $log->action) }}
                            <span class="ml-1 text-[10px] font-normal normal-case tracking-wide text-faint">{{ $log->created_at->format('d M Y, H:i') }}</span>
                        </p>
                        <p class="text-xs text-muted">oleh {{ $log->actor_name ?? 'System' }}</p>

                        @if ($log->old_values && $log->new_values && isset($log->old_values['status']))
                            <p class="mt-1 font-mono text-xs text-muted">{{ $log->old_values['status'] }} &rarr; <strong class="text-ink">{{ $log->new_values['status'] }}</strong></p>
                        @elseif ($log->new_values && isset($log->new_values['priority']))
                            <p class="mt-1 font-mono text-xs text-muted">Prioritas: {{ $log->old_values['priority'] ?? '-' }} &rarr; <strong class="text-ink">{{ $log->new_values['priority'] }}</strong></p>
                        @endif
                    </div>
                @empty
                    <x-empty-state title="Belum ada aktivitas" />
                @endforelse
            </x-card>

            <x-card title="Ubah Status">
                @if ($ticket->isClosed())
                    <div class="flex items-start gap-3 rounded-[5px] border border-dashed border-rule bg-paper/60 p-4 text-sm leading-relaxed text-muted">
                        <svg class="mt-0.5 h-5 w-5 shrink-0 text-faint" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                        </svg>
                        <span>Tiket ini sudah <strong class="text-ink">Closed</strong> dan tidak dapat diubah lagi sesuai aturan bisnis.</span>
                    </div>
                @else
                    <form method="POST" action="{{ route('admin.tickets.updateStatus', $ticket) }}" data-disable-on-submit>
                        @csrf
                        @method('PATCH')

                        <div class="mb-3 flex items-center justify-between rounded-[4px] border border-stamp-200 bg-stamp-50 px-3 py-2 font-mono text-[10px] font-medium uppercase tracking-wide text-stamp-800">
                            <span>Alur: Open &rarr; In Progress &rarr; Resolved &rarr; Closed</span>
                        </div>

                        <div class="flex flex-wrap gap-2">
                            @foreach ($nextStatuses as $next)
                                <button type="submit" name="status" value="{{ $next }}"
                                    class="btn btn-sm {{ $next === 'Resolved' ? 'btn-dark border-ok-600 bg-ok-500 hover:bg-ok-600' : ($next === 'Closed' ? 'btn-dark' : ($next === 'In Progress' ? 'btn-primary' : 'btn-secondary')) }}">
                                    {{ $next === 'In Progress' ? 'Mulai Kerjakan' : $next }}
                                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                                </button>
                            @endforeach
                            @if (empty($nextStatuses))
                                <p class="text-sm text-faint">Tidak ada transisi tersedia.</p>
                            @endif
                        </div>
                    </form>
                @endif
            </x-card>

            <x-card title="Tim Teknis & Eskalasi">
                @php($activeAssignment = $ticket->assignments->first(fn ($a) => $a->unassigned_at === null))

                @if ($ticket->isClosed())
                    <p class="font-mono text-xs uppercase tracking-wide text-faint">Tidak dapat diubah — tiket Closed.</p>
                @else
                    <form method="POST" action="{{ route('admin.tickets.assign', $ticket) }}" data-disable-on-submit class="space-y-3">
                        @csrf
                        @method('PATCH')

                        <div class="flex items-center justify-between rounded-[4px] border border-rule bg-paper/60 px-3 py-2">
                            <span class="font-mono text-[10px] uppercase tracking-wide text-muted">Penanggung jawab saat ini</span>
                            @if ($activeAssignment)
                                <span class="chip border-ok-500/60 bg-ok-50 text-ok-700">{{ $activeAssignment->technician?->technician_name }}</span>
                            @else
                                <span class="font-mono text-[10px] uppercase tracking-wide text-faint">Belum ditugaskan</span>
                            @endif
                        </div>

                        <x-select name="technician_id" label="Tugaskan / Eskalasi ke teknisi" :required="true"
                                  :options="$technicians->pluck('technician_name', 'id')" placeholder="— Pilih teknisi aktif —"
                                  hint="Menugaskan ulang akan menutup riwayat penanganan sebelumnya." />

                        <x-button type="submit" size="sm" variant="secondary">
                            {{ $activeAssignment ? 'Eskalasi ke Teknisi Terpilih' : 'Tugaskan Sekarang' }}
                        </x-button>
                    </form>

                    @if ($ticket->assignments->isNotEmpty())
                        <div class="border-t border-dashed border-rule pt-4">
                            <p class="eyebrow mb-2">Riwayat penanganan</p>
                            <ul class="space-y-1.5 font-mono text-xs tabular-nums text-muted">
                                @foreach ($ticket->assignments as $assignment)
                                    <li class="flex items-center justify-between gap-3">
                                        <span class="font-semibold text-ink">{{ $assignment->technician?->technician_name ?? '(teknisi dihapus)' }}</span>
                                        <span class="text-faint">{{ $assignment->assigned_at->format('d M H.i') }} &rarr; {{ $assignment->unassigned_at?->format('d M H.i') ?? 'sekarang' }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                @endif
            </x-card>

            <x-card title="Override Prioritas & Kategori">
                @if ($ticket->isClosed())
                    <p class="font-mono text-xs uppercase tracking-wide text-faint">Tidak dapat diubah — tiket Closed.</p>
                @else
                    <form method="POST" action="{{ route('admin.tickets.updateDetails', $ticket) }}" data-disable-on-submit class="space-y-4">
                        @csrf
                        @method('PATCH')

                        <x-select name="priority" label="Prioritas" :required="true"
                                  :options="array_combine($priorities, $priorities)" :selected="$ticket->priority"
                                  hint="Jika prioritas berubah, SLA due date dihitung ulang dari waktu tiket dibuat." />

                        <x-select name="category_id" label="Kategori" :required="true"
                                  :options="$categories->pluck('category_name', 'id')" :selected="$ticket->category_id" />

                        <x-button type="submit" size="sm">Simpan Perubahan</x-button>
                    </form>
                @endif
            </x-card>

            <x-card title="Info SLA">
                <ul class="space-y-2.5 font-mono text-[13px] tabular-nums">
                    <li class="flex justify-between gap-4"><span class="text-faint">Due date</span><span class="font-medium text-ink">{{ $ticket->sla_due_at->format('d M Y, H.i') }}</span></li>
                    <li class="flex justify-between gap-4">
                        <span class="text-faint">Sisa waktu</span>
                        @if ($ticket->isActive())
                            <span class="font-semibold {{ $ticket->isOverdue() ? 'text-critical-600' : 'text-ok-600' }}">{{ now()->diffForHumans($ticket->sla_due_at, ['parts' => 2]) }}</span>
                        @else
                            <span class="text-faint">selesai / ditutup</span>
                        @endif
                    </li>
                    <li class="flex justify-between gap-4"><span class="text-faint">Alert mendekati terkirim</span><span class="font-medium text-ink">{{ $ticket->sla_alert_approaching_sent_at ? 'Ya' : 'Belum' }}</span></li>
                    <li class="flex justify-between gap-4"><span class="text-faint">Alert breach terkirim</span><span class="font-medium text-ink">{{ $ticket->sla_alert_breach_sent_at ? 'Ya' : 'Belum' }}</span></li>
                </ul>
            </x-card>
        </div>

    <div class="mt-6">
        <a href="{{ route('admin.tickets.index') }}" class="inline-flex items-center gap-1.5 font-mono text-[11px] font-semibold uppercase tracking-wide text-muted hover:text-ink">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
            Kembali ke daftar tiket
        </a>
    </div>
@endsection