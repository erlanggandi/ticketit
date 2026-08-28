<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Technician;
use App\Models\Ticket;
use App\Models\TicketAssignment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TechnicianTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create();
        $this->category = Category::query()->create(['category_name' => 'Software']);
    }

    public function test_admin_dapat_kelola_teknisi(): void
    {
        $this->actingAs($this->admin)
            ->post('/admin/technicians', [
                'technician_name' => 'Ahmad Fauzi',
                'expertise' => 'Jaringan',
                'contact' => 'ahmad@company.co.id',
                'is_active' => '1',
            ])
            ->assertSessionHasNoErrors();

        $technician = Technician::query()->where('technician_name', 'Ahmad Fauzi')->first();
        $this->assertNotNull($technician);
        $this->assertSame('Jaringan', $technician->expertise);
        $this->assertTrue($technician->is_active);

        $this->actingAs($this->admin)
            ->patch("/admin/technicians/{$technician->id}", [
                'technician_name' => 'Ahmad Fauzi',
                'expertise' => 'Jaringan & Server',
            ])
            ->assertSessionHasNoErrors();

        $this->assertSame('Jaringan & Server', $technician->fresh()->expertise);
        $this->assertFalse($technician->fresh()->is_active);
    }

    public function test_nama_teknisi_wajib_diisi(): void
    {
        $this->actingAs($this->admin)
            ->post('/admin/technicians', ['expertise' => 'Jaringan'])
            ->assertSessionHasErrors('technician_name');
    }

    public function test_status_teknisi_bisa_diubah(): void
    {
        $technician = Technician::query()->create(['technician_name' => 'Rudi Hartono']);

        $this->actingAs($this->admin)
            ->patch("/admin/technicians/{$technician->id}/toggle")
            ->assertSessionHasNoErrors();

        $this->assertFalse($technician->fresh()->is_active);
    }

    public function test_hapus_teknisi_melepaskan_tiket_terkait(): void
    {
        $technician = Technician::query()->create(['technician_name' => 'Rudi Hartono']);
        $ticket = $this->makeTicket('TCK-2026-00001');
        $ticket->forceFill(['assigned_technician_id' => $technician->id])->save();
        TicketAssignment::query()->create([
            'ticket_id' => $ticket->id,
            'technician_id' => $technician->id,
            'assigned_at' => now(),
        ]);

        $this->actingAs($this->admin)
            ->delete("/admin/technicians/{$technician->id}")
            ->assertSessionHasNoErrors();

        $this->assertNull(Technician::find($technician->id));
        $this->assertNull($ticket->fresh()->assigned_technician_id);
        $this->assertSame(0, $ticket->assignments()->count());
        $this->assertTrue(Ticket::query()->where('ticket_number', 'TCK-2026-00001')->exists());
    }

    public function test_teknisi_tanpa_tiket_bisa_dihapus(): void
    {
        $technician = Technician::query()->create(['technician_name' => 'Bebas Hapus']);

        $this->actingAs($this->admin)
            ->delete("/admin/technicians/{$technician->id}")
            ->assertSessionHasNoErrors();

        $this->assertNull(Technician::find($technician->id));
    }

    private function makeTicket(string $number): Ticket
    {
        return Ticket::query()->create([
            'ticket_number' => $number,
            'requester_name' => 'Budi Santoso',
            'category_id' => $this->category->id,
            'priority' => Ticket::PRIORITY_MEDIUM,
            'description' => 'Deskripsi pengujian.',
            'status' => Ticket::STATUS_OPEN,
            'sla_due_at' => now()->addHours(8),
        ]);
    }
}
