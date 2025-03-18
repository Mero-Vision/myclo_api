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
        Schema::create('product_s_waps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requester_id')->nullable();
            $table->foreignId('owner_id')->nullable();
            $table->foreignId('requester_product_id')->nullable();
            $table->foreignId('owner_product_id')->nullable();
            $table->string('swap_status')->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_s_waps');
    }
};