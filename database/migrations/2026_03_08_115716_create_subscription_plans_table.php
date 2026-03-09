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
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')
                ->constrained('admins')
                ->cascadeOnDelete();

            $table->string('name');

            $table->unsignedInteger('duration_value');
            // 3, 10, etc.

            $table->enum('duration_unit', ['day', 'month', 'year'])
                ->default('month');

            $table->decimal('price', 10, 2);

            $table->string('currency')->default('NPR');

            $table->json('features')->nullable();
            // dynamic key-value pairs

            $table->boolean('published')->default(true);

            $table->integer('sort_order')->nullable();

            $table->timestamps();

            $table->index(['published']);
            $table->index(['duration_value', 'duration_unit']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_plans');
    }
};
