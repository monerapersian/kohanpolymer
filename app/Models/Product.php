<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'name',
        'image',
        'description',
        'status',
        'published_at',
        'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'is_featured' => 'boolean',
        ];
    }

    public function details(): HasMany
    {
        return $this->hasMany(ProductDetail::class)->orderBy('sort_order');
    }

    public function properties(): HasMany
    {
        return $this->details()->where('type', 'property');
    }

    public function sizes(): HasMany
    {
        return $this->details()->where('type', 'size');
    }

    public function eps(): HasMany
    {
        return $this->details()->where('type', 'ep');
    }

    public function goodsDescriptions(): HasMany
    {
        return $this->details()->where('type', 'goods_description');
    }
}