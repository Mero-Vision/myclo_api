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
        Schema::create('delivery_charges', function (Blueprint $table) {
            $table->id();
            $table->string('district_name')->nullable();
            $table->boolean('cash_on_delivery', 16, 2)->default(true);
            $table->decimal('cost_0_1kg', 16, 2)->nullable();
            $table->decimal('cost_1_2kg', 16, 2)->nullable();
            $table->decimal('cost_2_3kg', 16, 2)->nullable();
            $table->decimal('cost_3_5kg', 16, 2)->nullable();
            $table->decimal('cost_5_10kg', 16, 2)->nullable();
            $table->decimal('cost_above_10kg', 16, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_charges');
    }
};
