<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_featured')->default(false)->after('description');
        });

        Schema::create('product_details', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->enum('type', [
                'property',
                'size',
                'ep',
                'goods_description',
            ]);

            $table->string('value')->nullable();
            $table->decimal('thickness', 10, 3)->nullable();
            $table->decimal('width', 10, 3)->nullable();

            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();

            $table->index(['product_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_details');

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('is_featured');
        });
    }
};