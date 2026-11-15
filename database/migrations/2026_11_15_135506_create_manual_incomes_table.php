<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('manual_incomes', function (Blueprint $table) {
            $table->id();

            $table->string('title', 255);
            $table->decimal('amount', 20, 2);
            $table->date('transaction_date');

            $table->text('description')->nullable();
            $table->string('document_path')->nullable();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->index('transaction_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('manual_incomes');
    }
};