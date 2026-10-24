<?php

declare(strict_types=1);

namespace App\Enums;

enum MediaConversion: string
{
    case Small = 'small';
    case Medium = 'medium';
    case Large = 'large';
}
