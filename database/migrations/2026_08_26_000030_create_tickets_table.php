<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number', 30)->unique();
            $table->string('requester_name', 150);
            $table->foreignId('requester_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->foreignId('category_id')->constrained('categories')->restrictOnDelete();
            $table->string('priority', 20);
            $table->text('description');
            $table->string('status', 20)->default('Open');
            $table->timestamp('sla_due_at');
            $table->timestamp('sla_alert_approaching_sent_at')->nullable();
            $table->timestamp('sla_alert_breach_sent_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'priority']);
            $table->index('sla_due_at');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
