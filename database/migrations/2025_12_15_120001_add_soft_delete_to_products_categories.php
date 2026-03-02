<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations - Add soft delete support to products and categories
     * This prevents data loss when items are deleted from SmartLife or locally
     */
    public function up(): void
    {
        // Add soft delete to products
        Schema::table('products', function (Blueprint $table) {
            $table->softDeletes()->after('updated_at');

            // Track deletion source (local admin or smartlife sync)
            $table->string('deleted_reason', 50)->nullable()->after('deleted_at');
        });

        // Add soft delete to categories
        Schema::table('categories', function (Blueprint $table) {
            $table->softDeletes()->after('updated_at');

            // Track deletion source
            $table->string('deleted_reason', 50)->nullable()->after('deleted_at');
        });
    }

    /**
     * Reverse the migrations
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['deleted_at', 'deleted_reason']);
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['deleted_at', 'deleted_reason']);
        });
    }
};
