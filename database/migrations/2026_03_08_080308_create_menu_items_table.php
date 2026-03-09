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
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_category_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 8, 2); // 999,999.99
            $table->string('image')->nullable();
            $table->boolean('is_available')->default(true); // Toggle if out of stock
            $table->boolean('is_featured')->default(false); // For "Chef's Specials"
            $table->json('dietary_info')->nullable();       // Store tags like ['vegan', 'gluten-free']
            $table->foreignId('restaurant_id')->constrained()->onDelete('cascade'); // Link to restaurant
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};
