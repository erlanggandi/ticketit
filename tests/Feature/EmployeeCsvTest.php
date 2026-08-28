<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class EmployeeCsvTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['is_active' => true]);
    }

    public function test_ekspor_csv_karyawan_menghasilkan_isi_dan_urutan_nama(): void
    {
        Employee::query()->create(['employee_name' => 'Zaki Ramadhan', 'is_active' => true]);
        Employee::query()->create(['employee_name' => 'Andi Pratama', 'department' => 'IT', 'contact' => 'andi@co.id', 'is_active' => false]);

        $response = $this->actingAs($this->admin)->get(route('admin.employees.export'));

        $response->assertOk();
        $this->assertStringContainsString('text/csv', (string) $response->headers->get('Content-Type'));
        $this->assertStringContainsString('karyawan-ekspor-', (string) $response->headers->get('Content-Disposition'));

        $content = $response->streamedContent();

        $this->assertStringContainsString('employee_name,department,contact,is_active', str_replace("\xEF\xBB\xBF", '', $content));
        $this->assertSame(
            1,
            preg_match('/Andi Pratama.*Zaki Ramadhan/s', $content),
            'Urutan harus berdasar nama menaik.'
        );
        $this->assertStringContainsString('Nonaktif', $content);
    }

    public function test_impor_upsert_membuat_dan_memperbarui_karyawan(): void
    {
        Employee::query()->create(['employee_name' => 'Budi Santoso', 'department' => 'Lama', 'is_active' => false]);

        $csv = implode("\n", [
            'employee_name,department,contact,is_active',
            'Budi Santoso,IT,budi@co.id,Aktif',
            'Siti Rahayu,HRD,siti@co.id,Nonaktif',
            ',Tanpa Nama,,',
            'Orang Status Aneh,IT,x@x.id,Ajaib',
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.employees.import'), [
            'file' => UploadedFile::fake()->createWithContent('karyawan.csv', $csv),
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success', function (string $message) {
            return str_contains($message, '1 karyawan dibuat')
                && str_contains($message, '1 diperbarui')
                && str_contains($message, '2 dilewati')
                && str_contains($message, '(tanpa nama)')
                && str_contains($message, '(status tidak valid)');
        });

        // Existing diperbarui dari file.
        $budi = Employee::query()->where('employee_name', 'Budi Santoso')->first();
        $this->assertSame('IT', $budi->department);
        $this->assertTrue($budi->is_active);

        // Baru dibuat sesuai file.
        $siti = Employee::query()->where('employee_name', 'Siti Rahayu')->first();
        $this->assertNotNull($siti);
        $this->assertFalse($siti->is_active);

        $this->assertSame(2, Employee::query()->count());
        $this->assertSame(1, AuditLog::query()->where('action', 'created')->where('entity_type', 'Employee')->count());
        $this->assertSame(1, AuditLog::query()->where('action', 'updated')->where('entity_type', 'Employee')->count());
    }

    public function test_impor_tanpa_status_mempertahankan_yang_lama(): void
    {
        Employee::query()->create(['employee_name' => 'Rina Kartika', 'is_active' => false]);

        $csv = implode("\n", [
            'employee_name,department,contact,is_active',
            'rina kartika,Gudang,,',
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.employees.import'), [
            'file' => UploadedFile::fake()->createWithContent('karyawan.csv', $csv),
        ]);

        $response->assertSessionHasNoErrors();

        // Cocok tanpa membedakan huruf besar/kecil; status kosong mempertahankan nilai lama.
        $rina = Employee::query()->whereRaw('lower(employee_name) = ?', ['rina kartika'])->first();
        $this->assertNotNull($rina);
        $this->assertFalse($rina->is_active);
        $this->assertSame('Gudang', $rina->department);
        $this->assertSame(1, Employee::query()->count());
    }

    public function test_impor_csv_delimiter_titik_koma_dengan_bom_diterima(): void
    {
        $csv = "\xEF\xBB\xBF".implode("\n", [
            'employee_name;department;contact;is_active',
            'Hendra Gunawan;TE;hendra@co.id;Aktif',
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.employees.import'), [
            'file' => UploadedFile::fake()->createWithContent('hendra.csv', $csv),
        ]);

        $response->assertSessionHasNoErrors();

        $hendra = Employee::query()->where('employee_name', 'Hendra Gunawan')->first();
        $this->assertNotNull($hendra);
        $this->assertTrue($hendra->is_active);
    }

    public function test_impor_header_salah_menampilkan_kolom_terbaca(): void
    {
        $csv = "no,nama\nIMP-1,Budi\n";

        $response = $this->actingAs($this->admin)->post(route('admin.employees.import'), [
            'file' => UploadedFile::fake()->createWithContent('salah.csv', $csv),
        ]);

        $response->assertSessionHasErrors('file');
        $this->assertSame(0, Employee::query()->count());
        $this->assertTrue(
            str_contains((string) session('errors')?->first('file'), 'Kolom terbaca dari file Anda: [no | nama]'),
            'Pesan error harus menampilkan kolom yang terbaca.'
        );
    }
}
