<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('CREATE EXTENSION IF NOT EXISTS pg_trgm');

        DB::statement('CREATE INDEX CONCURRENTLY IF NOT EXISTS idx_transactions_note_trgm
            ON transactions USING GIN (note gin_trgm_ops)');

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropIndex('idx_transactions_user_date_type');
        });

        DB::statement('CREATE INDEX idx_transactions_user_date_type
            ON transactions (user_id, date, type) INCLUDE (amount)');
    }

    public function down(): void
    {
        DB::statement('DROP INDEX IF EXISTS idx_transactions_note_trgm');

        DB::statement('DROP INDEX IF EXISTS idx_transactions_user_date_type');

        Schema::table('transactions', function (Blueprint $table) {
            $table->index(['user_id', 'date', 'type'], 'idx_transactions_user_date_type');
        });
    }
};
