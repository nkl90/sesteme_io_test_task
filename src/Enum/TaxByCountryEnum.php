<?php

namespace App\Enum;

class TaxByCounty
{
    public const DE = 0.19;
    public const IT = 0.20;
    public const GR = 0.24;

    public static function getTaxByCounty(string $county): float
    {
        
        return match ($county) {
            'DE' => self::DE,
            'IT' => self::IT,
            'GR' => self::GR,
            default => 0.0,
        };
    }
}
