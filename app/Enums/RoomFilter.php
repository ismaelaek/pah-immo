<?php

declare(strict_types=1);

namespace App\Enums;

enum RoomFilter: string
{
    case Studio = 'studio';
    case One = '1';
    case Two = '2';
    case Three = '3';
    case FourOrMore = '4';
}
