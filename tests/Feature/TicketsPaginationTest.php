<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketsPaginationTest extends TestCase
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

    private function createTickets(int $count): void
    {
        for ($i = 1; $i <= $count; $i++) {
            $ticket = Ticket::query()->create([
                'ticket_number' => 'TCK-2026-'.str_pad((string) $i, 5, '0', STR_PAD_LEFT),
                'requester_name' => 'Budi Santoso',
                'category_id' => $this->category->id,
                'priority' => Ticket::PRIORITY_MEDIUM,
                'description' => 'Deskripsi masalah tiket '.$i,
                'status' => Ticket::STATUS_OPEN,
                'sla_due_at' => now()->addHours(8),
            ]);

            // Semakin besar i, semakin baru — daftar diurutkan terbaru dulu.
            $ticket->forceFill(['created_at' => now()->subHours($count - $i)])->save();
        }
    }

    public function test_enam_belas_tiket_membagi_dua_halaman_dengan_link_paginasi(): void
    {
        $this->createTickets(16);

        $pageOne = $this->actingAs($this->admin)->get(route('admin.tickets.index'));

        $pageOne->assertOk();
        $pageOne->assertSee('dari 16 data');
        $pageOne->assertSee('TCK-2026-00016');
        $pageOne->assertSee('page=2');
        $pageOne->assertDontSee('TCK-2026-00001');

        // Urutan nomor menurun: terbesar di atas.
        $pageOne->assertSeeInOrder(['TCK-2026-00016', 'TCK-2026-00015', 'TCK-2026-00014']);

        $pageTwo = $this->actingAs($this->admin)->get(route('admin.tickets.index', ['page' => 2]));

        $pageTwo->assertOk();
        $pageTwo->assertSee('TCK-2026-00001');
        $pageTwo->assertDontSee('TCK-2026-00016');
    }

    public function test_query_string_filter_ikut_pada_link_paginasi(): void
    {
        $this->createTickets(16);

        $response = $this->actingAs($this->admin)->get(route('admin.tickets.index', ['q' => 'budi']));

        $response->assertOk();
        $response->assertSee('q=budi&page=2');
    }

    public function test_satu_halaman_hanya_tampilkan_ringkasan(): void
    {
        $this->createTickets(3);

        $response = $this->actingAs($this->admin)->get(route('admin.tickets.index'));

        $response->assertOk();
        $response->assertSee('Menampilkan 1&ndash;3 dari 3 data', false);
        $response->assertDontSee('page=2');
    }
}
