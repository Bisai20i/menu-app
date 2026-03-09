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
        Schema::create('menu_images', function (Blueprint $table) {
            $table->id();
            $table->string('media_path')->nullable(); // Stores the path or the full URL
            $table->enum('media_type', ['image', 'pdf'])->default('image');
            $table->enum('media_source', ['local', 'external'])->default('local');
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->foreignId('restaurant_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_images');
    }
};
