<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();

            $table->foreignId('customer_id')
                ->constrained('customers')
                ->restrictOnDelete();

            $table->foreignId('converted_from_id')
                ->nullable()
                ->constrained('invoices')
                ->restrictOnDelete();

            // Generated automatically by the application.
            // Invoice: 600001, 600002, ...
            // Proforma: P400001, P400002, ...
            $table->string('document_number', 50)->unique();

            $table->enum('type', ['proforma', 'invoice']);

            $table->date('issue_date');
            $table->date('valid_until')->nullable();

            $table->string('payment_type', 100)->nullable();

            $table->enum('status', ['valid', 'voided'])
                ->default('valid');

            $table->boolean('is_formal')->default(true);

            $table->string('qr_token', 64)->nullable()->unique();

            $table->text('description')->nullable();

            $table->timestamps();

            $table->index(['type', 'status']);
            $table->index('issue_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};