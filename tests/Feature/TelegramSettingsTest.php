<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\TelegramSetting;
use App\Models\Ticket;
use App\Models\User;
use App\Services\TelegramService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class TelegramSettingsTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create();
    }

    public function test_admin_dapat_menyimpan_pengaturan_telegram(): void
    {
        $response = $this->actingAs($this->admin)->put('/admin/telegram-settings', [
            'bot_token' => '123456:ABC-TOKEN-TEST',
            'chat_id' => '-1001234567890',
            'message_template_new_ticket' => "Tiket Baru: {ticket_number}\nPrioritas: {priority}",
            'message_template_sla_alert' => '[SLA ALERT] {ticket_number}',
        ]);

        $response->assertRedirect()->assertSessionHasNoErrors();

        $setting = TelegramSetting::current();

        $this->assertNotNull($setting);
        $this->assertSame('-1001234567890', $setting->chat_id);
        $this->assertSame('123456:ABC-TOKEN-TEST', $setting->bot_token);
        $this->assertStringNotContainsString('ABC-TOKEN-TEST', $setting->getRawOriginal('bot_token'));
    }

    public function test_token_dimask_di_halaman_pengaturan(): void
    {
        TelegramSetting::query()->create([
            'bot_token' => '9876543210:SUPER-SECRET-TOKEN',
            'chat_id' => '12345',
            'message_template_new_ticket' => 'x',
            'message_template_sla_alert' => 'y',
        ]);

        $html = $this->actingAs($this->admin)->get('/admin/telegram-settings')->getContent();

        $this->assertStringNotContainsString('SUPER-SECRET-TOKEN', $html);
        $this->assertStringContainsString('****', $html);
    }

    public function test_simpan_ulang_tanpa_isi_token_pertahankan_token_lama(): void
    {
        TelegramSetting::query()->create([
            'bot_token' => '9876543210:SUPER-SECRET-TOKEN',
            'chat_id' => 'lama',
            'message_template_new_ticket' => 'a',
            'message_template_sla_alert' => 'b',
        ]);

        $this->actingAs($this->admin)->put('/admin/telegram-settings', [
            'bot_token' => '',
            'chat_id' => 'baru',
            'message_template_new_ticket' => 'a2',
            'message_template_sla_alert' => 'b2',
        ])->assertSessionHasNoErrors();

        $setting = TelegramSetting::current();
        $this->assertSame('9876543210:SUPER-SECRET-TOKEN', $setting->bot_token);
        $this->assertSame('baru', $setting->chat_id);
    }

    public function test_test_kirim_pesan_menggunakan_konfigurasi_tersimpan(): void
    {
        Http::fake([
            'api.telegram.org/*' => Http::response(['ok' => true, 'result' => ['message_id' => 1]]),
        ]);

        TelegramSetting::query()->create([
            'bot_token' => '111111:AAA',
            'chat_id' => '-100999',
            'message_template_new_ticket' => 't1',
            'message_template_sla_alert' => 't2',
        ]);

        $response = $this->actingAs($this->admin)->postJson('/admin/telegram-settings/test');

        $response->assertOk()->assertJson(['ok' => true]);

        Http::assertSent(function ($request) {
            return str_contains($request->url(), '/bot111111:AAA/sendMessage')
                && $request['chat_id'] === '-100999'
                && str_contains($request['text'], 'Test');
        });
    }

    public function test_kegagalan_api_tidak_melempar_error_500(): void
    {
        Http::fake([
            'api.telegram.org/*' => Http::response(['ok' => false, 'description' => 'Bad Request: chat not found'], 400),
        ]);

        TelegramSetting::query()->create([
            'bot_token' => '111111:AAA',
            'chat_id' => 'salah',
            'message_template_new_ticket' => 't1',
            'message_template_sla_alert' => 't2',
        ]);

        $this->actingAs($this->admin)
            ->postJson('/admin/telegram-settings/test')
            ->assertOk()
            ->assertJson(['ok' => false]);
    }

    public function test_template_default_mencakup_departemen(): void
    {
        $this->assertStringContainsString('{department}', TelegramSetting::DEFAULT_TEMPLATE_NEW_TICKET);
        $this->assertStringContainsString('{department}', TelegramSetting::DEFAULT_TEMPLATE_SLA_ALERT);
        $this->assertContains('{department}', TelegramSetting::TEMPLATE_VARIABLES);
    }

    public function test_render_template_mengganti_departemen(): void
    {
        $category = Category::query()->create(['category_name' => 'Hardware']);
        $ticket = Ticket::query()->create([
            'ticket_number' => 'TCK-2026-00001',
            'requester_name' => 'Budi Santoso',
            'department' => 'Finance',
            'category_id' => $category->id,
            'priority' => Ticket::PRIORITY_MEDIUM,
            'description' => 'Monitor rusak.',
            'status' => Ticket::STATUS_OPEN,
            'sla_due_at' => now()->addDays(1),
        ]);

        $message = app(TelegramService::class)->renderTemplate(
            "Departemen: {department}\nRequester: {requester_name}",
            $ticket
        );

        $this->assertStringContainsString('Finance', $message);
    }

    public function test_render_template_departemen_kosong_jadi_tanda_hubung(): void
    {
        $category = Category::query()->create(['category_name' => 'Software']);
        $ticket = Ticket::query()->create([
            'ticket_number' => 'TCK-2026-00002',
            'requester_name' => 'Nama Manual',
            'category_id' => $category->id,
            'priority' => Ticket::PRIORITY_LOW,
            'description' => 'Aplikasi error.',
            'status' => Ticket::STATUS_OPEN,
            'sla_due_at' => now()->addDays(1),
        ]);

        $message = app(TelegramService::class)->renderTemplate('Departemen: {department}', $ticket);

        $this->assertStringContainsString('Departemen: -', $message);
    }

    public function test_migrasi_template_menyisipkan_departemen_setelah_requester(): void
    {
        $migration = require database_path('migrations/2026_08_28_152040_add_department_to_telegram_templates.php');

        $old = "Tiket Baru: {ticket_number}\nRequester: {requester_name}\nKategori: {category}";
        $expected = "Tiket Baru: {ticket_number}\nRequester: {requester_name}\nDepartemen: {department}\nKategori: {category}";

        $this->assertSame($expected, $migration::insertDepartmentLine($old));
    }

    public function test_migrasi_template_tidak_duplikat_jika_sudah_ada_departemen(): void
    {
        $migration = require database_path('migrations/2026_08_28_152040_add_department_to_telegram_templates.php');

        $already = "Requester: {requester_name}\nDepartemen: {department}\nKategori: {category}";

        $this->assertSame($already, $migration::insertDepartmentLine($already));
    }
}
