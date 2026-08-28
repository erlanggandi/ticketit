<?php

namespace App\Models;

use App\Services\SlaService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    public const STATUS_OPEN = 'Open';

    public const STATUS_IN_PROGRESS = 'In Progress';

    public const STATUS_RESOLVED = 'Resolved';

    public const STATUS_CLOSED = 'Closed';

    /** @var list<string> */
    public const STATUSES = [
        self::STATUS_OPEN,
        self::STATUS_IN_PROGRESS,
        self::STATUS_RESOLVED,
        self::STATUS_CLOSED,
    ];

    /** @var array<string, list<string>> */
    public const ALLOWED_TRANSITIONS = [
        self::STATUS_OPEN => [self::STATUS_IN_PROGRESS],
        self::STATUS_IN_PROGRESS => [self::STATUS_RESOLVED],
        self::STATUS_RESOLVED => [self::STATUS_CLOSED],
        self::STATUS_CLOSED => [],
    ];

    public const PRIORITY_LOW = 'Low';

    public const PRIORITY_MEDIUM = 'Medium';

    public const PRIORITY_HIGH = 'High';

    public const PRIORITY_CRITICAL = 'Critical';

    /** @var list<string> */
    public const PRIORITIES = [
        self::PRIORITY_LOW,
        self::PRIORITY_MEDIUM,
        self::PRIORITY_HIGH,
        self::PRIORITY_CRITICAL,
    ];

    protected $fillable = [
        'ticket_number',
        'requester_name',
        'requester_id',
        'department',
        'category_id',
        'assigned_technician_id',
        'priority',
        'description',
        'status',
        'sla_due_at',
        'resolved_at',
        'closed_at',
    ];

    protected function casts(): array
    {
        return [
            'sla_due_at' => 'datetime',
            'sla_alert_approaching_sent_at' => 'datetime',
            'sla_alert_breach_sent_at' => 'datetime',
            'resolved_at' => 'datetime',
            'closed_at' => 'datetime',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'requester_id');
    }

    public function assignedTechnician(): BelongsTo
    {
        return $this->belongsTo(Technician::class, 'assigned_technician_id');
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(TicketAssignment::class)->latest('assigned_at');
    }

    public function scopeOpen(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_OPEN);
    }

    public function scopeActiveStatuses(Builder $query): Builder
    {
        return $query->whereIn('status', [self::STATUS_OPEN, self::STATUS_IN_PROGRESS]);
    }

    public function scopeOverdue(Builder $query): Builder
    {
        return $query->activeStatuses()->where('sla_due_at', '<', now());
    }

    public function isOverdue(): bool
    {
        return $this->isActive() && $this->sla_due_at->isPast();
    }

    public function isActive(): bool
    {
        return in_array($this->status, [self::STATUS_OPEN, self::STATUS_IN_PROGRESS], true);
    }

    public function isClosed(): bool
    {
        return $this->status === self::STATUS_CLOSED;
    }

    /**
     * @return list<string>
     */
    public function nextAllowedStatuses(): array
    {
        return self::ALLOWED_TRANSITIONS[$this->status] ?? [];
    }

    public function resolutionTimeInMinutes(): ?int
    {
        if (! $this->resolved_at) {
            return null;
        }

        return (int) round($this->created_at->diffInMinutes($this->resolved_at));
    }

    public function slaCompliant(): ?bool
    {
        if (! $this->resolved_at) {
            return null;
        }

        return $this->resolved_at->lessThanOrEqualTo($this->sla_due_at);
    }

    public static function boot(): void
    {
        parent::boot();

        static::creating(function (Ticket $ticket) {
            if (empty($ticket->sla_due_at)) {
                $ticket->sla_due_at = app(SlaService::class)->calculateDueDate($ticket->priority);
            }
        });
    }
}
