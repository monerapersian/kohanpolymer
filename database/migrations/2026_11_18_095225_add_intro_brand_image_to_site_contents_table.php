<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_contents', function (Blueprint $table) {
            $table->string('intro_brand_image')->nullable()->after('hero_image');
        });
    }

    public function down(): void
    {
        Schema::table('site_contents', function (Blueprint $table) {
            $table->dropColumn('intro_brand_image');
        });
    }
};