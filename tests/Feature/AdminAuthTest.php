<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_harus_login_untuk_mengakses_dashboard(): void
    {
        $this->get('/admin/dashboard')->assertRedirect(route('admin.login'));
        $this->get('/admin/tickets')->assertRedirect(route('admin.login'));
        $this->get('/admin/reports')->assertRedirect(route('admin.login'));
    }

    public function test_login_berhasil_dengan_kredensial_benar(): void
    {
        $user = User::query()->create([
            'name' => 'Admin IT',
            'email' => 'admin@helpdesk.local',
            'password' => 'password',
            'is_active' => true,
        ]);

        $response = $this->post('/admin/login', [
            'email' => 'admin@helpdesk.local',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_gagal_dengan_kredensial_salah(): void
    {
        User::query()->create([
            'name' => 'Admin IT',
            'email' => 'admin@helpdesk.local',
            'password' => 'password',
        ]);

        $this->post('/admin/login', [
            'email' => 'admin@helpdesk.local',
            'password' => 'wrong-password',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_akun_tidak_aktif_tidak_bisa_login(): void
    {
        User::query()->create([
            'name' => 'Nonaktif',
            'email' => 'off@helpdesk.local',
            'password' => 'password',
            'is_active' => false,
        ]);

        $this->post('/admin/login', [
            'email' => 'off@helpdesk.local',
            'password' => 'password',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_logout_menghapus_session(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/admin/logout')
            ->assertRedirect(route('admin.login'));

        $this->assertGuest();
    }
}
