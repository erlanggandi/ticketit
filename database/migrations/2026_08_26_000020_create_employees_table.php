<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('employee_name', 150);
            $table->string('department', 100)->nullable();
            $table->string('contact', 150)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('employee_name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
