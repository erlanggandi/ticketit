<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MasterDataTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create();
    }

    public function test_admin_dapat_membuat_dan_mengedit_kategori(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/categories', [
            'category_name' => 'Perangkat Jaringan',
            'is_active' => '1',
        ]);

        $response->assertSessionHasNoErrors();

        $category = Category::query()->where('category_name', 'Perangkat Jaringan')->first();
        $this->assertNotNull($category);

        $this->actingAs($this->admin)
            ->patch("/admin/categories/{$category->id}", ['category_name' => 'Jaringan & Internet'])
            ->assertSessionHasNoErrors();

        $this->assertSame('Jaringan & Internet', $category->fresh()->category_name);
    }

    public function test_nama_kategori_duplikat_ditolak(): void
    {
        Category::query()->create(['category_name' => 'Hardware']);

        $this->actingAs($this->admin)
            ->post('/admin/categories', ['category_name' => 'Hardware'])
            ->assertSessionHasErrors('category_name');
    }

    public function test_kategori_dengan_tiket_tidak_bisa_dihapus(): void
    {
        $category = Category::query()->create(['category_name' => 'Hardware']);
        $category->tickets()->create([
            'ticket_number' => 'TCK-2026-00001',
            'requester_name' => 'Budi',
            'priority' => 'Low',
            'description' => 'Deskripsi masalah pengujian hapus kategori.',
            'status' => 'Open',
            'sla_due_at' => now()->addDays(5),
        ]);

        $this->actingAs($this->admin)
            ->delete("/admin/categories/{$category->id}")
            ->assertSessionHasErrors('delete');

        $this->assertTrue($category->fresh()->exists);
    }

    public function test_admin_dapat_kelola_karyawan(): void
    {
        $this->actingAs($this->admin)
            ->post('/admin/employees', [
                'employee_name' => 'Siti Rahayu',
                'department' => 'HRD',
                'contact' => 'siti@company.co.id',
                'is_active' => '1',
            ])
            ->assertSessionHasNoErrors();

        $employee = Employee::query()->where('employee_name', 'Siti Rahayu')->first();
        $this->assertNotNull($employee);

        $this->actingAs($this->admin)
            ->patch("/admin/employees/{$employee->id}/toggle")
            ->assertSessionHasNoErrors();

        $this->assertFalse($employee->fresh()->is_active);
    }

    public function test_sla_setting_dapat_disimpan(): void
    {
        $this->actingAs($this->admin)
            ->put('/admin/sla-settings', [
                'durations' => [
                    'critical' => 4,
                    'high' => 8,
                    'medium' => 48,
                    'low' => 100,
                ],
            ])
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('sla_settings', ['priority' => 'Medium', 'duration_hours' => 48]);
        $this->assertDatabaseHas('sla_settings', ['priority' => 'Low', 'duration_hours' => 100]);
    }

    public function test_guest_ditolak_mengakses_master_data(): void
    {
        $this->get('/admin/categories')->assertRedirect(route('admin.login'));
        $this->get('/admin/employees')->assertRedirect(route('admin.login'));
        $this->get('/admin/sla-settings')->assertRedirect(route('admin.login'));
    }
}
