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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->uuid('uuid')->unique(); // Public-facing order reference

            $table->foreignId('table_session_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->foreignId('restaurant_table_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->foreignId('restaurant_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // Order fulfilment status
            $table->enum('status', [
                'pending',      // Placed by customer, awaiting staff acknowledgement
                'confirmed',    // Confirmed by staff, kitchen is preparing
                'served',       // All items delivered to the table
            ])->default('pending');

            // Payment is tracked independently from fulfilment
            $table->boolean('is_paid')->default(false);
            $table->timestamp('paid_at')->nullable();

            // Snapshot total at time of order (sum of unit_price * quantity)
            $table->decimal('total_amount', 10, 2)->default(0.00);

            // Customer notes for this specific order round
            $table->text('note')->nullable();

            // Which staff member confirmed/served (optional, for accountability)
            $table->foreignId('confirmed_by')
                  ->nullable()
                  ->constrained('admins')
                  ->nullOnDelete();

            $table->foreignId('served_by')
                  ->nullable()
                  ->constrained('admins')
                  ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
