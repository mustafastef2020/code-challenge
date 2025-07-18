<?php

namespace App\Enums;

enum Currency: string
{
    case Euro = 'EUR';
    case GreatBritishPound = 'GBP';
    case UnitedStatesDollar = 'USD';

    public function label(): string
    {
        return match ($this) {
            self::Euro => 'EUR',
            self::GreatBritishPound => 'GBP',
            self::UnitedStatesDollar => 'USD',
        };
    }
}
