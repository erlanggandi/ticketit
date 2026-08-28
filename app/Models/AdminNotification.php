<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class AdminNotification extends Model
{
    public const TYPE_NEW_TICKET = 'new_ticket';

    public const TYPE_SLA_APPROACHING = 'sla_approaching';

    public const TYPE_SLA_BREACH = 'sla_breach';

    protected $fillable = ['type', 'title', 'body', 'data', 'read_at'];

    protected function casts(): array
    {
        return [
            'data' => 'array',
            'read_at' => 'datetime',
        ];
    }

    public function scopeUnread(Builder $query): Builder
    {
        return $query->whereNull('read_at');
    }

    public function markAsRead(): void
    {
        if (! $this->read_at) {
            $this->forceFill(['read_at' => now()])->save();
        }
    }

    public function timeAgo(): string
    {
        return $this->created_at?->diffForHumans(now(), CarbonInterface::DIFF_ABSOLUTE, true, 1).' lalu';
    }
}
