<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Technician;
use App\Models\Ticket;
use App\Models\TicketAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $rangeAll = $request->input('range') === 'all';

        $from = $request->input('date_from', now()->startOfMonth()->toDateString());
        $to = $request->input('date_to', now()->toDateString());

        if ($rangeAll) {
            $from = null;
            $to = null;
            $periodLabel = 'Semua Periode';
        } else {
            $periodLabel = Carbon::parse($from)->translatedFormat('d M Y').' – '.Carbon::parse($to)->translatedFormat('d M Y');
        }

        $base = Ticket::query()
            ->when($from !== null, fn ($query) => $query->whereDate('tickets.created_at', '>=', $from))
            ->when($to !== null, fn ($query) => $query->whereDate('tickets.created_at', '<=', $to));

        $totalTickets = (clone $base)->count();

        $byStatus = (clone $base)
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $byCategory = (clone $base)
            ->join('categories', 'categories.id', '=', 'tickets.category_id')
            ->selectRaw('categories.category_name, count(*) as total')
            ->groupBy('categories.category_name')
            ->orderByDesc('total')
            ->get();

        $byPriority = (clone $base)
            ->selectRaw('priority, count(*) as total')
            ->groupBy('priority')
            ->pluck('total', 'priority');

        $resolvedInRange = (clone $base)
            ->whereNotNull('resolved_at')
            ->get(['created_at', 'resolved_at', 'sla_due_at']);

        $avgResolutionMinutes = $resolvedInRange->isNotEmpty()
            ? (int) round($resolvedInRange->avg(fn ($t) => $t->created_at->diffInMinutes($t->resolved_at)))
            : null;

        $compliantCount = $resolvedInRange->filter(
            fn ($t) => $t->resolved_at && $t->sla_due_at && $t->resolved_at->lessThanOrEqualTo($t->sla_due_at)
        )->count();

        $slaComplianceRate = $resolvedInRange->isNotEmpty()
            ? round($compliantCount / $resolvedInRange->count() * 100, 1)
            : null;

        // Agregasi bulanan dilakukan di sisi aplikasi agar portabel lintas driver database.
        $createdAtInRange = Ticket::query()
            ->whereDate('created_at', '>=', now()->subMonths(5)->startOfMonth()->toDateString())
            ->pluck('created_at');

        $monthlyTrend = collect(range(5, 0))
            ->mapWithKeys(function (int $back) use ($createdAtInRange): array {
                $key = now()->subMonths($back)->format('Y-m');

                return [$key => $createdAtInRange->filter(fn ($date) => $date->format('Y-m') === $key)->count()];
            });

        return view('admin.reports.index', [
            'from' => $from,
            'to' => $to,
            'range' => $rangeAll ? 'all' : 'custom',
            'periodLabel' => $periodLabel,
            'totalTickets' => $totalTickets,
            'byStatus' => $byStatus,
            'byCategory' => $byCategory,
            'byPriority' => $byPriority,
            'avgResolutionMinutes' => $avgResolutionMinutes,
            'avgResolutionLabel' => $this->formatDuration($avgResolutionMinutes),
            'slaComplianceRate' => $slaComplianceRate,
            'monthlyTrend' => $monthlyTrend,
            'kpiByTechnician' => $this->technicianKpis($from, $to),
            'statuses' => Ticket::STATUSES,
            'priorities' => Ticket::PRIORITIES,
        ]);
    }

    /**
     * KPI per teknisi pada rentang tanggal tiket dibuat.
     * - Ditangani / Eskalasi diterima: dari aktivitas penugasan yang menjangkau periode.
     * - Selesai / Rata-rata resolusi / SLA: atribusi ke teknisi yang memegang
     *   tiket pada saat resolved_at (penanggung jawab akhir).
     *
     * @return Collection<int, array{name: string, expertise: ?string, handled: int, escalations: int, resolved: int, avg_resolution_minutes: ?int, sla_rate: ?float}>
     */
    private function technicianKpis(?string $from, ?string $to): Collection
    {
        $rows = [];

        // Aktivitas penugasan yang menjangkau periode (batas null = tanpa batas).
        $overlapsQuery = TicketAssignment::query()
            ->with('technician:id,technician_name,expertise');

        if ($to !== null) {
            $overlapsQuery->where('assigned_at', '<=', Carbon::parse($to)->endOfDay());
        }

        if ($from !== null) {
            $overlapsQuery->where(
                fn ($q) => $q->whereNull('unassigned_at')
                    ->orWhere('unassigned_at', '>=', Carbon::parse($from)->startOfDay())
            );
        }

        $overlaps = $overlapsQuery->get(['id', 'ticket_id', 'technician_id']);

        if ($overlaps->isNotEmpty()) {
            $firstAssignmentIds = TicketAssignment::query()
                ->whereIn('ticket_id', $overlaps->pluck('ticket_id')->unique())
                ->groupBy('ticket_id')
                ->selectRaw('min(id) as first_assignment_id')
                ->pluck('first_assignment_id');

            foreach ($overlaps as $assignment) {
                $this->ensureKpiRow($rows, $assignment->technician_id, $assignment->technician);

                $rows[$assignment->technician_id]['handledTickets'][$assignment->ticket_id] = true;

                if (! $firstAssignmentIds->contains($assignment->id)) {
                    $rows[$assignment->technician_id]['escalations']++;
                }
            }
        }

        // Atribusi tiket selesai: teknisi yang memegang saat resolved_at.
        $resolvedTickets = Ticket::query()
            ->whereNotNull('resolved_at')
            ->with(['assignments:id,ticket_id,technician_id,assigned_at'])
            ->get(['id', 'created_at', 'resolved_at', 'sla_due_at']);

        foreach ($resolvedTickets as $ticket) {
            $resolver = $ticket->assignments
                ->filter(fn ($a) => $a->assigned_at->lessThanOrEqualTo($ticket->resolved_at))
                ->sortBy([['assigned_at', 'desc'], ['id', 'desc']])
                ->first()?->technician_id;

            if ($resolver === null) {
                continue;
            }

            $this->ensureKpiRow($rows, $resolver);

            $minutes = (int) round($ticket->created_at->diffInMinutes($ticket->resolved_at));

            $rows[$resolver]['resolvedTickets'][] = [
                'compliant' => $ticket->sla_due_at !== null && $ticket->resolved_at->lessThanOrEqualTo($ticket->sla_due_at),
                'minutes' => $minutes,
            ];
        }

        return collect($rows)
            ->map(fn (array $row): array => [
                'name' => $row['name'],
                'expertise' => $row['expertise'],
                'handled' => count($row['handledTickets']),
                'escalations' => $row['escalations'],
                'resolved' => count($row['resolvedTickets']),
                'avg_resolution_minutes' => count($row['resolvedTickets']) > 0
                    ? (int) round(collect($row['resolvedTickets'])->avg('minutes'))
                    : null,
                'avg_label' => null,
                'sla_rate' => count($row['resolvedTickets']) > 0
                    ? round(collect($row['resolvedTickets'])->filter(fn (array $t): bool => $t['compliant'])->count() / count($row['resolvedTickets']) * 100, 1)
                    : null,
            ])
            ->map(function (array $row): array {
                $row['avg_label'] = $this->formatDuration($row['avg_resolution_minutes']);

                return $row;
            })
            ->sortBy([['resolved', 'desc'], ['handled', 'desc'], ['name', 'asc']])
            ->values();
    }

    /**
     * Pastikan baris agregasi KPI untuk satu teknisi sudah ada di $rows.
     *
     * @param  array<int, array<string, mixed>>  $rows
     */
    private function ensureKpiRow(array &$rows, int $technicianId, ?Technician $known = null): void
    {
        if (isset($rows[$technicianId])) {
            return;
        }

        $technician = $known ?? Technician::query()->find($technicianId);

        $rows[$technicianId] = [
            'name' => $technician?->technician_name ?? '(teknisi dihapus)',
            'expertise' => $technician?->expertise,
            'handledTickets' => [],
            'escalations' => 0,
            'resolvedTickets' => [],
        ];
    }

    private function formatDuration(?int $minutes): ?string
    {
        if ($minutes === null) {
            return null;
        }

        if ($minutes < 60) {
            return "{$minutes} menit";
        }

        $hours = floor($minutes / 60);
        $remaining = $minutes % 60;

        if ($hours < 24) {
            return $remaining > 0 ? "{$hours} jam {$remaining} mnt" : "{$hours} jam";
        }

        $days = floor($hours / 24);

        return "{$days} hari ".($hours % 24).' jam';
    }
}
