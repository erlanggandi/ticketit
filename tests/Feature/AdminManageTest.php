<?php

namespace Tests\Feature;

use App\Models\AuthSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminManageTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'name' => 'Test Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password123'),
            'is_active' => true,
        ]);

        AuthSetting::factory()->create([
            'password_min_length' => 8,
            'password_require_uppercase' => true,
            'password_require_lowercase' => true,
            'password_require_number' => true,
            'password_require_symbol' => true,
        ]);

        $this->actingAs($this->admin);
    }

    public function test_admin_can_access_manage_page(): void
    {
        $response = $this->get(route('admin.admin-manage.index'));

        $response->assertStatus(200);
        $response->assertSee('Manajemen Admin');
        $response->assertSee('Admin Terdaftar');
        $response->assertSee('Akun Saya');
        $response->assertSee('Ubah Password');
    }

    public function test_admin_can_update_password_with_valid_data(): void
    {
        $response = $this->put(route('admin.admin-manage.updatePassword'), [
            'current_password' => 'password123',
            'password' => 'newPassword123!',
            'password_confirmation' => 'newPassword123!',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertTrue(
            password_verify('newPassword123!', $this->admin->fresh()->password)
        );
    }

    public function test_admin_cannot_update_password_with_wrong_current_password(): void
    {
        $response = $this->put(route('admin.admin-manage.updatePassword'), [
            'current_password' => 'wrongpassword',
            'password' => 'newPassword123!',
            'password_confirmation' => 'newPassword123!',
        ]);

        $response->assertSessionHasErrors('current_password');
    }

    public function test_admin_cannot_update_password_with_too_short_password(): void
    {
        $response = $this->put(route('admin.admin-manage.updatePassword'), [
            'current_password' => 'password123',
            'password' => '123',
            'password_confirmation' => '123',
        ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_admin_cannot_update_password_with_mismatched_confirmation(): void
    {
        $response = $this->put(route('admin.admin-manage.updatePassword'), [
            'current_password' => 'password123',
            'password' => 'newPassword123!',
            'password_confirmation' => 'differentPassword!',
        ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_old_password_no_longer_works_after_update(): void
    {
        $this->put(route('admin.admin-manage.updatePassword'), [
            'current_password' => 'password123',
            'password' => 'newPassword123!',
            'password_confirmation' => 'newPassword123!',
        ])->assertRedirect();

        auth()->logout();

        // Old password should fail
        $this->from(route('admin.login'))->post(route('admin.login.attempt'), [
            'email' => $this->admin->email,
            'password' => 'password123',
        ])->assertRedirect(route('admin.login'))->assertSessionHasErrors('email');

        // New password should work
        $this->post(route('admin.login.attempt'), [
            'email' => $this->admin->email,
            'password' => 'newPassword123!',
        ])->assertRedirect(route('admin.dashboard'));
    }
}
