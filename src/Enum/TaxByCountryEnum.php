<?php

namespace App\Enum;

enum TaxByCountryEnum: string
{
    case DE = '0.19';
    case IT = '0.20';
    case GR = '0.24';

    public static function taxByCountry(string $countyCode): float
    {
        return match ($countyCode) {
            'DE' => floatval(self::DE->value),
            'IT' => floatval(self::IT->value),
            'GR' => floatval(self::GR->value),
            default => throw new \DomainException('Unknown tax amount for the country'),
        };
    }

    public static function getRegex(): string
    {
        return '/^((AT)(U\d{8})|(BE)(0\d{9})|(BG)(\d{9,10})|(CY)(\d{8}[LX])|(CZ)(\d{8,10})|(DE)(\d{9})|(DK)(\d{8})|(EE)(\d{9})|(EL|GR)(\d{9})|(ES)([\dA-Z]\d{7}[\dA-Z])|(FI)(\d{8})|(FR)([\dA-Z]{2}\d{9})|(HU)(\d{8})|(IE)(\d{7}[A-Z]{2})|(IT)(\d{11})|(LT)(\d{9}|\d{12})|(LU)(\d{8})|(LV)(\d{11})|(MT)(\d{8})|(NL)(\d{9}(B\d{2}|BO2))|(PL)(\d{10})|(PT)(\d{9})|(RO)(\d{2,10})|(SE)(\d{12})|(SI)(\d{8})|(SK)(\d{10}))$/im';
    }
}
