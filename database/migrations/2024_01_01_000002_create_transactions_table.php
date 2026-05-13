<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->decimal('amount', 12, 2);
            $table->string('type')->checkIn(['income', 'expense', 'reconciliation']);
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('note')->nullable();
            $table->timestamps();

            $table->index(['date', 'type']);
            $table->index('category_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
