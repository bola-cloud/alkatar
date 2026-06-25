<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('custom_box_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name_en');
            $table->string('name_ar');
            $table->text('description_en')->nullable();
            $table->text('description_ar')->nullable();
            $table->string('color_code')->nullable();
            $table->string('image')->nullable();
            $table->decimal('price', 10, 3)->default(2.000);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('custom_box_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->string('template_name');
            $table->integer('capacity');
            $table->string('print_name')->nullable();
            $table->text('gift_message')->nullable();
            $table->text('details');
            $table->decimal('price', 10, 3);
            $table->string('prep_status')->default('pending');
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('custom_box_orders');
        Schema::dropIfExists('custom_box_templates');
    }
};
