<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\PropertyAreaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * A room from Apimo API v3 `PropertyArea`, with the `floor`, `lot` and `beds` objects flattened.
 *
 * @property int $id
 * @property int $property_id
 * @property int $type
 * @property int $number
 * @property float|null $area
 * @property int|null $flooring
 * @property float|null $ceiling_height
 * @property int|null $floor_type
 * @property int|null $floor_value
 * @property list<string> $orientations
 * @property list<array{language: string, comment: string}> $comments
 * @property int|null $lot_type
 * @property int|null $lot_rank
 * @property list<string> $lot_name
 * @property int|null $beds_single
 * @property int|null $beds_double
 * @property float|null $price
 */
class PropertyArea extends Model
{
    /** @use HasFactory<PropertyAreaFactory> */
    use HasFactory;

    public $timestamps = false;

    /**
     * @return BelongsTo<Property, $this>
     */
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => 'integer',
            'number' => 'integer',
            'area' => 'float',
            'flooring' => 'integer',
            'ceiling_height' => 'float',
            'floor_type' => 'integer',
            'floor_value' => 'integer',
            'orientations' => 'array',
            'comments' => 'array',
            'lot_type' => 'integer',
            'lot_rank' => 'integer',
            'lot_name' => 'array',
            'beds_single' => 'integer',
            'beds_double' => 'integer',
            'price' => 'float',
        ];
    }
}
