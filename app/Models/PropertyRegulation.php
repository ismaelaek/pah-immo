<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\PropertyRegulationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * A diagnostic or legal notice (DPE, GES...) from Apimo API v3 `PropertyRegulation`.
 *
 * @property int $id
 * @property int $property_id
 * @property int $type
 * @property string $value
 * @property Carbon|null $date
 * @property string|null $label
 * @property string|null $graph
 * @property array<string, string> $graph_i18ns
 */
class PropertyRegulation extends Model
{
    /** @use HasFactory<PropertyRegulationFactory> */
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
            'date' => 'date',
            'graph_i18ns' => 'array',
        ];
    }
}
