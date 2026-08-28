@extends('layouts.admin')

@section('title', 'Audit Log')
@section('page_title', 'Audit Log')
@section('page_subtitle', 'Jejak aktivitas administrator dan perubahan status')

@section('content')
    <x-card :padded="false">
        <form method="GET" action="{{ route('admin.audit-logs.index') }}" class="flex flex-wrap items-end gap-3 border-b border-dashed border-rule bg-paper/50 p-4">
            <div class="w-40">
                <label class="label" for="log-action">Aksi</label>
                <select id="log-action" name="action" class="field appearance-none py-2 pr-8">
                    <option value="">Semua</option>
                    @foreach ($actions as $action)
                        <option value="{{ $action }}" @selected(($filters['action'] ?? '') === $action)>{{ ucfirst(str_replace('_', ' ', $action)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-40">
                <label class="label" for="log-entity">Objek</label>
                <select id="log-entity" name="entity_type" class="field appearance-none py-2 pr-8">
                    <option value="">Semua</option>
                    @foreach ($entityTypes as $type)
                        <option value="{{ $type }}" @selected(($filters['entity_type'] ?? '') === $type)>{{ $type }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="label" for="log-from">Dari</label>
                <input id="log-from" type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}" class="field py-2" />
            </div>
            <div>
                <label class="label" for="log-to">Sampai</label>
                <input id="log-to" type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}" class="field py-2" />
            </div>
            <x-button type="submit" variant="secondary" size="md">Filter</x-button>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[720px] text-left text-sm">
                <thead>
                    <tr class="border-b border-rule bg-paper/60 font-mono text-[10px] uppercase tracking-[0.14em] text-muted">
                        <th class="px-6 py-2.5 font-semibold">Waktu</th>
                        <th class="px-6 py-2.5 font-semibold">Aktor</th>
                        <th class="px-6 py-2.5 font-semibold">Aksi</th>
                        <th class="px-6 py-2.5 font-semibold">Objek</th>
                        <th class="px-6 py-2.5 font-semibold">Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-rule/70">
                    @forelse ($logs as $log)
                        <tr class="transition hover:bg-paper/70">
                            <td class="whitespace-nowrap px-6 py-3.5 font-mono text-xs tabular-nums text-muted">{{ $log->created_at->format('d M Y, H:i') }}</td>
                            <td class="px-6 py-3.5 font-medium text-ink">{{ $log->actor_name }}</td>
                            <td class="px-6 py-3.5">
                                <span class="chip {{ in_array($log->action, ['login', 'logout']) ? 'border-ink/25 bg-ink/[0.06] text-muted'
                                    : (str_contains($log->action, 'delete') ? 'border-critical-500/60 bg-critical-50 text-critical-700'
                                    : 'border-stamp-500/60 bg-stamp-50 text-stamp-800') }}">{{ str_replace('_', ' ', $log->action) }}</span>
                            </td>
                            <td class="px-6 py-3.5 font-mono text-xs text-muted">
                                {{ $log->entity_type }}
                                @if ($log->entity_id && $log->entity_type === 'Ticket')
                                    — <a href="{{ route('admin.tickets.show', $log->entity_id) }}" class="text-stamp-700 underline decoration-stamp-300 underline-offset-4 hover:text-stamp-800">#{{ $log->entity_id }}</a>
                                @endif
                            </td>
                            <td class="max-w-[280px] truncate px-6 py-3.5 font-mono text-xs text-faint">
                                @if ($old = $log->old_values)
                                    {{ json_encode($old) }}
                                    @if ($log->new_values)
                                        &rarr; {{ json_encode($log->new_values) }}
                                    @endif
                                @elseif ($log->new_values)
                                    {{ json_encode($log->new_values) }}
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-10"><x-empty-state description="Belum ada aktivitas tercatat." /></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-dashed border-rule px-6 py-3">
            {{ $logs->links() }}
        </div>
    </x-card>
@endsection
