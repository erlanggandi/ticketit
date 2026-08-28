<?php

namespace App\Services;

use App\Models\AdminNotification;
use App\Models\TelegramSetting;
use App\Models\Ticket;
use Illuminate\Support\Facades\Log;

class AdminNotificationService
{
    public function __construct(
        private readonly TelegramService $telegram,
    ) {}

    public function notifyNewTicket(Ticket $ticket): void
    {
        $this->dispatch(
            type: AdminNotification::TYPE_NEW_TICKET,
            title: "Tiket baru {$ticket->ticket_number}",
            body: "{$ticket->requester_name} melaporkan [{$ticket->priority}] {$ticket->category?->category_name}",
            ticket: $ticket,
            url: route('admin.tickets.show', $ticket),
            template: $this->templateFor(TelegramSetting::current(), 'message_template_new_ticket'),
        );
    }

    public function notifySlaApproaching(Ticket $ticket): void
    {
        $this->dispatch(
            type: AdminNotification::TYPE_SLA_APPROACHING,
            title: "SLA mendekati batas: {$ticket->ticket_number}",
            body: "[{$ticket->priority}] {$ticket->category?->category_name} — due ".optional($ticket->sla_due_at)->format('d M Y H:i'),
            ticket: $ticket,
            url: route('admin.tickets.show', $ticket),
            template: $this->templateFor(TelegramSetting::current(), 'message_template_sla_alert'),
        );
    }

    public function notifySlaBreach(Ticket $ticket): void
    {
        $this->dispatch(
            type: AdminNotification::TYPE_SLA_BREACH,
            title: "SLA terlampaui: {$ticket->ticket_number}",
            body: "[{$ticket->priority}] {$ticket->category?->category_name} — due ".optional($ticket->sla_due_at)->format('d M Y H:i'),
            ticket: $ticket,
            url: route('admin.tickets.show', $ticket),
            template: $this->templateFor(TelegramSetting::current(), 'message_template_sla_alert'),
        );
    }

    private function dispatch(string $type, string $title, string $body, Ticket $ticket, string $url, string $template): void
    {
        try {
            AdminNotification::create([
                'type' => $type,
                'title' => $title,
                'body' => $body,
                'data' => ['ticket_id' => $ticket->id, 'url' => $url],
            ]);

            if ($template !== '') {
                $message = $this->telegram->renderTemplate($template, $ticket)."\n\n".$url;
                [$ok] = $this->telegram->send(TelegramSetting::current(), $message);

                if (! $ok) {
                    Log::warning("Telegram notification for {$ticket->ticket_number} ({$type}) was not delivered.");
                }
            }
        } catch (\Throwable $e) {
            Log::error("Failed dispatching admin notification [{$type}]: ".$e->getMessage());
        }
    }

    private function templateFor(?TelegramSetting $setting, string $field): string
    {
        return $setting?->{$field} ?? '';
    }
}
