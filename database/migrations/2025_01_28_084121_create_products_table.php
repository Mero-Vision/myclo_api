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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable();
            $table->foreignId('brand_id')->nullable();
            $table->foreignId('created_user_id')->nullable();
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->text('description')->nullable();
            $table->decimal('selling_price', 16, 2)->nullable();
            $table->decimal('cross_price', 16, 2)->nullable();
            $table->decimal('unit_price', 16, 2)->nullable();
            $table->decimal('stock_quantity',16,2)->nullable();
            $table->string('sku')->nullable(); 
            $table->boolean('allow_negative_stock')->default(true);
            $table->boolean('has_varient')->default(false);
            $table->string('product_weight')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
