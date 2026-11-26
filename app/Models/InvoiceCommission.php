<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceCommission extends Model
{
    protected $fillable = [
        'invoice_id',
        'recipient_type',
        'user_id',
        'external_name',
        'percentage',
        'amount',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'percentage' => 'decimal:3',
            'amount' => 'decimal:2',
        ];
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}