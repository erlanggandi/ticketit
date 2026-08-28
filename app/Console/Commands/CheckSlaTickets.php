<?php

namespace App\Console\Commands;

use App\Models\Ticket;
use App\Services\AdminNotificationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckSlaTickets extends Command
{
    protected $signature = 'tickets:check-sla';

    protected $description = 'Kirim alert SLA untuk tiket yang mendekati atau melewati due date';

    public function handle(AdminNotificationService $notifier): int
    {
        $thresholdMinutes = (int) config('sla.approach_threshold_minutes', 60);

        $approachingCount = 0;
        $breachCount = 0;

        Ticket::query()
            ->activeStatuses()
            ->whereNull('sla_alert_approaching_sent_at')
            ->whereBetween('sla_due_at', [now(), now()->addMinutes($thresholdMinutes)])
            ->chunkById(100, function ($tickets) use ($notifier, &$approachingCount) {
                foreach ($tickets as $ticket) {
                    $notifier->notifySlaApproaching($ticket);
                    $ticket->forceFill(['sla_alert_approaching_sent_at' => now()])->saveQuietly();
                    $approachingCount++;
                }
            });

        Ticket::query()
            ->activeStatuses()
            ->whereNull('sla_alert_breach_sent_at')
            ->where('sla_due_at', '<', now())
            ->chunkById(100, function ($tickets) use ($notifier, &$breachCount) {
                foreach ($tickets as $ticket) {
                    $notifier->notifySlaBreach($ticket);
                    $ticket->forceFill(['sla_alert_breach_sent_at' => now()])->saveQuietly();
                    $breachCount++;
                }
            });

        if ($approachingCount > 0 || $breachCount > 0) {
            Log::info("SLA check completed: {$approachingCount} approaching, {$breachCount} breached.");
        }

        $this->info("Approaching: {$approachingCount}, Breached: {$breachCount}");

        return self::SUCCESS;
    }
}
