<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->firstOrCreate(
            ['email' => 'admin@helpdesk.local'],
            [
                'name' => 'Administrator IT',
                'password' => 'password',
                'is_active' => true,
            ]
        );
    }
}
