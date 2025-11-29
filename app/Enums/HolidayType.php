<?php

namespace App\Enums;

enum HolidayType: string
{
    case NATIONAL = 'national';
    case SCHOOL = 'school';
    case REGIONAL = 'regional';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
