<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TelegramSetting extends Model
{
    public const DEFAULT_TEMPLATE_NEW_TICKET = <<<'TPL'
    Tiket Baru: {ticket_number}
    Requester: {requester_name}
    Departemen: {department}
    Kategori: {category}
    Prioritas: {priority}
    Status: {status}
    Dibuat: {created_at}
    SLA Due: {sla_due_at}
    TPL;

    public const DEFAULT_TEMPLATE_SLA_ALERT = <<<'TPL'
    [SLA ALERT] {ticket_number}
    Requester: {requester_name}
    Departemen: {department}
    Kategori: {category}
    Prioritas: {priority}
    Status: {status}
    Dibuat: {created_at}
    SLA Due: {sla_due_at}
    TPL;

    /** @var list<string> */
    public const TEMPLATE_VARIABLES = [
        '{ticket_number}',
        '{category}',
        '{priority}',
        '{requester_name}',
        '{department}',
        '{status}',
        '{sla_due_at}',
        '{created_at}',
    ];

    protected $fillable = [
        'bot_token',
        'chat_id',
        'message_template_new_ticket',
        'message_template_sla_alert',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'bot_token' => 'encrypted',
        ];
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public static function current(): ?self
    {
        return static::query()->first();
    }

    public function maskedBotToken(): string
    {
        $token = (string) $this->bot_token;

        if ($token === '') {
            return '';
        }

        if (strlen($token) <= 8) {
            return str_repeat('*', strlen($token));
        }

        return substr($token, 0, 4).str_repeat('*', max(strlen($token) - 8, 4)).substr($token, -4);
    }
}
