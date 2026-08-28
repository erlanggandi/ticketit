<?php

namespace Tests\Feature;

use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SiteSettingTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create();
        $this->actingAs($this->admin);

        Storage::fake('public');
    }

    public function test_admin_can_view_site_settings_page(): void
    {
        $response = $this->get(route('admin.site-settings.edit'));

        $response->assertStatus(200);
        $response->assertSee('Pengaturan Website');
        $response->assertSee('Identitas Website');
        $response->assertSee('Tampilan Form Tiket');
        $response->assertSee('Logo & Favicon');
    }

    public function test_settings_are_created_with_defaults_on_first_view(): void
    {
        $this->assertDatabaseCount('site_settings', 0);

        $this->get(route('admin.site-settings.edit'));

        $this->assertDatabaseCount('site_settings', 1);
        $setting = SiteSetting::first();
        $this->assertEquals('IT', $setting->app_short_name);
    }

    public function test_admin_can_update_text_fields(): void
    {
        $setting = SiteSetting::factory()->create();

        $response = $this->put(route('admin.site-settings.update'), [
            'app_name' => 'Helpdesk Updated',
            'app_short_name' => 'HD',
            'tagline' => 'Tim Pendukung',
            'form_title' => 'Lapor Kendala',
            'form_description' => 'Deskripsi baru',
            'footer_text' => 'Footer baru',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $setting->refresh();
        $this->assertEquals('Helpdesk Updated', $setting->app_name);
        $this->assertEquals('HD', $setting->app_short_name);
        $this->assertEquals('Tim Pendukung', $setting->tagline);
        $this->assertEquals('Lapor Kendala', $setting->form_title);
        $this->assertEquals('Deskripsi baru', $setting->form_description);
        $this->assertEquals('Footer baru', $setting->footer_text);
        $this->assertEquals($this->admin->id, $setting->updated_by);
    }

    public function test_required_fields_validation(): void
    {
        SiteSetting::factory()->create();

        $response = $this->put(route('admin.site-settings.update'), [
            'app_name' => '',
            'app_short_name' => '',
            'tagline' => '',
            'form_title' => '',
            'footer_text' => '',
        ]);

        $response->assertSessionHasErrors(['app_name', 'app_short_name', 'tagline', 'form_title', 'footer_text']);
    }

    public function test_admin_can_upload_logo(): void
    {
        SiteSetting::factory()->create();
        $logo = UploadedFile::fake()->image('logo.png', 100, 100);

        $response = $this->put(route('admin.site-settings.update'), [
            'app_name' => 'Test',
            'app_short_name' => 'T',
            'tagline' => 'Tag',
            'form_title' => 'Form',
            'footer_text' => 'Foot',
            'logo' => $logo,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $setting = SiteSetting::first();
        $this->assertNotNull($setting->logo_path);
        Storage::disk('public')->assertExists($setting->logo_path);
    }

    public function test_admin_can_upload_favicon(): void
    {
        SiteSetting::factory()->create();
        $favicon = UploadedFile::fake()->image('favicon.png', 32, 32);

        $response = $this->put(route('admin.site-settings.update'), [
            'app_name' => 'Test',
            'app_short_name' => 'T',
            'tagline' => 'Tag',
            'form_title' => 'Form',
            'footer_text' => 'Foot',
            'favicon' => $favicon,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $setting = SiteSetting::first();
        $this->assertNotNull($setting->favicon_path);
        Storage::disk('public')->assertExists($setting->favicon_path);
    }

    public function test_public_layout_displays_app_name_from_settings(): void
    {
        SiteSetting::factory()->create([
            'app_name' => 'Custom Helpdesk',
            'tagline' => 'Tagline Khusus',
        ]);

        $response = $this->get(route('tickets.create'));
        $response->assertStatus(200);
        $response->assertSee('Custom Helpdesk');
        $response->assertSee('Tagline Khusus');
    }

    public function test_public_create_displays_form_title_from_settings(): void
    {
        SiteSetting::factory()->create([
            'form_title' => 'Lapor Kendala Baru',
            'form_description' => 'Deskripsi form baru',
        ]);

        $response = $this->get(route('tickets.create'));
        $response->assertStatus(200);
        $response->assertSee('Lapor Kendala Baru');
        $response->assertSee('Deskripsi form baru');
    }

    public function test_guest_cannot_access_site_settings(): void
    {
        auth()->logout();

        $response = $this->get(route('admin.site-settings.edit'));
        $response->assertRedirect(route('admin.login'));
    }
}
