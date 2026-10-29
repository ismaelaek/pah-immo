<?php

declare(strict_types=1);

namespace App\Enums;

enum PriceRange: string
{
    case Under250 = 'under_250';
    case From250To500 = '250_500';
    case From500To1m = '500_1m';
    case Over1m = 'over_1m';

    public function minimum(): ?int
    {
        return match ($this) {
            self::Under250 => null,
            self::From250To500 => 250_000,
            self::From500To1m => 500_000,
            self::Over1m => 1_000_000,
        };
    }

    public function maximum(): ?int
    {
        return match ($this) {
            self::Under250 => 250_000,
            self::From250To500 => 500_000,
            self::From500To1m => 1_000_000,
            self::Over1m => null,
        };
    }
}
