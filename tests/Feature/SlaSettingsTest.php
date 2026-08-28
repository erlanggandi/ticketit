<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\SlaSetting;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SlaSettingsTest extends TestCase
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

    private function createTicket(array $overrides = []): Ticket
    {
        $alertOverrides = collect([
            'sla_alert_approaching_sent_at' => $overrides['sla_alert_approaching_sent_at'] ?? null,
            'sla_alert_breach_sent_at' => $overrides['sla_alert_breach_sent_at'] ?? null,
        ])->filter()->all();
        unset($overrides['sla_alert_approaching_sent_at'], $overrides['sla_alert_breach_sent_at']);

        $createdAtOverride = isset($overrides['created_at']) ? ['created_at' => $overrides['created_at']] : [];
        unset($overrides['created_at']);

        $ticket = Ticket::query()->create(array_merge([
            'ticket_number' => 'TCK-2026-'.str_pad((string) (Ticket::query()->count() + 1), 5, '0', STR_PAD_LEFT),
            'requester_name' => 'Budi Santoso',
            'category_id' => $this->category->id,
            'priority' => Ticket::PRIORITY_HIGH,
            'description' => 'Printer tidak merespon perintah cetak.',
            'status' => Ticket::STATUS_OPEN,
            'sla_due_at' => now()->addHours(8),
        ], $overrides));

        $ticket->forceFill(array_merge($alertOverrides, $createdAtOverride))->save();

        return $ticket;
    }

    private function submitSettings(array $durations): void
    {
        $this->actingAs($this->admin)->put(route('admin.sla-settings.update'), [
            'durations' => [
                'critical' => $durations['critical'],
                'high' => $durations['high'],
                'medium' => $durations['medium'],
                'low' => $durations['low'],
            ],
        ])->assertSessionHasNoErrors();
    }

    public function test_menyimpan_pengaturan_menghitung_ulang_due_date_tiket_aktif(): void
    {
        SlaSetting::query()->create(['priority' => Ticket::PRIORITY_HIGH, 'duration_hours' => 8]);

        $ticket = $this->createTicket([
            'sla_alert_approaching_sent_at' => now()->subHour(),
            'sla_alert_breach_sent_at' => now()->subMinutes(30),
            'created_at' => now()->subDays(2),
        ]);

        $expectedDue = $ticket->fresh()->created_at->copy()->addHours(4);

        $this->submitSettings(['critical' => 2, 'high' => 4, 'medium' => 12, 'low' => 24]);

        $fresh = $ticket->fresh();

        $this->assertTrue($expectedDue->equalTo($fresh->sla_due_at));
        $this->assertNull($fresh->sla_alert_approaching_sent_at);
        $this->assertNull($fresh->sla_alert_breach_sent_at);
    }

    public function test_tiket_resolved_dan_closed_tidak_dihitung_ulang(): void
    {
        $resolved = $this->createTicket([
            'status' => Ticket::STATUS_RESOLVED,
            'sla_due_at' => now()->subDay(),
            'sla_alert_breach_sent_at' => now()->subHours(5),
        ]);

        $closed = $this->createTicket([
            'status' => Ticket::STATUS_CLOSED,
            'sla_due_at' => now()->subDay(),
            'sla_alert_breach_sent_at' => now()->subHours(5),
        ]);

        [$resolved, $closed] = [$resolved->fresh(), $closed->fresh()];

        $this->submitSettings(['critical' => 2, 'high' => 4, 'medium' => 12, 'low' => 24]);

        foreach ([$resolved->fresh(), $closed->fresh()] as $ticket) {
            $this->assertTrue($resolved->sla_due_at->equalTo($ticket->sla_due_at));
            $this->assertNotNull($ticket->sla_alert_breach_sent_at);
        }
    }

    public function test_durasi_prioritas_yang_tidak_diubah_tidak_mereset_flag_alert(): void
    {
        $mediumTicket = $this->createTicket([
            'priority' => Ticket::PRIORITY_MEDIUM,
            'sla_due_at' => now()->addHours(72),
            'sla_alert_approaching_sent_at' => now()->subHour(),
        ]);

        $before = $mediumTicket->fresh();

        // Hanya High yang berubah (fallback 8 -> 4); Medium tetap 72 sesuai fallback.
        $this->submitSettings(['critical' => 4, 'high' => 4, 'medium' => 72, 'low' => 120]);

        $after = $mediumTicket->fresh();

        $this->assertTrue($before->sla_alert_approaching_sent_at->equalTo($after->sla_alert_approaching_sent_at));
        $this->assertTrue($before->sla_due_at->equalTo($after->sla_due_at));
    }
}
