<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum NavigationGroup: string implements HasLabel
{
    case RealEstate = 'real_estate';
    case Content = 'content';
    case Communication = 'communication';
    case Settings = 'settings';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::RealEstate => __('Real estate'),
            self::Content => __('Content'),
            self::Communication => __('Communication'),
            self::Settings => __('Settings'),
        };
    }
}
