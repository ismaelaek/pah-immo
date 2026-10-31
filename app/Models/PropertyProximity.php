<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\PropertyProximityFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * A proximity from Apimo API v3 `Property.proximities`. `length` and `length_type` are only returned with `with[]=advanced|advanced_proximities`.
 *
 * @property int $id
 * @property int $property_id
 * @property int $type
 * @property int|null $length
 * @property string|null $length_type
 */
class PropertyProximity extends Model
{
    /** @use HasFactory<PropertyProximityFactory> */
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
            'length' => 'integer',
        ];
    }
}
