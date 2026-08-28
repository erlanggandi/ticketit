<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Technician;
use App\Models\Ticket;
use App\Models\TicketAssignment;
use App\Models\User;
use Carbon\CarbonInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportKpiTest extends TestCase
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

    public function test_kpi_per_teknisi_dihitung_dari_riwayat_penugasan(): void
    {
        $teknisiA = Technician::query()->create(['technician_name' => 'Ahmad Fauzi']);
        $teknisiB = Technician::query()->create(['technician_name' => 'Rina Marlina']);

        // T1: ditangani A, selesai tepat SLA (dibuat 2 jam lalu, due +4 jam, resolved 30 mnt lalu).
        $t1 = $this->makeTicket('TCK-2026-00001', now()->subHours(2));
        $t1->forceFill([
            'sla_due_at' => now()->subHours(2)->addHours(4),
            'resolved_at' => now()->subMinutes(30),
        ])->save();
        $this->assign($t1, $teknisiA, now()->subHours(2)->addMinutes(10));

        // T2: dibuat 3 jam lalu, ditugaskan A lalu dieskalasi ke B, diselesaikan melewati SLA.
        $t2 = $this->makeTicket('TCK-2026-00002', now()->subHours(3));
        $t2->forceFill([
            'sla_due_at' => now()->subHours(3)->addHour(),
            'resolved_at' => now()->subHour(),
        ])->save();
        $this->assign($t2, $teknisiA, now()->subHours(3)->addMinutes(10), now()->subHours(2));
        $this->assign($t2, $teknisiB, now()->subHours(2));

        $response = $this->actingAs($this->admin)->get(route('admin.reports.index'));

        $response->assertOk();

        $kpi = collect($response->viewData('kpiByTechnician'))->keyBy('name');

        $a = $kpi['Ahmad Fauzi'];
        $this->assertSame(2, $a['handled']);
        $this->assertSame(0, $a['escalations']);
        $this->assertSame(1, $a['resolved']);
        $this->assertSame(100.0, $a['sla_rate']);
        $this->assertNotNull($a['avg_label']);

        $b = $kpi['Rina Marlina'];
        $this->assertSame(1, $b['handled']);
        $this->assertSame(1, $b['escalations']);
        $this->assertSame(1, $b['resolved']);
        $this->assertSame(0.0, $b['sla_rate']);
    }

    public function test_tanpa_penugasan_tidak_ada_baris_kpi(): void
    {
        $this->makeTicket('TCK-2026-00001', now());

        $response = $this->actingAs($this->admin)->get(route('admin.reports.index'));

        $response->assertOk();
        $this->assertCount(0, $response->viewData('kpiByTechnician'));
    }

    public function test_rentang_semua_periode_mengabaikan_batas_bulan_berjalan(): void
    {
        $lama = $this->makeDeptTicket('OLD-0001', 'HRGA', 'Network', Ticket::STATUS_OPEN);
        $lama->forceFill(['created_at' => now()->subMonths(2)])->save();

        $default = $this->actingAs($this->admin)->get(route('admin.reports.index'));
        $this->assertSame(0, $default->viewData('totalTickets'));

        $semua = $this->actingAs($this->admin)->get(route('admin.reports.index', ['range' => 'all']));
        $semua->assertOk();
        $this->assertSame(1, $semua->viewData('totalTickets'));
        $this->assertSame('Semua Periode', $semua->viewData('periodLabel'));
    }

    private function makeDeptTicket(string $number, ?string $department, string $categoryName, string $status): Ticket
    {
        $category = Category::query()->firstOrCreate(['category_name' => $categoryName]);

        return Ticket::query()->create([
            'ticket_number' => $number,
            'requester_name' => 'Budi Santoso',
            'department' => $department,
            'category_id' => $category->id,
            'priority' => Ticket::PRIORITY_MEDIUM,
            'description' => 'Deskripsi pengujian KPI departemen.',
            'status' => $status,
            'sla_due_at' => now()->addHours(8),
        ]);
    }

    private function makeTicket(string $number, CarbonInterface $createdAt): Ticket
    {
        $ticket = Ticket::query()->create([
            'ticket_number' => $number,
            'requester_name' => 'Budi Santoso',
            'category_id' => $this->category->id,
            'priority' => Ticket::PRIORITY_MEDIUM,
            'description' => 'Deskripsi pengujian KPI.',
            'status' => Ticket::STATUS_RESOLVED,
            'sla_due_at' => $createdAt->copy()->addHours(8),
        ]);

        $ticket->forceFill(['created_at' => $createdAt])->save();

        return $ticket;
    }

    private function assign(Ticket $ticket, Technician $technician, CarbonInterface $at, ?CarbonInterface $until = null): void
    {
        TicketAssignment::query()->create([
            'ticket_id' => $ticket->id,
            'technician_id' => $technician->id,
            'assigned_at' => $at,
            'unassigned_at' => $until,
        ]);

        if ($until === null) {
            $ticket->forceFill(['assigned_technician_id' => $technician->id])->save();
        }
    }
}
