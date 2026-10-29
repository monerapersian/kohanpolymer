<?php

namespace App\Support;

class NumberHelper
{
    /**
     * Convert Persian and Arabic digits to English digits.
     */
    public static function toEnglish(mixed $value): mixed
    {
        if (!is_string($value) && !is_numeric($value)) {
            return $value;
        }

        $value = (string) $value;

        return strtr($value, [
            '۰' => '0',
            '۱' => '1',
            '۲' => '2',
            '۳' => '3',
            '۴' => '4',
            '۵' => '5',
            '۶' => '6',
            '۷' => '7',
            '۸' => '8',
            '۹' => '9',

            '٠' => '0',
            '١' => '1',
            '٢' => '2',
            '٣' => '3',
            '٤' => '4',
            '٥' => '5',
            '٦' => '6',
            '٧' => '7',
            '٨' => '8',
            '٩' => '9',

            '٫' => '.',
            '٬' => ',',
            '−' => '-',
            '－' => '-',
        ]);
    }

    /**
     * Convert numeric digits to Persian digits.
     */
    public static function toPersian(mixed $value): mixed
    {
        if (!is_string($value) && !is_numeric($value)) {
            return $value;
        }

        return strtr((string) $value, [
            '0' => '۰',
            '1' => '۱',
            '2' => '۲',
            '3' => '۳',
            '4' => '۴',
            '5' => '۵',
            '6' => '۶',
            '7' => '۷',
            '8' => '۸',
            '9' => '۹',
        ]);
    }

    /**
     * Format a number with a fixed number of decimal places.
     */
    public static function format(
        mixed $value,
        int $decimals = 2,
        bool $persian = true
    ): string {
        $value = self::toEnglish($value);

        if (!is_numeric($value)) {
            return (string) $value;
        }

        $formatted = number_format(
            (float) $value,
            $decimals,
            '.',
            ','
        );

        return $persian
            ? (string) self::toPersian($formatted)
            : $formatted;
    }
}