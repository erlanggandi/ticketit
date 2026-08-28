<?php

namespace App\Services;

use App\Models\SlaSetting;
use Carbon\CarbonInterface;

class SlaService
{
    public function calculateDueDate(string $priority, ?CarbonInterface $from = null): CarbonInterface
    {
        // copy() mencegah mutasi objek tanggal penelepon (mis. created_at tiket).
        return ($from !== null ? $from->copy() : now())->addHours($this->durationHoursFor($priority));
    }

    public function durationHoursFor(string $priority): int
    {
        $configured = SlaSetting::query()->where('priority', $priority)->value('duration_hours');

        if ($configured !== null) {
            return max((int) $configured, 1);
        }

        return (int) config("sla.fallback_durations.{$priority}", 24);
    }
}
