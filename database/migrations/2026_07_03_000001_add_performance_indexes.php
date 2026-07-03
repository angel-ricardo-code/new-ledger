<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->index(['user_id', 'date', 'type'], 'idx_transactions_user_date_type');
            $table->index(['user_id', 'type'], 'idx_transactions_user_type');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->index('user_id', 'idx_categories_user_id');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropIndex('idx_transactions_user_date_type');
            $table->dropIndex('idx_transactions_user_type');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex('idx_categories_user_id');
        });
    }
};
