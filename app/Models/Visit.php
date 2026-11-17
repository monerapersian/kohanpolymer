<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    protected $fillable = [
        'type',
        'visitable_id',
    ];

    public static function record(string $type, ?int $visitableId = null): void
    {
        self::create([
            'type' => $type,
            'visitable_id' => $visitableId,
        ]);
    }
}