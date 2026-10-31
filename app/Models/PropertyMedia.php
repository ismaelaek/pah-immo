<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\PropertyMediaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * A video or virtual tour from Apimo API v3 `PropertyMedia`. `apimo_id` is Apimo's `id` (a string).
 *
 * @property int $id
 * @property int $property_id
 * @property string $apimo_id
 * @property string $type
 * @property string $value
 * @property string $internet
 */
class PropertyMedia extends Model
{
    /** @use HasFactory<PropertyMediaFactory> */
    use HasFactory;

    public $timestamps = false;

    /**
     * @return BelongsTo<Property, $this>
     */
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }
}
