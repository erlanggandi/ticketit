<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\Category;
use App\Models\Technician;
use App\Models\Ticket;
use App\Models\User;
use Carbon\CarbonInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class TicketCsvTest extends TestCase
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

    public function test_ekspor_csv_menghasilkan_header_dan_isi_benar(): void
    {
        $ticket = $this->makeTicket('TCK-2026-00001', now());

        $response = $this->actingAs($this->admin)->get(route('admin.tickets.export'));

        $response->assertOk();

        $this->assertStringContainsString('text/csv', (string) $response->headers->get('Content-Type'));
        $this->assertStringContainsString('tiket-ekspor-custom-', (string) $response->headers->get('Content-Disposition'));

        $content = $response->streamedContent();

        $this->assertStringContainsString('ticket_number,created_at,requester_name,department,category_name', str_replace("\xEF\xBB\xBF", '', $content));
        $this->assertStringContainsString($ticket->ticket_number, $content);
        $this->assertStringContainsString($this->category->category_name, $content);
    }

    public function test_ekspor_periode_bulan_hanya_menyertakan_tiket_bulan_tersebut(): void
    {
        $current = $this->makeTicket('TCK-2026-00001', now());
        $old = $this->makeTicket('TCK-2026-00002', now()->subMonth());

        $response = $this->actingAs($this->admin)->get(route('admin.tickets.export', [
            'period_mode' => 'month',
            'period_month' => now()->format('Y-m'),
        ]));

        $response->assertOk();

        $content = $response->streamedContent();
        $this->assertStringContainsString($current->ticket_number, $content);
        $this->assertStringNotContainsString($old->ticket_number, $content);
        $this->assertStringContainsString('tiket-ekspor-month-', (string) $response->headers->get('Content-Disposition'));
    }

    public function test_daftar_tiket_mengikuti_mode_periode_harian(): void
    {
        $today = $this->makeTicket('TCK-2026-00001', now());
        $earlier = $this->makeTicket('TCK-2026-00002', now()->subDays(3));

        $response = $this->actingAs($this->admin)->get(route('admin.tickets.index', [
            'period_mode' => 'day',
            'period_date' => now()->toDateString(),
        ]));

        $response->assertOk();
        $response->assertSee($today->ticket_number);
        $response->assertDontSee($earlier->ticket_number);
    }

    public function test_impor_membuat_tiket_baru_dengan_audit_log(): void
    {
        $csv = implode("\n", [
            'ticket_number,created_at,requester_name,category_name,priority,status,technician_name,sla_due_at,resolved_at,closed_at,description',
            'IMP-001,2026-08-01 09:00:00,Dewi Lestari,Jaringan,High,,, , , ,Wifi lantai 2 putus',
            'IMP-002,2026-08-02 10:00:00,Rudi Pratama,Printer,,,,,,Toner habis',
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.tickets.import'), [
            'file' => UploadedFile::fake()->createWithContent('tiket.csv', $csv),
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success', fn (string $message) => str_contains($message, '2 tiket dibuat'));

        $first = Ticket::query()->where('ticket_number', 'IMP-001')->first();
        $second = Ticket::query()->where('ticket_number', 'IMP-002')->first();

        $this->assertNotNull($first);
        $this->assertNotNull($second);

        $this->assertSame(Ticket::STATUS_OPEN, $first->status);
        $this->assertNotNull($first->sla_due_at);
        $this->assertSame(Ticket::PRIORITY_HIGH, $first->priority);

        // Kategori belum ada -> dibuat otomatis; baris tanpa prioritas default Medium.
        $this->assertTrue(Category::query()->where('category_name', 'Jaringan')->exists());
        $this->assertSame(Ticket::PRIORITY_MEDIUM, $second->priority);

        $this->assertSame(
            2,
            AuditLog::query()->where('entity_type', 'Ticket')->where('action', 'created')->count()
        );
    }

    public function test_impor_melewati_duplikat_dan_baris_invalid(): void
    {
        $existing = $this->makeTicket('TCK-2026-00001', now());

        $csv = implode("\n", [
            'ticket_number,created_at,requester_name,category_name,priority,status,technician_name,sla_due_at,resolved_at,closed_at,description',
            'TCK-2026-00001,2026-08-01 09:00:00,Seseorang,Software,Low,,, , , ,duplikat nomor',
            'IMP-BAD,2026-08-02 09:00:00,Seseorang,Software,Penting,,, , , ,prioritas tidak dikenal',
            'IMP-OK,2026-08-03 09:00:00,Pelapor Baru,Software,Low,,, , , ,baris valid',
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.tickets.import'), [
            'file' => UploadedFile::fake()->createWithContent('tiket.csv', $csv),
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success', function (string $message) {
            return str_contains($message, '1 tiket dibuat')
                && str_contains($message, '2 dilewati')
                && str_contains($message, '(duplikat)')
                && str_contains($message, '(prioritas tidak dikenal)');
        });

        $this->assertSame(2, Ticket::query()->count());
        $this->assertTrue(Ticket::query()->where('ticket_number', 'IMP-OK')->exists());
        $this->assertSame($existing->id, Ticket::query()->where('ticket_number', 'TCK-2026-00001')->value('id'));
    }

    public function test_impor_menolak_header_salah(): void
    {
        $csv = "no,nama\nIMP-1,Budi\n";

        $response = $this->actingAs($this->admin)->post(route('admin.tickets.import'), [
            'file' => UploadedFile::fake()->createWithContent('salah.csv', $csv),
        ]);

        $response->assertSessionHasErrors('file');
        $this->assertSame(0, Ticket::query()->count());
        $this->assertTrue(
            str_contains((string) session('errors')?->first('file'), 'Kolom terbaca dari file Anda: [no | nama]'),
            'Pesan error harus menampilkan kolom yang terbaca.'
        );
    }

    public function test_impor_csv_delimiter_titik_koma_diterima(): void
    {
        $csv = implode("\n", [
            'ticket_number;created_at;requester_name;category_name;priority;status;technician_name;sla_due_at;resolved_at;closed_at;description',
            'SC-0001;2026-08-10 09:00:00;Pak Budi;Software;Low;;; ; ; ;tiket titik koma',
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.tickets.import'), [
            'file' => UploadedFile::fake()->createWithContent('semicolon.csv', $csv),
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success', fn (string $message) => str_contains($message, '1 tiket dibuat'));

        $ticket = Ticket::query()->where('ticket_number', 'SC-0001')->first();
        $this->assertNotNull($ticket);
        $this->assertSame(Ticket::STATUS_OPEN, $ticket->status);
    }

    public function test_impor_csv_dengan_bom_excel_diterima(): void
    {
        $csv = "\xEF\xBB\xBF".implode("\n", [
            'ticket_number,created_at,requester_name,category_name,priority,status,technician_name,sla_due_at,resolved_at,closed_at,description',
            'BOM-0001,2026-08-11 09:00:00,Ibu Sari,Hardware,High,,, , , ,tiket dengan bom',
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.tickets.import'), [
            'file' => UploadedFile::fake()->createWithContent('bom.csv', $csv),
        ]);

        $response->assertSessionHasNoErrors();

        $this->assertTrue(Ticket::query()->where('ticket_number', 'BOM-0001')->exists());
        $this->assertTrue(Category::query()->where('category_name', 'Hardware')->exists());
    }

    public function test_impor_header_ber_spasi_tetap_dikenali(): void
    {
        $csv = implode("\n", [
            'Ticket Number,Created At,Requester Name,Category Name,Priority,Status,Technician Name,Sla Due At,Resolved At,Closed At,Description',
            'SPC-0001,2026-08-12 09:00:00,Pelapor Spasi,Kategori Baru,Medium,,, , , ,header ber spasi',
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.tickets.import'), [
            'file' => UploadedFile::fake()->createWithContent('spasi.csv', $csv),
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success', fn (string $message) => str_contains($message, '1 tiket dibuat'));

        $this->assertTrue(Ticket::query()->where('ticket_number', 'SPC-0001')->exists());
    }

    public function test_impor_format_helpdesk_lama_menormalisasi_seluruh_kolom(): void
    {
        $header = 'TicketID,Tanggal,NamaLengkap,Departemen,Kategori,Kendala,Priority,Status,Teknisi,Catatan';

        $csv = implode("\n", [
            $header,
            'TIK-90001,26/02/2026 14:43:00,nur fadiah,HRGA,Network,perbaikan excel,Urgent,Resolved,Abdullah Tauddin,',
            'TIK-90002,27/02/2026 8:38:08,Angela,HRGA,Software,wifi putus,Low,Open,A.Erlangga,',
            'TIK-90003,28/02/2026 9:00:00,Gunawan,HRGA,Hardware,printer rusak,Medium,Resolved,Mirza & Rahmatullah,',
            'TIK-90004,01/03/2026 10:00:00,Habibi,FAT,Hardware,layar terbuka,Urgent,Resolved,Vendor,',
            'TIK-90005,02/03/2026 11:00:00,Rafli,HRGA,Others,file hilang,Low,Open,,',
            'TIK-90006,31/31/2026 xx:xx:xx,Anonim,IT,Software,tanggal rusak,Low,Open,Andi Erlangga,',
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.tickets.import'), [
            'file' => UploadedFile::fake()->createWithContent('helpdesk-lama.csv', $csv),
        ]);

        $response->assertSessionHasNoErrors();

        $flash = (string) session('success');

        $this->assertStringContainsString('5 tiket dibuat', $flash);
        $this->assertStringContainsString('1 dilewati', $flash);
        $this->assertStringContainsString('(tanggal tidak valid: 31/31/2026', $flash);

        $pertama = Ticket::query()->where('ticket_number', 'TIK-90001')->first();
        $this->assertNotNull($pertama);
        $this->assertSame(Ticket::PRIORITY_CRITICAL, $pertama->priority);
        $this->assertSame(Ticket::STATUS_RESOLVED, $pertama->status);
        $this->assertSame(
            '2026-02-26 14:43:00',
            $pertama->created_at?->format('Y-m-d H:i:s'),
            'Created aktual: '.$pertama->created_at?->format('Y-m-d H:i:s')
        );

        // SLA Due dihitung dari created_at + durasi prioritas Critical (4 jam) — tidak pernah kosong.
        $this->assertSame(
            '2026-02-26 18:43:00',
            $pertama->sla_due_at?->format('Y-m-d H:i:s'),
            'SLA aktual: '.$pertama->sla_due_at?->format('Y-m-d H:i:s').' | created: '.$pertama->created_at.' | priority: '.$pertama->priority
        );

        $namaTeknisi = fn (string $number): ?string => Technician::find(Ticket::query()->where('ticket_number', $number)->value('assigned_technician_id'))?->technician_name;

        // Alias ejaan dinormalisasi ke master teknisi kanonik.
        $this->assertSame('Abdullah Tajuddin', $pertama->assignedTechnician?->technician_name, '90001: '.($pertama->assignedTechnician?->technician_name ?? 'null'));
        $this->assertSame(1, $pertama->assignments()->count());

        // Gabungan nama "&" -> teknisi pertama.
        $this->assertSame('Andi Erlangga', $namaTeknisi('TIK-90002'), '90002: '.($namaTeknisi('TIK-90002') ?? 'null'));
        $this->assertSame('Mirza Zulham', $namaTeknisi('TIK-90003'), '90003: '.($namaTeknisi('TIK-90003') ?? 'null'));
        $this->assertSame('Abdullah Tajuddin', $namaTeknisi('TIK-90004'), '90004: '.($namaTeknisi('TIK-90004') ?? 'null'));

        // Tanpa teknisi tetap tanpa penugasan.
        $this->assertNull(Ticket::query()->where('ticket_number', 'TIK-90005')->value('assigned_technician_id'), '90005 tidak harus kosong');

        // Master teknisi yang tercipta hanya nama kanonik.
        $daftar = Technician::query()->orderBy('technician_name')->pluck('technician_name')->implode(', ');
        $this->assertTrue(
            Technician::query()->where('technician_name', 'like', '%Tauddin%')->doesntExist(),
            "Teknisi tercipta: {$daftar}"
        );

        $auditCount = AuditLog::query()->where('action', 'created')->count();
        $this->assertSame(5, $auditCount, "Audit created: {$auditCount}");
    }

    public function test_impor_legacy_melewati_duplikat_dan_status_tak_dikenal(): void
    {
        $this->makeTicket('TIK-90001', now());

        $csv = implode("\n", [
            'TicketID,Tanggal,NamaLengkap,Departemen,Kategori,Kendala,Priority,Status,Teknisi,Catatan',
            'TIK-90001,26/02/2026 14:43:00,Anonim,IT,Software,duplikat,Low,Resolved,Andi Erlangga,',
            'TIK-90002,27/02/2026 14:43:00,Anonim,IT,Software,status aneh,Low,Done,Andi Erlangga,',
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.tickets.import'), [
            'file' => UploadedFile::fake()->createWithContent('legacy.csv', $csv),
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success', function (string $message) {
            return str_contains($message, '0 tiket dibuat')
                && str_contains($message, '2 dilewati')
                && str_contains($message, '(duplikat)')
                && str_contains($message, '(status tidak dikenal)');
        });

        $this->assertSame(1, Ticket::query()->count());
    }

    public function test_impor_format_ekspor_mempertahankan_status_dan_tanggal(): void
    {
        $csv = implode("\n", [
            'ticket_number,created_at,requester_name,category_name,priority,status,technician_name,sla_due_at,resolved_at,closed_at,description',
            'HIS-0001,2026-02-26 14:43:00,nur fadiah,Network,Urgent,Resolved,Abdullah Tauddin,2026-02-26 16:43:00,2026-02-26 15:30:00,,perbaikan excel',
            'HIS-0002,2026-03-01 08:00:00,Angela,Hardware,High,In Progress,A Erlangga,,, ,wifi putus',
            'HIS-0003,2026-03-02 09:00:00,Budi Santoso,Software,Low,Open,,,,abc-bukan-tanggal,tanggal resolved rusak',
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.tickets.import'), [
            'file' => UploadedFile::fake()->createWithContent('histori.csv', $csv),
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success', fn (string $message) => str_contains($message, '3 tiket dibuat'));

        // Histori lengkap tersimpan persis; SLA memakai nilai dari file.
        $pertama = Ticket::query()->where('ticket_number', 'HIS-0001')->first();
        $this->assertNotNull($pertama);
        $this->assertSame(Ticket::STATUS_RESOLVED, $pertama->status);
        $this->assertSame('2026-02-26 14:43:00', $pertama->created_at?->format('Y-m-d H:i:s'));
        $this->assertSame('2026-02-26 16:43:00', $pertama->sla_due_at?->format('Y-m-d H:i:s'));
        $this->assertSame('2026-02-26 15:30:00', $pertama->resolved_at?->format('Y-m-d H:i:s'));
        $this->assertNull($pertama->closed_at);

        // Status In Progress dipertahankan; SLA kosong dihitung dari created_at + durasi High (8 jam).
        $kedua = Ticket::query()->where('ticket_number', 'HIS-0002')->first();
        $this->assertSame(Ticket::STATUS_IN_PROGRESS, $kedua->status);
        $this->assertSame('2026-03-01 16:00:00', $kedua->sla_due_at?->format('Y-m-d H:i:s'));
        $this->assertNull($kedua->resolved_at);

        // resolved_at rusak -> dikosongkan tanpa menolak baris; SLA Low = created + 120 jam.
        $ketiga = Ticket::query()->where('ticket_number', 'HIS-0003')->first();
        $this->assertNotNull($ketiga);
        $this->assertSame(Ticket::STATUS_OPEN, $ketiga->status);
        $this->assertNull($ketiga->resolved_at);
        $this->assertSame('2026-03-07 09:00:00', $ketiga->sla_due_at?->format('Y-m-d H:i:s'));
    }

    public function test_impor_menolak_tanggal_dua_digit_tahun(): void
    {
        $csv = implode("\n", [
            'ticket_number,created_at,requester_name,category_name,priority,status,technician_name,sla_due_at,resolved_at,closed_at,description',
            'YY-0001,28-03-02 08:38,Anonim,IT,Low,,,, , ,tahun dua digit harus ditolak',
            'YY-0002,2026-03-02 08:38:00,Anonim,IT,Low,,,,,,baris valid',
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.tickets.import'), [
            'file' => UploadedFile::fake()->createWithContent('yy.csv', $csv),
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success', function (string $message) {
            return str_contains($message, '1 tiket dibuat')
                && str_contains($message, '1 dilewati')
                && str_contains($message, '(tanggal tidak valid: 28-03-02 08:38)');
        });

        $this->assertFalse(Ticket::query()->where('ticket_number', 'YY-0001')->exists());
        $this->assertTrue(Ticket::query()->where('ticket_number', 'YY-0002')->exists());
    }

    public function test_impor_legacy_menyimpan_departemen(): void
    {
        $csv = implode("\n", [
            'TicketID,Tanggal,NamaLengkap,Departemen,Kategori,Kendala,Priority,Status,Teknisi,Catatan',
            'TIK-DEPT-01,26/02/2026 14:43:00,nur fadiah,HRGA,Network,perbaikan excel,Low,Open,,',
        ]);

        $this->actingAs($this->admin)
            ->post(route('admin.tickets.import'), [
                'file' => UploadedFile::fake()->createWithContent('dept.csv', $csv),
            ])
            ->assertSessionHasNoErrors();

        $this->assertSame('HRGA', Ticket::query()->where('ticket_number', 'TIK-DEPT-01')->value('department'));
    }

    public function test_impor_ekspor_terbaru_dengan_kolom_department(): void
    {
        // Header ekspor baru (12 kolom) + satu baris berisi department.
        $csv = implode("\n", [
            'ticket_number,created_at,requester_name,department,category_name,priority,status,technician_name,sla_due_at,resolved_at,closed_at,description',
            'DEP-0001,2026-08-01 09:00:00,Budi Santoso,FAT,Hardware,High,Open,,, , ,laptop mati',
        ]);

        $this->actingAs($this->admin)
            ->post(route('admin.tickets.import'), [
                'file' => UploadedFile::fake()->createWithContent('baru.csv', $csv),
            ])
            ->assertSessionHasNoErrors();

        $ticket = Ticket::query()->where('ticket_number', 'DEP-0001')->first();
        $this->assertNotNull($ticket);
        $this->assertSame('FAT', $ticket->department);
    }

    public function test_impor_ekspor_lama_tanpa_department_tetap_diterima(): void
    {
        // Varian ekspor lama: 11 kolom tanpa department.
        $csv = implode("\n", [
            'ticket_number,created_at,requester_name,category_name,priority,status,technician_name,sla_due_at,resolved_at,closed_at,description',
            'OLD-0001,2026-08-02 09:00:00,Pelapor Lama,Software,Low,,,, , ,regresi format lama',
        ]);

        $this->actingAs($this->admin)
            ->post(route('admin.tickets.import'), [
                'file' => UploadedFile::fake()->createWithContent('lama.csv', $csv),
            ])
            ->assertSessionHasNoErrors();

        $ticket = Ticket::query()->where('ticket_number', 'OLD-0001')->first();
        $this->assertNotNull($ticket);
        $this->assertNull($ticket->department);
    }

    private function makeTicket(string $number, CarbonInterface $createdAt): Ticket
    {
        $technician = Technician::query()->first();

        $ticket = Ticket::query()->create([
            'ticket_number' => $number,
            'requester_name' => 'Budi Santoso',
            'category_id' => $this->category->id,
            'priority' => Ticket::PRIORITY_MEDIUM,
            'description' => 'Deskripsi pengujian CSV.',
            'status' => Ticket::STATUS_OPEN,
            'sla_due_at' => $createdAt->copy()->addHours(8),
            'assigned_technician_id' => $technician?->id,
        ]);

        $ticket->forceFill(['created_at' => $createdAt])->save();

        return $ticket;
    }
}
