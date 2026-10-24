<?php

declare(strict_types=1);

namespace App\Enums;

enum MediaConversion: string
{
    case Small = 'small';
    case Medium = 'medium';
    case Large = 'large';

    public function width(): int
    {
        return match ($this) {
            self::Small => 150,
            self::Medium => 400,
            self::Large => 1024,
        };
    }
}
