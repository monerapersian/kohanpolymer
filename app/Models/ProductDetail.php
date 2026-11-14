<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductDetail extends Model
{
    protected $fillable = [
        'product_id',
        'type',
        'value',
        'thickness',
        'width',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'thickness' => 'decimal:3',
            'width' => 'decimal:3',
            'sort_order' => 'integer',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}