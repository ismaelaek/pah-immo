<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\PropertyCommentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * A description in one language from Apimo API v3 `PropertyComment`.
 *
 * @property int $id
 * @property int $property_id
 * @property string $language
 * @property string|null $title
 * @property string|null $subtitle
 * @property string|null $hook
 * @property string|null $comment
 * @property string|null $comment_full
 */
class PropertyComment extends Model
{
    /** @use HasFactory<PropertyCommentFactory> */
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
