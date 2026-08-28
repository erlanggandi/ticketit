<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeePaginationTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['is_active' => true]);
    }

    public function test_enam_belas_karyawan_membagi_dua_halaman(): void
    {
        for ($i = 1; $i <= 16; $i++) {
            Employee::query()->create([
                'employee_name' => 'Karyawan '.str_pad((string) $i, 2, '0', STR_PAD_LEFT),
            ]);
        }

        $pageOne = $this->actingAs($this->admin)->get(route('admin.employees.index'));

        $pageOne->assertOk();
        $pageOne->assertSee('dari 16 data');
        $pageOne->assertSee('Karyawan 01');
        $pageOne->assertSee('page=2');
        $pageOne->assertDontSee('Karyawan 16');
        $pageOne->assertSeeInOrder(['Karyawan 01', 'Karyawan 02']);

        $pageTwo = $this->actingAs($this->admin)->get(route('admin.employees.index', ['page' => 2]));

        $pageTwo->assertOk();
        $pageTwo->assertSee('Karyawan 16');
        $pageTwo->assertDontSee('Karyawan 01');
    }

    public function test_satu_halaman_hanya_tampilkan_ringkasan(): void
    {
        Employee::query()->create(['employee_name' => 'Budi Santoso']);

        $response = $this->actingAs($this->admin)->get(route('admin.employees.index'));

        $response->assertOk();
        $response->assertSee('Menampilkan 1&ndash;1 dari 1 data', false);
        $response->assertDontSee('page=2');
    }
}
