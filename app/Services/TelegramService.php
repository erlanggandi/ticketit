<?php

namespace App\Services;

use App\Models\TelegramSetting;
use App\Models\Ticket;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramService
{
    /**
     * @return array{0: bool, 1: string} [success, message]
     */
    public function send(TelegramSetting $setting, string $message): array
    {
        $token = trim((string) $setting->bot_token);
        $chatId = trim((string) $setting->chat_id);

        if ($token === '' || $chatId === '') {
            return [false, 'Bot Token atau Chat ID belum diisi.'];
        }

        try {
            $response = Http::timeout(10)
                ->retry(2, 300, throw: false)
                ->post("https://api.telegram.org/bot{$token}/sendMessage", [
                    'chat_id' => $chatId,
                    'text' => $message,
                ]);

            if ($response->failed()) {
                $description = $response->json('description') ?? "HTTP {$response->status()}";

                Log::warning('Telegram send failed', ['description' => $description]);

                return [false, "Gagal mengirim pesan: {$description}"];
            }

            return [true, 'Pesan berhasil terkirim ke Telegram.'];
        } catch (\Throwable $e) {
            Log::error('Telegram send exception', ['error' => $e->getMessage()]);

            return [false, 'Gagal terhubung ke API Telegram: '.$e->getMessage()];
        }
    }

    public function renderTemplate(string $template, Ticket $ticket): string
    {
        $replacements = [
            '{ticket_number}' => $ticket->ticket_number,
            '{category}' => $ticket->category?->category_name ?? '-',
            '{priority}' => $ticket->priority,
            '{requester_name}' => $ticket->requester_name,
            '{department}' => $ticket->department ?? '-',
            '{status}' => $ticket->status,
            '{sla_due_at}' => optional($ticket->sla_due_at)->format('d M Y H:i'),
            '{created_at}' => optional($ticket->created_at)->format('d M Y H:i'),
        ];

        return trim(str_replace(
            TelegramSetting::TEMPLATE_VARIABLES,
            array_values($replacements),
            $template
        ));
    }
}
