<?php

namespace App\Services;

use App\Models\Technician;
use App\Models\Ticket;
use App\Models\TicketAssignment;

class TicketAssignmentService
{
    /**
     * Tutup baris assignment aktif sebelumnya (jika ada), buka baris baru
     * untuk teknisi tujuan, dan perbarui penanggung jawab saat ini di tiket.
     * Riwayat inilah sumber perhitungan KPI per teknisi.
     */
    public function assign(Ticket $ticket, Technician $technician): void
    {
        $ticket->assignments()->active()->update(['unassigned_at' => now()]);

        TicketAssignment::query()->create([
            'ticket_id' => $ticket->id,
            'technician_id' => $technician->id,
            'assigned_at' => now(),
        ]);

        $ticket->forceFill(['assigned_technician_id' => $technician->id])->save();
    }
}
