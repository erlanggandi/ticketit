<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['is_active' => true]);

        $category = Category::query()->create(['category_name' => 'Software']);

        $tickets = [
            ['ticket_number' => 'TCK-2026-00001', 'status' => Ticket::STATUS_OPEN, 'sla_due_at' => now()->addHours(8)],
            ['ticket_number' => 'TCK-2026-00002', 'status' => Ticket::STATUS_OPEN, 'sla_due_at' => now()->addHours(8)],
            ['ticket_number' => 'TCK-2026-00003', 'status' => Ticket::STATUS_OPEN, 'sla_due_at' => now()->addHours(8)],
            ['ticket_number' => 'TCK-2026-00004', 'status' => Ticket::STATUS_IN_PROGRESS, 'sla_due_at' => now()->addHours(8)],
            ['ticket_number' => 'TCK-2026-00005', 'status' => Ticket::STATUS_IN_PROGRESS, 'sla_due_at' => now()->subHours(2)],
            [
                'ticket_number' => 'TCK-2026-00006',
                'status' => Ticket::STATUS_RESOLVED,
                'sla_due_at' => now()->addHours(8),
                'resolved_at' => now(),
            ],
            [
                'ticket_number' => 'TCK-2026-00007',
                'status' => Ticket::STATUS_RESOLVED,
                'sla_due_at' => now()->addHours(8),
                'resolved_at' => now(),
            ],
        ];

        foreach ($tickets as $ticket) {
            Ticket::query()->create([
                'ticket_number' => $ticket['ticket_number'],
                'requester_name' => 'Budi Santoso',
                'category_id' => $category->id,
                'priority' => Ticket::PRIORITY_MEDIUM,
                'description' => 'Aplikasi akuntansi crash saat membuka laporan bulanan.',
                'status' => $ticket['status'],
                'sla_due_at' => $ticket['sla_due_at'],
                'resolved_at' => $ticket['resolved_at'] ?? null,
            ]);
        }
    }

    public function test_dashboard_menampilkan_angka_ringkasan_pada_setiap_kartu(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));

        $response->assertOk();

        preg_match_all('/tabular-nums">\s*([^<]*?)\s*<\/p>/s', (string) $response->getContent(), $matches);

        // Urutan kartu: Open (3), In Progress (2), Melewati SLA (1), Resolved Minggu Ini (2).
        $this->assertSame(['3', '2', '1', '2'], $matches[1]);
    }

    public function test_dashboard_tanpa_tiket_menampilkan_angka_nol(): void
    {
        Ticket::query()->delete();

        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));

        $response->assertOk();

        preg_match_all('/tabular-nums">\s*([^<]*?)\s*<\/p>/s', (string) $response->getContent(), $matches);

        $this->assertSame(['0', '0', '0', '0'], $matches[1]);
    }

    public function test_dashboard_tidak_menampilkan_tiket_closed_pada_tiket_terbaru(): void
    {
        $category = Category::query()->first();
        $closed = Ticket::query()->create([
            'ticket_number' => 'TCK-2026-00999',
            'requester_name' => 'Tiket Closed',
            'category_id' => $category->id,
            'priority' => Ticket::PRIORITY_LOW,
            'description' => 'Tiket yang sudah ditutup.',
            'status' => Ticket::STATUS_CLOSED,
            'sla_due_at' => now()->addHours(8),
            'closed_at' => now(),
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertDontSee($closed->ticket_number);
        $response->assertSee('TCK-2026-00001');
    }

    public function test_dashboard_hanya_menampilkan_open_in_progress_resolved(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));

        $content = (string) $response->getContent();

        foreach (['TCK-2026-00001', 'TCK-2026-00004', 'TCK-2026-00006'] as $number) {
            $this->assertStringContainsString($number, $content);
        }
    }

    public function test_dashboard_menyembunyikan_tabel_saat_tidak_ada_tiket_aktif(): void
    {
        Ticket::query()->whereIn('status', [Ticket::STATUS_OPEN, Ticket::STATUS_IN_PROGRESS, Ticket::STATUS_RESOLVED])->delete();

        $category = Category::query()->first();
        Ticket::query()->create([
            'ticket_number' => 'TCK-2026-00888',
            'requester_name' => 'Hanya Closed',
            'category_id' => $category->id,
            'priority' => Ticket::PRIORITY_LOW,
            'description' => 'Satu-satunya tiket, sudah closed.',
            'status' => Ticket::STATUS_CLOSED,
            'sla_due_at' => now()->addHours(8),
            'closed_at' => now(),
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertDontSee('Tiket Terbaru');
        $response->assertDontSee('TCK-2026-00888');
    }
}
