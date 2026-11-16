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
                'initial' => 1,
                'prefix' => '',
            ],
            'proforma' => [
                'initial' => 1001,
                'prefix' => 'PF-',
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

            if ($type === 'proforma') {
                return $config['prefix'] . $number;
            }

            $group = intdiv($number - 1, 99);
            $sequenceNumber = (($number - 1) % 99) + 1;
            $yearNumber = 2000 + $group;

            return $yearNumber . '-' . $sequenceNumber;
        }, 5);
    }
}