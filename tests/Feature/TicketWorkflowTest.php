<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\Category;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketWorkflowTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private Ticket $ticket;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['is_active' => true]);

        $category = Category::query()->create(['category_name' => 'Software']);

        $this->ticket = Ticket::query()->create([
            'ticket_number' => 'TCK-2026-00001',
            'requester_name' => 'Budi Santoso',
            'category_id' => $category->id,
            'priority' => Ticket::PRIORITY_HIGH,
            'description' => 'Aplikasi akuntansi crash saat membuka laporan bulanan.',
            'status' => Ticket::STATUS_OPEN,
            'sla_due_at' => now()->addHours(8),
        ]);
    }

    public function test_transisi_valid_open_ke_in_progress(): void
    {
        $this->actingAs($this->admin)
            ->patch("/admin/tickets/{$this->ticket->id}/status", ['status' => Ticket::STATUS_IN_PROGRESS])
            ->assertSessionHasNoErrors();

        $ticket = $this->ticket->fresh();
        $this->assertSame(Ticket::STATUS_IN_PROGRESS, $ticket->status);

        $this->assertTrue(
            AuditLog::query()
                ->where('entity_type', 'Ticket')
                ->where('entity_id', $this->ticket->id)
                ->where('action', 'status_changed')
                ->exists()
        );
    }

    public function test_transisi_invalid_ditolak(): void
    {
        $this->actingAs($this->admin)
            ->patch("/admin/tickets/{$this->ticket->id}/status", ['status' => Ticket::STATUS_RESOLVED])
            ->assertSessionHasErrors('status');

        $this->assertSame(Ticket::STATUS_OPEN, $this->ticket->fresh()->status);
    }

    public function test_resolved_mengisi_waktu_selesai(): void
    {
        $this->ticket->forceFill(['status' => Ticket::STATUS_IN_PROGRESS])->save();

        $this->actingAs($this->admin)
            ->patch("/admin/tickets/{$this->ticket->id}/status", ['status' => Ticket::STATUS_RESOLVED]);

        $this->assertNotNull($this->ticket->fresh()->resolved_at);
    }

    public function test_closed_mengisi_waktu_tutup_dan_tidak_bisa_diubah(): void
    {
        $this->travelTo(now()->addMinutes(30));
        $this->ticket->forceFill(['status' => Ticket::STATUS_RESOLVED, 'resolved_at' => now()])->save();

        $this->actingAs($this->admin)
            ->patch("/admin/tickets/{$this->ticket->id}/status", ['status' => Ticket::STATUS_CLOSED])
            ->assertSessionHasNoErrors();

        $this->assertNotNull($this->ticket->fresh()->closed_at);

        $this->actingAs($this->admin)
            ->patch("/admin/tickets/{$this->ticket->id}/status", ['status' => Ticket::STATUS_OPEN])
            ->assertForbidden();

        $this->actingAs($this->admin)
            ->patch("/admin/tickets/{$this->ticket->id}/details", [
                'priority' => Ticket::PRIORITY_LOW,
                'category_id' => $this->ticket->category_id,
            ])
            ->assertForbidden();

        $this->assertSame(Ticket::STATUS_CLOSED, $this->ticket->fresh()->status);
    }

    public function test_admin_dapat_override_prioritas_dan_sla_dihitung_ulang(): void
    {
        $oldDue = $this->ticket->sla_due_at;

        $this->actingAs($this->admin)
            ->patch("/admin/tickets/{$this->ticket->id}/details", [
                'priority' => Ticket::PRIORITY_CRITICAL,
                'category_id' => $this->ticket->category_id,
            ])
            ->assertSessionHasNoErrors();

        $ticket = $this->ticket->fresh();

        $this->assertSame(Ticket::PRIORITY_CRITICAL, $ticket->priority);
        $this->assertTrue($ticket->sla_due_at->equalTo($this->ticket->created_at->copy()->addHours(4)));
        $this->assertFalse($oldDue->equalTo($ticket->sla_due_at));

        $this->assertNull($ticket->sla_alert_approaching_sent_at);
        $this->assertNull($ticket->sla_alert_breach_sent_at);
    }

    public function test_filter_daftar_tiket_berjalan(): void
    {
        $this->actingAs($this->admin)->get('/admin/tickets?status=Open')
            ->assertOk()
            ->assertSee($this->ticket->ticket_number);

        $this->actingAs($this->admin)->get('/admin/tickets?status=Closed')
            ->assertOk()
            ->assertDontSee($this->ticket->ticket_number);

        $this->actingAs($this->admin)->get('/admin/tickets?q=budi')
            ->assertOk()
            ->assertSee($this->ticket->ticket_number);
    }
}
