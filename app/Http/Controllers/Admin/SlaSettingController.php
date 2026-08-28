<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SlaSettingRequest;
use App\Models\SlaSetting;
use App\Models\Ticket;
use App\Services\AuditLogger;
use App\Services\SlaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SlaSettingController extends Controller
{
    public function edit(): View
    {
        $settings = SlaSetting::query()->pluck('duration_hours', 'priority');

        $durations = collect(Ticket::PRIORITIES)
            ->mapWithKeys(fn ($priority) => [
                $priority => $settings[$priority] ?? (int) config("sla.fallback_durations.{$priority}"),
            ]);

        return view('admin.sla-settings.edit', ['durations' => $durations]);
    }

    public function update(SlaSettingRequest $request, AuditLogger $audit, SlaService $slaService): RedirectResponse
    {
        $old = SlaSetting::query()->pluck('duration_hours', 'priority')->all();
        $durations = $request->durationsByPriority();

        foreach ($durations as $priority => $hours) {
            SlaSetting::query()->updateOrCreate(
                ['priority' => $priority],
                ['duration_hours' => $hours]
            );
        }

        $changedPriorities = collect($durations)
            ->filter(fn ($hours, $priority) => $this->effectiveOldHours($old, $priority) !== $hours)
            ->keys()
            ->all();

        if ($changedPriorities !== []) {
            Ticket::query()
                ->whereIn('status', [Ticket::STATUS_OPEN, Ticket::STATUS_IN_PROGRESS])
                ->whereIn('priority', $changedPriorities)
                ->chunkById(100, function ($tickets) use ($slaService): void {
                    foreach ($tickets as $ticket) {
                        $ticket->forceFill([
                            'sla_due_at' => $slaService->calculateDueDate($ticket->priority, $ticket->created_at),
                            'sla_alert_approaching_sent_at' => null,
                            'sla_alert_breach_sent_at' => null,
                        ])->save();
                    }
                });
        }

        $audit->recordAction('sla_settings_updated');

        return back()->with('success', 'Pengaturan SLA berhasil disimpan.');
    }

    private function effectiveOldHours(array $old, string $priority): int
    {
        return (int) ($old[$priority] ?? config("sla.fallback_durations.{$priority}", 24));
    }
}
