<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum Locale: string implements HasColor, HasLabel
{
    case French = 'fr';
    case English = 'en';
    case Italian = 'it';
    case Spanish = 'es';
    case Chinese = 'zh';
    case Russian = 'ru';

    public static function default(): self
    {
        return self::French;
    }

    /**
     * @return list<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * @return list<array{value: string, label: string}>
     */
    public static function options(): array
    {
        return array_map(
            fn (self $locale): array => [
                'value' => $locale->value,
                'label' => $locale->getNativeLabel(),
            ],
            self::cases(),
        );
    }

    public function getNativeLabel(): string
    {
        return match ($this) {
            self::French => 'Français',
            self::English => 'English',
            self::Italian => 'Italiano',
            self::Spanish => 'Español',
            self::Chinese => '中文',
            self::Russian => 'Русский',
        };
    }

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::French => 'Français',
            self::English => 'Anglais',
            self::Italian => 'Italien',
            self::Spanish => 'Espagnol',
            self::Chinese => 'Chinois',
            self::Russian => 'Russe',
        };
    }

    /**
     * @return string|array<string>|null
     */
    public function getColor(): string|array|null
    {
        return match ($this) {
            self::French => 'info',
            self::English => 'gray',
            self::Italian => 'success',
            self::Spanish => 'warning',
            self::Chinese => 'danger',
            self::Russian => 'primary',
        };
    }
}
