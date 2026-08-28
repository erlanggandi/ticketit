<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\Category;
use App\Models\Technician;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketAssignmentTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['is_active' => true]);
        $this->category = Category::query()->create(['category_name' => 'Software']);
    }

    public function test_penugasan_pertama_mencatat_assignment_dan_audit(): void
    {
        [$ticket, $teknisiA] = $this->prepare();

        $this->actingAs($this->admin)
            ->patch("/admin/tickets/{$ticket->id}/assignment", ['technician_id' => $teknisiA->id])
            ->assertSessionHasNoErrors();

        $this->assertSame($teknisiA->id, $ticket->fresh()->assigned_technician_id);

        $assignment = $ticket->assignments()->first();
        $this->assertNotNull($assignment);
        $this->assertSame($teknisiA->id, (int) $assignment->technician_id);
        $this->assertNull($assignment->unassigned_at);

        $this->assertTrue(
            AuditLog::query()
                ->where('entity_type', 'Ticket')
                ->where('entity_id', $ticket->id)
                ->where('action', 'assigned')
                ->exists()
        );
    }

    public function test_eskalasi_menutup_riwayat_lama_dan_tercatat_sebagai_eskalasi(): void
    {
        [$ticket, $teknisiA, $teknisiB] = $this->prepare();

        $this->actingAs($this->admin)
            ->patch("/admin/tickets/{$ticket->id}/assignment", ['technician_id' => $teknisiA->id]);

        $this->travel(1)->hours();

        $this->actingAs($this->admin)
            ->patch("/admin/tickets/{$ticket->id}/assignment", ['technician_id' => $teknisiB->id])
            ->assertSessionHasNoErrors();

        $this->assertSame($teknisiB->id, $ticket->fresh()->assigned_technician_id);
        $this->assertSame(2, $ticket->assignments()->count());

        $closed = $ticket->assignments()->where('technician_id', $teknisiA->id)->first();
        $this->assertNotNull($closed->unassigned_at);

        $open = $ticket->assignments()->where('technician_id', $teknisiB->id)->first();
        $this->assertNull($open->unassigned_at);

        $this->assertTrue(
            AuditLog::query()
                ->where('entity_type', 'Ticket')
                ->where('entity_id', $ticket->id)
                ->where('action', 'escalated')
                ->exists()
        );
    }

    public function test_teknisi_nonaktif_ditolak(): void
    {
        [$ticket] = $this->prepare();

        $inactive = Technician::query()->create(['technician_name' => 'Nonaktif', 'is_active' => false]);

        $this->actingAs($this->admin)
            ->patch("/admin/tickets/{$ticket->id}/assignment", ['technician_id' => $inactive->id])
            ->assertSessionHasErrors('technician_id');

        $this->assertNull($ticket->fresh()->assigned_technician_id);
        $this->assertSame(0, $ticket->assignments()->count());
    }

    public function test_tiket_closed_tidak_bisa_ditugaskan(): void
    {
        [$ticket, $teknisiA] = $this->prepare();

        $ticket->forceFill(['status' => Ticket::STATUS_CLOSED])->save();

        $this->actingAs($this->admin)
            ->patch("/admin/tickets/{$ticket->id}/assignment", ['technician_id' => $teknisiA->id])
            ->assertForbidden();

        $this->assertNull($ticket->fresh()->assigned_technician_id);
    }

    /**
     * @return array{0: Ticket, 1: Technician, 2: Technician}
     */
    private function prepare(): array
    {
        $ticket = Ticket::query()->create([
            'ticket_number' => 'TCK-2026-00001',
            'requester_name' => 'Budi Santoso',
            'category_id' => $this->category->id,
            'priority' => Ticket::PRIORITY_MEDIUM,
            'description' => 'Deskripsi pengujian penugasan.',
            'status' => Ticket::STATUS_OPEN,
            'sla_due_at' => now()->addHours(8),
        ]);

        $teknisiA = Technician::query()->create(['technician_name' => 'Ahmad Fauzi']);
        $teknisiB = Technician::query()->create(['technician_name' => 'Rina Marlina']);

        return [$ticket, $teknisiA, $teknisiB];
    }
}
