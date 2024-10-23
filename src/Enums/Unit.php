<?php

namespace Breuer\MakePDF\Enums;

enum Unit
{
    case INCH;
    case CENTIMETER;
    case MILLIMETER;

    public function toInches($value)
    {
        return match ($this) {
            self::INCH => round($value, 2),
            self::CENTIMETER => round($value * 0.3937007874, 2),
            self::MILLIMETER => round($value * 0.03937007874, 2),
        };
    }
}
