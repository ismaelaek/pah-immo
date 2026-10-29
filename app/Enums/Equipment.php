<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum Equipment: string implements HasLabel
{
    case AirConditioning = 'air_conditioning';
    case Heating = 'heating';
    case Elevator = 'elevator';
    case Parking = 'parking';
    case Garage = 'garage';
    case SwimmingPool = 'swimming_pool';
    case Garden = 'garden';
    case Terrace = 'terrace';
    case Balcony = 'balcony';
    case Furnished = 'furnished';
    case EquippedKitchen = 'equipped_kitchen';
    case Fireplace = 'fireplace';
    case Storage = 'storage';
    case SecuritySystem = 'security_system';
    case Concierge = 'concierge';

    /**
     * @return list<array{value: string, label: string}>
     */
    public static function options(): array
    {
        return array_map(
            fn (self $equipment): array => [
                'value' => $equipment->value,
                'label' => (string) $equipment->getLabel(),
            ],
            self::cases(),
        );
    }

    public function getLabel(): string|Htmlable|null
    {
        return __('front.property.equipment.'.$this->value);
    }
}
