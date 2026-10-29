<?php

namespace App\Support;

use Carbon\Carbon;
use Morilog\Jalali\Jalalian;

class DateHelper
{
    /**
     * Get the application timezone.
     */
    private static function timezone(): string
    {
        return config('app.timezone', 'Asia/Tehran');
    }

    /**
     * Convert a Jalali date to a Gregorian date.
     */
    public static function toGregorian(?string $date): ?string
    {
        if ($date === null || trim($date) === '') {
            return null;
        }

        $date = NumberHelper::toEnglish($date);
        $date = str_replace('-', '/', trim($date));

        return Jalalian::fromFormat('Y/m/d', $date)
            ->toCarbon()
            ->setTimezone(self::timezone())
            ->format('Y-m-d');
    }

    /**
     * Convert a Gregorian date to a Jalali date.
     */
    public static function toPersian(
        mixed $date,
        string $format = 'Y/m/d'
    ): string {
        if ($date === null || $date === '') {
            return '';
        }

        if ($date instanceof \DateTimeInterface) {
            $date = Carbon::instance($date);
        } else {
            $date = Carbon::parse($date, self::timezone());
        }

        $date->setTimezone(self::timezone());

        return Jalalian::fromCarbon($date)->format($format);
    }
}