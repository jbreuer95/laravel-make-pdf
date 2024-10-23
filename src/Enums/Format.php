<?php

namespace Breuer\MakePDF\Enums;

enum Format
{
    case LETTER;
    case LEGAL;
    case A0;
    case A1;
    case A2;
    case A3;
    case A4;
    case A5;
    case A6;

    public function widthInInches(): float
    {
        return match ($this) {
            self::LETTER => 8.5,
            self::LEGAL => 8.5,
            self::A0 => 33.11,
            self::A1 => 23.39,
            self::A2 => 16.54,
            self::A3 => 11.69,
            self::A4 => 8.27,
            self::A5 => 5.83,
            self::A6 => 4.13,
        };
    }

    public function heightInInches(): float
    {
        return match ($this) {
            self::LETTER => 11,
            self::LEGAL => 14,
            self::A0 => 46.81,
            self::A1 => 33.11,
            self::A2 => 23.39,
            self::A3 => 16.54,
            self::A4 => 11.69,
            self::A5 => 8.27,
            self::A6 => 5.83,
        };
    }
}
