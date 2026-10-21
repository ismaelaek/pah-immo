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
            self::Apartment => 'Appartement',
            self::House => 'Maison',
            self::Villa => 'Villa',
            self::Studio => 'Studio',
            self::CommercialSpace => 'Local commercial',
            self::Land => 'Terrain',
            self::Office => 'Bureau',
            self::Duplex => 'Duplex',
        };
    }
}
