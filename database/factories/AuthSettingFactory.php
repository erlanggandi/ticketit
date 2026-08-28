<?php

namespace Database\Factories;

use App\Models\AuthSetting;
use Illuminate\Database\Eloquent\Factories\Factory;

class AuthSettingFactory extends Factory
{
    protected $model = AuthSetting::class;

    public function definition(): array
    {
        return [
            'password_min_length' => 8,
            'password_require_uppercase' => true,
            'password_require_lowercase' => true,
            'password_require_number' => true,
            'password_require_symbol' => true,
            'login_attempts_before_lockout' => 5,
            'lockout_duration_minutes' => 30,
            'session_timeout_minutes' => 60,
            'remember_me_duration_minutes' => 1440,
        ];
    }
}
