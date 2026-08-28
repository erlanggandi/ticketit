<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('technicians', function (Blueprint $table) {
            $table->id();
            $table->string('technician_name', 150);
            $table->string('expertise', 150)->nullable();
            $table->string('contact', 150)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('technician_name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('technicians');
    }
};
