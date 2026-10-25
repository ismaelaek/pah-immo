<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum PropertyType: string implements HasLabel
{
    case Apartment = 'apartment';
    case House = 'house';
    case Villa = 'villa';
    case Studio = 'studio';
    case CommercialSpace = 'commercial_space';
    case Land = 'land';
    case Office = 'office';
    case Duplex = 'duplex';

    /**
     * @return list<array{value: string, label: string}>
     */
    public static function options(): array
    {
        return array_map(
            fn (self $type): array => [
                'value' => $type->value,
                'label' => (string) $type->getLabel(),
            ],
            self::cases(),
        );
    }

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::Apartment => __('front.property.types.apartment'),
            self::House => __('front.property.types.house'),
            self::Villa => __('front.property.types.villa'),
            self::Studio => __('front.property.types.studio'),
            self::CommercialSpace => __('front.property.types.commercial_space'),
            self::Land => __('front.property.types.land'),
            self::Office => __('front.property.types.office'),
            self::Duplex => __('front.property.types.duplex'),
        };
    }
}
