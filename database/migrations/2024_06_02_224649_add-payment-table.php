<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("payments", function (Blueprint $table) {
            $table->id();
            $table->string("session_id")->unique();
            $table->foreignId("user_id")->constrained();
            $table->string('order_number');
            $table->decimal("amount", 16, 3);
            $table->enum("status", ["CREATED", "PAYED", "REJECTED"])->default("CREATED");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("payments");
    }
}
