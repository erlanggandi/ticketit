<?php

namespace Tests\Feature;

use App\Models\AdminNotification;
use App\Models\Category;
use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SlaSchedulerTest extends TestCase
{
    use RefreshDatabase;

    private function createTicket(array $overrides = []): Ticket
    {
        $category = Category::query()->create(['category_name' => 'Hardware']);

        return Ticket::query()->create(array_merge([
            'ticket_number' => 'TCK-2026-00001',
            'requester_name' => 'Budi Santoso',
            'category_id' => $category->id,
            'priority' => Ticket::PRIORITY_HIGH,
            'description' => 'Printer tidak merespon perintah cetak.',
            'status' => Ticket::STATUS_OPEN,
            'sla_due_at' => now()->addHours(8),
        ], $overrides));
    }

    public function test_alert_terkirim_saat_mendekati_due_date(): void
    {
        $ticket = $this->createTicket(['sla_due_at' => now()->addMinutes(30)]);

        $this->artisan('tickets:check-sla')->assertSuccessful();

        $this->assertSame(1, AdminNotification::query()->where('type', AdminNotification::TYPE_SLA_APPROACHING)->count());
        $this->assertNotNull($ticket->fresh()->sla_alert_approaching_sent_at);
    }

    public function test_tidak_ada_alert_jika_masih_lama(): void
    {
        $this->createTicket(['sla_due_at' => now()->addHours(6)]);

        $this->artisan('tickets:check-sla');

        $this->assertSame(0, AdminNotification::query()->count());
    }

    public function test_alert_breach_terkirim_saat_melewati_due(): void
    {
        $ticket = $this->createTicket(['sla_due_at' => now()->subHour()]);

        $this->artisan('tickets:check-sla');

        $this->assertSame(1, AdminNotification::query()->where('type', AdminNotification::TYPE_SLA_BREACH)->count());
        $this->assertNotNull($ticket->fresh()->sla_alert_breach_sent_at);
        $this->assertSame(0, AdminNotification::query()->where('type', AdminNotification::TYPE_SLA_APPROACHING)->count());
    }

    public function test_alert_tidak_duplikat_sata_dijalankan_ulang(): void
    {
        $this->createTicket(['sla_due_at' => now()->subMinutes(10)]);

        $this->artisan('tickets:check-sla');
        $this->artisan('tickets:check-sla');
        $this->artisan('tickets:check-sla');

        $this->assertSame(1, AdminNotification::query()->where('type', AdminNotification::TYPE_SLA_BREACH)->count());
    }

    public function test_tiket_closed_tidak_diproses(): void
    {
        $this->createTicket([
            'status' => Ticket::STATUS_CLOSED,
            'sla_due_at' => now()->subDay(),
        ]);

        $this->artisan('tickets:check-sla');

        $this->assertSame(0, AdminNotification::query()->count());
    }
}
