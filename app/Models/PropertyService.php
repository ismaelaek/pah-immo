<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\PropertyServiceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * A service from Apimo API v3 `Property.services`. `comments` is only returned with `with[]=advanced|advanced_services`.
 *
 * @property int $id
 * @property int $property_id
 * @property int $type
 * @property list<array{language: string, comment: string}>|null $comments
 */
class PropertyService extends Model
{
    /** @use HasFactory<PropertyServiceFactory> */
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
            'comments' => 'array',
        ];
    }
}
