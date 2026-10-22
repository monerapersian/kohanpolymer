<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteContent extends Model
{
    protected $fillable = [
        'hero_slogan',
        'about_text',
        'phone',
        'email',
        'instagram',
        'whatsapp',
    ];
}