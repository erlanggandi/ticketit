<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Employee;
use App\Models\SlaSetting;
use App\Models\TelegramSetting;
use Illuminate\Database\Seeder;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        $categories = ['Hardware', 'Software', 'Jaringan', 'Akses/Akun', 'Lainnya'];

        foreach ($categories as $name) {
            Category::query()->firstOrCreate(['category_name' => $name], ['is_active' => true]);
        }

        $slaDefaults = [
            'Critical' => 4,
            'High' => 8,
            'Medium' => 72,
            'Low' => 120,
        ];

        foreach ($slaDefaults as $priority => $hours) {
            SlaSetting::query()->firstOrCreate(
                ['priority' => $priority],
                ['duration_hours' => $hours]
            );
        }

        Employee::query()->insertOrIgnore([
            ['employee_name' => 'Budi Santoso', 'department' => 'Finance', 'contact' => 'budi.santoso@company.co.id', 'is_active' => true],
            ['employee_name' => 'Siti Rahayu', 'department' => 'HRD', 'contact' => 'siti.rahayu@company.co.id', 'is_active' => true],
            ['employee_name' => 'Andi Wijaya', 'department' => 'Operations', 'contact' => 'andi.wijaya@company.co.id', 'is_active' => true],
            ['employee_name' => 'Dewi Lestari', 'department' => 'Marketing', 'contact' => 'dewi.lestari@company.co.id', 'is_active' => true],
            ['employee_name' => 'Rizky Pratama', 'department' => 'Sales', 'contact' => 'rizky.pratama@company.co.id', 'is_active' => true],
            ['employee_name' => 'Maya Anggraini', 'department' => 'Legal', 'contact' => 'maya.anggraini@company.co.id', 'is_active' => true],
        ]);

        if (TelegramSetting::query()->doesntExist()) {
            TelegramSetting::query()->create([
                'bot_token' => '',
                'chat_id' => '',
                'message_template_new_ticket' => TelegramSetting::DEFAULT_TEMPLATE_NEW_TICKET,
                'message_template_sla_alert' => TelegramSetting::DEFAULT_TEMPLATE_SLA_ALERT,
            ]);
        }
    }
}
