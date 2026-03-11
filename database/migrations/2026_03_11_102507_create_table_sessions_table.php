<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('table_sessions', function (Blueprint $table) {
            $table->id();

            $table->uuid('uuid')->unique(); // Public-facing identifier

            $table->foreignId('restaurant_table_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->foreignId('restaurant_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // Who opened the session (waiter or admin) — nullable if auto-opened on QR scan
            $table->foreignId('opened_by')
                  ->nullable()
                  ->constrained('admins')
                  ->nullOnDelete();

            // Who closed the session
            $table->foreignId('closed_by')
                  ->nullable()
                  ->constrained('admins')
                  ->nullOnDelete();

            $table->enum('status', [
                'active',       // Table is occupied, orders can be added
                'paid',         // Bill settled — session closed
                'cancelled',    // Abandoned / no orders placed
            ])->default('active');

            // Optional: number of guests for reporting
            $table->unsignedTinyInteger('guest_count')->nullable();

            // Optional: notes added by staff (e.g. "birthday table", "allergy: nuts")
            $table->text('notes')->nullable();

            $table->timestamp('opened_at')->useCurrent();
            $table->timestamp('closed_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_sessions');
    }
};
