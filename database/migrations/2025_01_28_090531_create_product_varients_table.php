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
        Schema::create('product_varients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->nullable();
            $table->string('size')->nullable();
            $table->string('color')->nullable();
            $table->decimal('selling_price', 16, 2)->nullable();
            $table->decimal('cross_price', 16, 2)->nullable();
            $table->decimal('unit_price', 16, 2)->nullable();
            $table->decimal('stock_quantity', 16, 2)->nullable();
            $table->string('sku')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_varients');
    }
};
