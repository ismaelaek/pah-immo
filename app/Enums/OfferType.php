<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum OfferType: string implements HasColor, HasLabel
{
    case Sale = 'sale';
    case Rent = 'rent';

    /**
     * @return list<array{value: string, label: string}>
     */
    public static function options(): array
    {
        return array_map(
            fn (self $offerType): array => [
                'value' => $offerType->value,
                'label' => (string) $offerType->getLabel(),
            ],
            self::cases(),
        );
    }

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::Sale => __('front.property.statuses.sale'),
            self::Rent => __('front.property.statuses.rent'),
        };
    }

    /**
     * @return string|array<string>|null
     */
    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Sale => 'success',
            self::Rent => 'info',
        };
    }
}
