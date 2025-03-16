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
        Schema::create('rental_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->nullable();
            $table->decimal('rental_price', 10, 2)->nullable();
            $table->integer('rental_duration')->nullable(); 
            $table->enum('rental_type', ['hour', 'day', 'week', 'month'])->default('day');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rental_products');
    }
};