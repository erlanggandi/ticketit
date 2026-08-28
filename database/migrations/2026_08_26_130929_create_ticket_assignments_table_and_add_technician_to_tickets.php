<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('tickets')->cascadeOnDelete();
            $table->foreignId('technician_id')->constrained('technicians')->restrictOnDelete();
            $table->timestamp('assigned_at');
            $table->timestamp('unassigned_at')->nullable();
            $table->timestamps();

            $table->index(['ticket_id', 'unassigned_at']);
            $table->index(['technician_id', 'assigned_at']);
            $table->index('assigned_at');
        });

        Schema::table('tickets', function (Blueprint $table) {
            $table->foreignId('assigned_technician_id')
                ->nullable()
                ->constrained('technicians')
                ->nullOnDelete();

            $table->index('assigned_technician_id');
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropForeign(['assigned_technician_id']);
            $table->dropIndex(['assigned_technician_id']);
            $table->dropColumn('assigned_technician_id');
        });

        Schema::dropIfExists('ticket_assignments');
    }
};
