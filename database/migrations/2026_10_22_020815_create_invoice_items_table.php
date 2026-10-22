<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('invoice_id')
                ->constrained('invoices')
                ->cascadeOnDelete();

            $table->string('description', 500);

            $table->decimal('quantity', 15, 3)->default(1);
            $table->string('unit', 50)->nullable();

            $table->decimal('width', 15, 3)->nullable();
            $table->decimal('thickness', 15, 3)->nullable();

            $table->decimal('unit_price', 20, 2)->default(0);
            $table->decimal('total_amount', 20, 2)->default(0);

            $table->decimal('vat_percent', 5, 2)->default(0);
            $table->decimal('vat_amount', 20, 2)->default(0);

            $table->decimal('grand_total', 20, 2)->default(0);

            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();

            $table->index(['invoice_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
    }
};