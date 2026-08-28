@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'Ringkasan aktivitas tiket hari ini')

@section('content')
    <div class="space-y-6">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <x-stat-card title="Tiket Open" tone="brand" value="{{ $openCount }}">
                <x-slot:icon>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </x-slot:icon>
                <x-slot:meta>menunggu ditangani</x-slot:meta>
            </x-stat-card>

            <x-stat-card title="In Progress" tone="amber" value="{{ $inProgressCount }}">
                <x-slot:icon>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                </x-slot:icon>
                <x-slot:meta>sedang dikerjakan</x-slot:meta>
            </x-stat-card>

            <x-stat-card title="Melewati SLA" tone="red" value="{{ $overdueCount }}">
                <x-slot:icon>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                </x-slot:icon>
                <x-slot:meta>butuh perhatian segera</x-slot:meta>
            </x-stat-card>

            <x-stat-card title="Resolved Minggu Ini" tone="emerald" value="{{ $resolvedThisWeek }}">
                <x-slot:icon>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </x-slot:icon>
                <x-slot:meta>sejak Senin</x-slot:meta>
            </x-stat-card>
        </div>

        @if ($recentTickets->isNotEmpty())
            <x-card :padded="false" title="Tiket Terbaru">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[640px] text-left text-sm">
                        <thead>
                            <tr class="border-b border-rule bg-paper/60 font-mono text-[10px] uppercase tracking-[0.14em] text-muted">
                                <th class="px-6 py-2.5 font-semibold">No. Tiket</th>
                                <th class="px-6 py-2.5 font-semibold">Pelapor</th>
                                <th class="px-6 py-2.5 font-semibold">Kategori</th>
                                <th class="px-6 py-2.5 font-semibold">Prioritas</th>
                                <th class="px-6 py-2.5 font-semibold">Status</th>
                                <th class="px-6 py-2.5 text-right font-semibold"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-rule/70">
                            @foreach ($recentTickets as $ticket)
                                <tr class="transition hover:bg-paper/70">
                                    <td class="whitespace-nowrap px-6 py-3.5">
                                        <span class="font-mono text-[13px] font-semibold text-stamp-800">{{ $ticket->ticket_number }}</span>
                                        <span class="block font-mono text-[10px] uppercase tracking-wide text-faint">{{ $ticket->created_at->format('d M Y, H.i') }}</span>
                                    </td>
                                    <td class="max-w-[160px] truncate px-6 py-3.5 font-medium text-ink">{{ $ticket->requester_name }}</td>
                                    <td class="px-6 py-3.5 text-muted">{{ $ticket->category?->category_name }}</td>
                                    <td class="px-6 py-3.5"><x-badge :value="$ticket->priority" size="sm" /></td>
                                    <td class="px-6 py-3.5"><x-badge :value="$ticket->status" size="sm" /></td>
                                    <td class="whitespace-nowrap px-6 py-3.5 text-right">
                                        <a href="{{ route('admin.tickets.show', $ticket) }}" class="font-mono text-[10px] font-semibold uppercase tracking-wide text-stamp-700 underline decoration-stamp-300 underline-offset-4 hover:text-stamp-800">Detail</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-dashed border-rule px-6 py-3 text-right">
                    <a href="{{ route('admin.tickets.index') }}" class="font-mono text-[10px] font-semibold uppercase tracking-[0.14em] text-stamp-700 hover:text-stamp-800">Lihat semua tiket &rarr;</a>
                </div>
            </x-card>
        @endif
    </div>
@endsection
