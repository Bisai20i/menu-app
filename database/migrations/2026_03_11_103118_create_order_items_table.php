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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->foreignId('menu_item_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->foreignId('restaurant_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->unsignedSmallInteger('quantity');

            // Snapshot of price at time of order — never changes even if menu price is updated later
            $table->decimal('unit_price', 8, 2);

            // Computed: quantity * unit_price (stored for fast reporting, no recalculation needed)
            $table->decimal('subtotal', 10, 2);

            // Per-item special requests (e.g. "no onions", "extra sauce")
            $table->string('special_request')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
