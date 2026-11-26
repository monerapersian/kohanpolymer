<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('profit_input_type', 20)->nullable()->after('is_formal');
            $table->decimal('profit_percentage', 8, 3)->nullable()->after('profit_input_type');
            $table->decimal('profit_amount', 20, 2)->nullable()->after('profit_percentage');
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn([
                'profit_input_type',
                'profit_percentage',
                'profit_amount',
            ]);
        });
    }
};