<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class Invoice extends Model
{
    protected $fillable = [
        'customer_id',
        'converted_from_id',
        'document_number',
        'type',
        'issue_date',
        'valid_until',
        'payment_type',
        'status',
        'is_formal',
        'qr_token',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'issue_date' => 'date',
            'valid_until' => 'date',
            'is_formal' => 'boolean',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class)
            ->orderBy('sort_order');
    }

    public function notes(): HasMany
    {
        return $this->hasMany(InvoiceNote::class)
            ->orderBy('sort_order');
    }

    public function convertedFrom(): BelongsTo
    {
        return $this->belongsTo(self::class, 'converted_from_id');
    }

    public function convertedTo(): HasMany
    {
        return $this->hasMany(self::class, 'converted_from_id');
    }

    public function isExpired(): bool
    {
        return $this->type === 'proforma'
            && $this->valid_until !== null
            && $this->valid_until->lt(Carbon::today());
    }

    public function getDisplayStatusAttribute(): string
    {
        if ($this->status === 'voided') {
            return 'باطل شده';
        }

        if ($this->isExpired()) {
            return 'منقضی';
        }

        return 'معتبر';
    }
}