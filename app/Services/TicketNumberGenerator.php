<?php

namespace App\Services;

use App\Models\Ticket;
use Illuminate\Support\Facades\DB;

class TicketNumberGenerator
{
    public function generate(): string
    {
        $year = now()->format('Y');
        $prefix = "TCK-{$year}-";

        return DB::transaction(function () use ($prefix) {
            if (DB::connection()->getDriverName() === 'pgsql') {
                DB::select('select pg_advisory_xact_lock(?)', [crc32('ticket_number_seq')]);
            }

            $last = Ticket::query()
                ->where('ticket_number', 'like', $prefix.'%')
                ->orderByDesc('ticket_number')
                ->value('ticket_number');

            $sequence = $last !== null
                ? (int) substr($last, strlen($prefix)) + 1
                : 1;

            return $prefix.str_pad((string) $sequence, 5, '0', STR_PAD_LEFT);
        });
    }
}
