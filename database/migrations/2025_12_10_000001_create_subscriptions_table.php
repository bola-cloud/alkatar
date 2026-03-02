<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->enum('period_type', ['days','months','years'])->default('months');
            $table->integer('period_value')->default(1);
            $table->decimal('price', 12, 2)->default(0);
            $table->decimal('discount_percent', 5, 2)->nullable();
            $table->decimal('max_discount_amount', 12, 2)->nullable();
            $table->boolean('free_shipping')->default(false);
            $table->boolean('tax_exempt')->default(false);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
