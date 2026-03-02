<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations - Add payment webhook support fields to orders table
     * This enables unpaid order creation with webhook confirmation (like Thawani pattern)
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Track whether payment has been confirmed via webhook
            $table->boolean('is_paid')->default(false)->after('Payment_Status');

            // Unique token for webhook identification before payment
            $table->string('pending_token', 100)->nullable()->unique()->after('is_paid');

            // Payment gateway session/transaction ID for verification
            $table->string('payment_session_id', 255)->nullable()->index()->after('pending_token');

            // SmartLife invoice ID (assigned after successful payment sync)
            $table->string('smartlife_invoice_id', 100)->nullable()->index()->after('payment_session_id');

            // Track when order was synced to SmartLife ERP
            $table->timestamp('smartlife_synced_at')->nullable()->after('smartlife_invoice_id');
        });
    }

    /**
     * Reverse the migrations
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'is_paid',
                'pending_token',
                'payment_session_id',
                'smartlife_invoice_id',
                'smartlife_synced_at'
            ]);
        });
    }
};
