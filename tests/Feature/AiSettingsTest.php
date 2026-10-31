<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AiSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dapat_melihat_halaman_pengaturan_ai(): void
    {
        $this->actingAs(User::factory()->create(['is_active' => true]))
            ->get('/admin/ai-settings')
            ->assertOk()
            ->assertSee('Pengaturan AI');
    }

    public function test_test_koneksi_ai_mengembalikan_json_sukses(): void
    {
        Http::fake();

        $this->actingAs(User::factory()->create(['is_active' => true]))
            ->postJson('/admin/ai-settings/test', [
                'provider' => 'gemini',
                'api_key' => 'test-api-key',
                'model' => 'gemini-2.0-flash',
            ])
            ->assertOk()
            ->assertJsonPath('ok', true);
    }

    public function test_test_koneksi_ai_gagal_jika_api_key_belum_diisi(): void
    {
        $this->actingAs(User::factory()->create(['is_active' => true]))
            ->postJson('/admin/ai-settings/test', [
                'provider' => 'gemini',
                'api_key' => '',
                'model' => 'gemini-2.0-flash',
            ])
            ->assertOk()
            ->assertJsonPath('ok', false);
    }
}
