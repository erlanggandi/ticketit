<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('auth_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('password_min_length')->default(8);
            $table->boolean('password_require_uppercase')->default(true);
            $table->boolean('password_require_lowercase')->default(true);
            $table->boolean('password_require_number')->default(true);
            $table->boolean('password_require_symbol')->default(true);
            $table->integer('login_attempts_before_lockout')->default(5);
            $table->integer('lockout_duration_minutes')->default(30);
            $table->integer('session_timeout_minutes')->default(60);
            $table->integer('remember_me_duration_minutes')->default(1440);
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auth_settings');
    }
};
