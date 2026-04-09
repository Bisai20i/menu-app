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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('rating'); //store ratting from the user (1-5)
            $table->text('comment')->nullable(); //store comment from the user
            $table->enum('source', ['internal', 'google_redirect'])->default('internal'); // identify good or bad reviews
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->foreignId('restaurant_id')->constrained()->cascadeOnDelete();
            $table->boolean('redirected_to_google')->default(false);
            $table->boolean('is_public')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
