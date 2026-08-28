<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Employee;
use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckTicketStatusTest extends TestCase
{
    use RefreshDatabase;

    private function createTicket(array $overrides = []): Ticket
    {
        $category = Category::query()->create(['category_name' => 'Hardware']);

        return Ticket::query()->create(array_merge([
            'ticket_number' => 'TCK-2026-00001',
            'requester_name' => 'Budi Santoso',
            'category_id' => $category->id,
            'priority' => Ticket::PRIORITY_MEDIUM,
            'description' => 'Monitor tidak menampilkan gambar apapun.',
            'status' => Ticket::STATUS_OPEN,
            'sla_due_at' => now()->addDays(3),
        ], $overrides));
    }

    public function test_status_tampil_untuk_nomor_valid(): void
    {
        $ticket = $this->createTicket();

        $response = $this->get('/tickets/check?number='.$ticket->ticket_number);

        $response->assertOk();
        $response->assertSee($ticket->ticket_number);
        $response->assertSee($ticket->requester_name);
    }

    public function test_departemen_tampil_pada_halaman_cek_status(): void
    {
        $ticket = $this->createTicket(['department' => 'Finance']);

        $this->get('/tickets/check?number='.$ticket->ticket_number)
            ->assertOk()
            ->assertSee('Finance');
    }

    public function test_pesan_tidak_ditemukan_untuk_nomor_invalid(): void
    {
        $this->createTicket();

        $response = $this->get('/tickets/check?number=TCK-2026-99999');

        $response->assertOk();
        $response->assertSee('Tiket tidak ditemukan');
    }

    public function test_autocomplete_mengembalikan_karyawan_cocok(): void
    {
        Employee::query()->create(['employee_name' => 'Siti Rahayu', 'department' => 'HRD', 'is_active' => true]);
        Employee::query()->create(['employee_name' => 'Budi Santoso', 'department' => 'Finance', 'is_active' => false]);

        $response = $this->getJson('/api/employees/search?q=siti');

        $response->assertOk();
        $this->assertCount(1, $response->json('data'));
        $this->assertSame('Siti Rahayu', $response->json('data.0.name'));
    }

    public function test_autocomplete_hanya_karyawan_aktif_dan_minimal_dua_karakter(): void
    {
        Employee::query()->create(['employee_name' => 'Dewi Lestari', 'is_active' => true]);

        $this->getJson('/api/employees/search?q=d')->assertJson(['data' => []]);
        $this->getJson('/api/employees/search?q=nonexistent')->assertJson(['data' => []]);
    }
}
