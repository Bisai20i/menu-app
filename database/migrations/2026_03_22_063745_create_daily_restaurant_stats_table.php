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
        Schema::create('daily_restaurant_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained()->onDelete('cascade');
            $table->date('date'); // The specific day this data belongs to

            // Metrics
            $table->unsignedInteger('menu_views')->default(0);
            $table->unsignedInteger('total_orders')->default(0);
            $table->decimal('total_revenue', 12, 2)->default(0.00);
            // $table->unsignedInteger('guest_count')->default(0); // Optional: derived from TableSession

            $table->timestamps();
            // Index for fast reporting and uniqueness
            $table->unique(['restaurant_id', 'date']);
            $table->index('date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_restaurant_stats');
    }
};
