<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $fillable = [
        'type',
        'name',
        'phone',
        'national_id',
        'description',
    ];

    public function documents(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function proformas(): HasMany
    {
        return $this->hasMany(Invoice::class)->where('type', 'proforma');
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class)->where('type', 'invoice');
    }
}