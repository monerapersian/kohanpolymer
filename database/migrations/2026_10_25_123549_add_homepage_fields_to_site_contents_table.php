<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('site_contents', function (Blueprint $table) {
            $table->string('logo')->nullable();
            $table->string('hero_image')->nullable();

            $table->string('industry_title')->nullable();
            $table->longText('industry_text')->nullable();

            $table->string('difference_title')->nullable();
            $table->longText('difference_text')->nullable();

            $table->string('about_title')->nullable();

            $table->json('activity_areas')->nullable();
            $table->json('phones')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('site_contents', function (Blueprint $table) {
            $table->dropColumn([
                'logo',
                'hero_image',
                'industry_title',
                'industry_text',
                'difference_title',
                'difference_text',
                'about_title',
                'activity_areas',
                'phones',
            ]);
        });
    }
};