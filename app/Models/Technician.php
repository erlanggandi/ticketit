<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Technician extends Model
{
    protected $fillable = ['technician_name', 'expertise', 'contact', 'is_active'];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'assigned_technician_id');
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(TicketAssignment::class);
    }

    public function initials(): string
    {
        return collect(explode(' ', $this->technician_name))
            ->map(fn ($word) => mb_substr($word, 0, 1))
            ->slice(0, 2)
            ->implode('');
    }
}
