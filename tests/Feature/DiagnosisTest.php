<?php

namespace Tests\Feature;

use App\Models\AiSetting;
use App\Models\Category;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class DiagnosisTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private Ticket $openTicket;

    private Ticket $closedTicket;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['is_active' => true]);

        $category = Category::query()->create(['category_name' => 'Software']);

        $this->openTicket = Ticket::query()->create([
            'ticket_number' => 'TCK-2026-00001',
            'requester_name' => 'Budi Santoso',
            'category_id' => $category->id,
            'priority' => Ticket::PRIORITY_HIGH,
            'description' => 'Aplikasi akuntansi crash saat membuka laporan bulanan.',
            'status' => Ticket::STATUS_OPEN,
            'sla_due_at' => now()->addHours(8),
        ]);

        $this->closedTicket = Ticket::query()->create([
            'ticket_number' => 'TCK-2026-00002',
            'requester_name' => 'Citra Dewi',
            'category_id' => $category->id,
            'priority' => Ticket::PRIORITY_LOW,
            'description' => 'Aplikasi akuntansi crash saat membuka laporan bulanan.',
            'status' => Ticket::STATUS_CLOSED,
            'sla_due_at' => now()->addHours(8),
        ]);
    }

    public function test_admin_dapat_memilih_tiket_open_dan_mendapatkan_diagnosis_ai(): void
    {
        AiSetting::query()->create([
            'provider' => AiSetting::PROVIDER_GEMINI,
            'api_key' => 'test-api-key',
            'base_url' => null,
            'model' => 'gemini-2.0-flash',
            'temperature' => null,
            'max_tokens' => null,
            'updated_by' => null,
        ]);

        Http::fake([
            'generativelanguage.googleapis.com/*' => Http::response([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                ['text' => 'Kemungkinan komponen aplikasi bermasalah. Periksa log error dan update versi aplikasi.'],
                            ],
                        ],
                    ],
                ],
            ]),
        ]);

        $this->actingAs($this->admin)
            ->get('/admin/diagnosis')
            ->assertOk()
            ->assertSee($this->openTicket->ticket_number)
            ->assertDontSee($this->closedTicket->ticket_number);

        $this->actingAs($this->admin)
            ->get('/admin/diagnosis?ticket_id='.$this->openTicket->id)
            ->assertOk()
            ->assertSee('Kemungkinan komponen aplikasi bermasalah. Periksa log error dan update versi aplikasi.');

        $this->assertDatabaseCount('tickets', 2);
    }
}
