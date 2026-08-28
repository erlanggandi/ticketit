<?php

namespace Tests\Feature;

use App\Models\AdminNotification;
use App\Models\Category;
use App\Models\Employee;
use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicTicketCreationTest extends TestCase
{
    use RefreshDatabase;

    public function test_karyawan_dapat_membuat_tiket_tanpa_login(): void
    {
        $category = Category::query()->create(['category_name' => 'Hardware', 'is_active' => true]);

        $response = $this->post('/tickets', [
            'requester_name' => 'Budi Santoso',
            'category_id' => $category->id,
            'priority' => Ticket::PRIORITY_HIGH,
            'description' => 'Laptop tidak bisa dinyalakan sama sekali sejak pagi.',
        ]);

        $ticket = Ticket::query()->first();

        $this->assertNotNull($ticket);
        $response->assertRedirect(route('tickets.success', ['ticket' => $ticket->id]));

        $this->assertSame('TCK-'.now()->format('Y').'-00001', $ticket->ticket_number);
        $this->assertSame(Ticket::STATUS_OPEN, $ticket->status);
        $this->assertNotNull($ticket->sla_due_at);
        $this->assertTrue(abs($ticket->sla_due_at->diffInMinutes(now()->addHours(8))) < 1);
    }

    public function test_validasi_field_wajib_berjalan(): void
    {
        $this->post('/tickets', [])->assertSessionHasErrors(['requester_name', 'category_id', 'priority', 'description']);
        $this->assertSame(0, Ticket::query()->count());
    }

    public function test_nomor_tiket_berurutan_dan_unik(): void
    {
        $category = Category::query()->create(['category_name' => 'Software']);

        foreach ([1, 2] as $i) {
            $this->post('/tickets', [
                'requester_name' => "Pengguna {$i}",
                'category_id' => $category->id,
                'priority' => Ticket::PRIORITY_LOW,
                'description' => 'Deskripsi masalah pengujian nomor '.$i,
            ]);
        }

        $numbers = Ticket::query()->orderBy('id')->pluck('ticket_number');
        $this->assertSame('TCK-'.now()->format('Y').'-00001', $numbers[0]);
        $this->assertSame('TCK-'.now()->format('Y').'-00002', $numbers[1]);
    }

    public function test_notifikasi_web_dibuat_saat_tiket_baru(): void
    {
        $category = Category::query()->create(['category_name' => 'Jaringan']);

        $this->post('/tickets', [
            'requester_name' => 'Andi Wijaya',
            'category_id' => $category->id,
            'priority' => Ticket::PRIORITY_CRITICAL,
            'description' => 'Internet kantor mati total sejak tadi malam.',
        ]);

        $this->assertSame(1, AdminNotification::query()->where('type', AdminNotification::TYPE_NEW_TICKET)->count());
    }

    public function test_halaman_sukses_memerlukan_session(): void
    {
        $this->get('/tickets/1/success')->assertNotFound();
    }

    public function test_departemen_diisi_otomatis_dari_karyawan_terdaftar(): void
    {
        $category = Category::query()->create(['category_name' => 'Hardware', 'is_active' => true]);
        $employee = Employee::query()->create(['employee_name' => 'Budi Santoso', 'department' => 'Finance', 'is_active' => true]);

        $this->post('/tickets', [
            'requester_name' => 'Budi Santoso',
            'employee_id' => $employee->id,
            'category_id' => $category->id,
            'priority' => Ticket::PRIORITY_HIGH,
            'description' => 'Laptop tidak bisa dinyalakan sejak pagi tadi.',
        ])->assertRedirect();

        $ticket = Ticket::query()->first();
        $this->assertSame('Finance', $ticket->department);
    }

    public function test_departemen_null_jika_karyawan_tidak_terdaftar(): void
    {
        $category = Category::query()->create(['category_name' => 'Software', 'is_active' => true]);

        $this->post('/tickets', [
            'requester_name' => 'Nama Manual',
            'category_id' => $category->id,
            'priority' => Ticket::PRIORITY_LOW,
            'description' => 'Masalah aplikasi yang dialami secara manual.',
        ])->assertRedirect();

        $this->assertNull(Ticket::query()->first()->department);
    }

    public function test_halaman_sukses_menampilkan_departemen(): void
    {
        $category = Category::query()->create(['category_name' => 'Hardware', 'is_active' => true]);
        $employee = Employee::query()->create(['employee_name' => 'Siti Rahayu', 'department' => 'HRD', 'is_active' => true]);

        $this->post('/tickets', [
            'requester_name' => 'Siti Rahayu',
            'employee_id' => $employee->id,
            'category_id' => $category->id,
            'priority' => Ticket::PRIORITY_HIGH,
            'description' => 'Printer di lantai 3 rusak dan tidak merespons.',
        ])->assertRedirect();

        $ticket = Ticket::query()->first();

        $this->withSession(['just_created_ticket_number' => $ticket->ticket_number])
            ->get(route('tickets.success', ['ticket' => $ticket->id]))
            ->assertOk()
            ->assertSee('HRD');
    }
}
