<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Use raw SQL to avoid Doctrine DBAL 'Unknown column type "timestamp"' error
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE user_subscriptions MODIFY COLUMN start_at timestamp NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE user_subscriptions MODIFY COLUMN start_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP');
    }
};
