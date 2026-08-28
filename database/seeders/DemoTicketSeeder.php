<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Employee;
use App\Models\Ticket;
use Illuminate\Database\Seeder;

class DemoTicketSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment('production')) {
            return;
        }

        $categories = Category::query()->pluck('id', 'category_name');
        $employees = Employee::query()->get();

        if ($categories->isEmpty() || $employees->isEmpty()) {
            return;
        }

        $rows = [
            ['Budi Santoso', 'Hardware', 'High', 'Laptop tidak bisa booting, layar hitam saat dinyalakan.', Ticket::STATUS_OPEN, 0],
            ['Siti Rahayu', 'Akses/Akun', 'Medium', 'Tidak bisa login ke sistem payroll, password reset email tidak masuk.', Ticket::STATUS_OPEN, 1],
            ['Andi Wijaya', 'Jaringan', 'Critical', 'Internet kantor mati total sejak pagi, operasional terhenti.', Ticket::STATUS_IN_PROGRESS, 2],
            ['Dewi Lestari', 'Software', 'Low', 'Update lisensi Microsoft Office akan habis bulan depan.', Ticket::STATUS_IN_PROGRESS, 3],
            ['Rizky Pratama', 'Hardware', 'Medium', 'Printer ruang sales macet dan sering paper jam.', Ticket::STATUS_RESOLVED, 5],
            ['Maya Anggraini', 'Software', 'Low', 'Permintaan instalasi aplikasi PDF editor di laptop baru.', Ticket::STATUS_CLOSED, 8],
            ['Budi Santoso', 'Jaringan', 'High', 'Wi-Fi di area meeting room sangat lambat.', Ticket::STATUS_CLOSED, 10],
        ];

        $year = now()->format('Y');
        $seq = 1;

        foreach ($rows as [$name, $categoryName, $priority, $description, $status, $daysAgo]) {
            $employee = $employees->firstWhere('employee_name', $name);
            $createdAt = now()->subDays($daysAgo)->subHours(rand(1, 6));
            $dueAt = $createdAt->copy()->addHours(match ($priority) {
                Ticket::PRIORITY_CRITICAL => 4,
                Ticket::PRIORITY_HIGH => 8,
                Ticket::PRIORITY_MEDIUM => 72,
                default => 120,
            });

            $resolvedAt = in_array($status, [Ticket::STATUS_RESOLVED, Ticket::STATUS_CLOSED], true)
                ? $createdAt->copy()->addHours(rand(2, max(3, (int) $createdAt->diffInHours($dueAt))))
                : null;

            Ticket::query()->create([
                'ticket_number' => "TCK-{$year}-".str_pad((string) $seq++, 5, '0', STR_PAD_LEFT),
                'requester_name' => $name,
                'requester_id' => $employee?->id,
                'category_id' => $categories[$categoryName],
                'priority' => $priority,
                'description' => $description,
                'status' => $status,
                'sla_due_at' => $dueAt,
                'resolved_at' => $resolvedAt,
                'closed_at' => $status === Ticket::STATUS_CLOSED
                    ? ($resolvedAt?->copy()->addDay() ?? $createdAt->copy()->addDays(2))
                    : null,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }
    }
}
