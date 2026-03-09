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
        Schema::create('restaurant_tables', function (Blueprint $table) {
            $table->id();
            // Unique identifier for the QR code (e.g., yoursite.com/menu/550e8400-e29b...)
            $table->uuid('uuid')->unique(); 
            
            $table->string('table_number'); // e.g., "Table 1", "T-05", "VIP-1"
            $table->integer('capacity')->default(2); // Number of seats
            
            // Helps organize tables by section
            $table->string('section')->nullable(); // e.g., "Terrace", "Main Hall", "Bar"
            
            $table->boolean('is_active')->default(true); // Can this table be used?
            $table->enum('status', ['available', 'occupied', 'reserved'])->default('available');
            $table->foreignId('restaurant_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurant_tables');
    }
};
