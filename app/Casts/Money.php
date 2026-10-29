<?php

declare(strict_types=1);

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

/**
 * Stores an amount in minor units (cents) and exposes it in major units (euros).
 *
 * @implements CastsAttributes<float|null, int|float|string|null>
 */
class Money implements CastsAttributes
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): ?float
    {
        if ($value === null) {
            return null;
        }

        return static::toMajorUnits((int) $value);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        return static::toMinorUnits((float) $value);
    }

    public static function toMinorUnits(int|float $amount): int
    {
        return (int) round($amount * 100);
    }

    public static function toMajorUnits(int $amount): float
    {
        return $amount / 100;
    }
}
