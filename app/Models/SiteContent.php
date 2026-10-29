<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteContent extends Model
{
    protected $fillable = [
        'logo',
        'hero_image',
        'hero_slogan',
        'industry_title',
        'industry_text',
        'difference_title',
        'difference_text',
        'about_title',
        'about_text',
        'activity_areas',
        'phones',
        'email',
        'instagram',
        'whatsapp',
    ];

    protected function casts(): array
    {
        return [
            'activity_areas' => 'array',
            'phones' => 'array',
        ];
    }
}