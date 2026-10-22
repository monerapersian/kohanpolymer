<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use RuntimeException;

class DocumentNumberService
{
    public function generate(string $type): string
    {
        $config = match ($type) {
            'invoice' => [
                'prefix' => '',
                'initial' => 600001,
            ],
            'proforma' => [
                'prefix' => 'P',
                'initial' => 400001,
            ],
            default => throw new InvalidArgumentException(
                'Unsupported document type.'
            ),
        };

        return DB::transaction(function () use ($type, $config) {
            DB::table('document_sequences')->insertOrIgnore([
                'type' => $type,
                'next_number' => $config['initial'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $sequence = DB::table('document_sequences')
                ->where('type', $type)
                ->lockForUpdate()
                ->first();

            if (!$sequence) {
                throw new RuntimeException(
                    'Document sequence could not be initialized.'
                );
            }

            $number = (int) $sequence->next_number;

            DB::table('document_sequences')
                ->where('type', $type)
                ->update([
                    'next_number' => $number + 1,
                    'updated_at' => now(),
                ]);

            return $config['prefix'] . $number;
        }, 5);
    }
}