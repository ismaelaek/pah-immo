<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\PropertyDocumentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * A document from Apimo API v3 `PropertyDocument`. `apimo_id` is Apimo's `id` (a string); `owner` is the Apimo id of the property owning the file.
 *
 * @property int $id
 * @property int $property_id
 * @property string $apimo_id
 * @property int $owner
 * @property string|null $contact
 * @property string $name
 * @property string $url
 * @property string $download_url
 * @property string $filesize
 * @property string|null $type_id
 * @property string $internet
 * @property string $child
 * @property string $extranet
 * @property string $private
 * @property string $created_at
 * @property string $updated_at
 */
class PropertyDocument extends Model
{
    /** @use HasFactory<PropertyDocumentFactory> */
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
     * The property owning the document, matched on Apimo's `PropertyDocument.owner`. It can differ from
     * `property` when a parent property shares the document with its child lots.
     *
     * @return BelongsTo<Property, $this>
     */
    public function ownerProperty(): BelongsTo
    {
        return $this->belongsTo(Property::class, 'owner', 'apimo_id');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'owner' => 'integer',
        ];
    }
}
