<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\MediaCollection;
use App\Enums\MediaConversion;
use Database\Factories\PropertyPictureFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * A picture from Apimo API v3 `PropertyPicture`. `apimo_id` is Apimo's `id`; `internet`, `print` and `child` are "1"/"0" strings as documented.
 *
 * The image file at `url` is downloaded into the `images` media collection so it can be served locally with conversions.
 *
 * @property int $id
 * @property int $property_id
 * @property int $apimo_id
 * @property int $type_id
 * @property int $rank
 * @property string $url
 * @property int $width_max
 * @property int $height_max
 * @property string $internet
 * @property string $print
 * @property bool $panorama
 * @property string $child
 * @property string|null $reference
 * @property list<array{language: string, comment: string}> $comments
 * @property bool|null $ai_generated
 */
class PropertyPicture extends Model implements HasMedia
{
    /** @use HasFactory<PropertyPictureFactory> */
    use HasFactory, InteractsWithMedia;

    public $timestamps = false;

    /**
     * @return BelongsTo<Property, $this>
     */
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * The downloaded image in the given conversion, or Apimo's original URL while the download is pending.
     * Needs the `media` relation loaded for lists.
     */
    public function imageUrl(MediaConversion $conversion): string
    {
        $media = $this->getFirstMedia(MediaCollection::Images->value);

        if ($media === null) {
            return $this->url;
        }

        $url = $media->hasGeneratedConversion($conversion->value)
            ? $media->getUrl($conversion->value)
            : $media->getUrl();

        return $url.'?v='.$media->updated_at?->timestamp;
    }

    /**
     * Whether the stored image was downloaded from the given Apimo URL.
     */
    public function hasImageFrom(string $url): bool
    {
        return $this->getFirstMedia(MediaCollection::Images->value)?->getCustomProperty('source_url') === $url;
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(MediaCollection::Images->value)
            ->singleFile()
            ->useDisk('public')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp', 'image/avif']);
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion(MediaConversion::Small->value)
            ->performOnCollections(MediaCollection::Images->value)
            ->fit(Fit::Contain, 320, 320)
            ->nonQueued();

        $this
            ->addMediaConversion(MediaConversion::Medium->value)
            ->performOnCollections(MediaCollection::Images->value)
            ->fit(Fit::Contain, 640, 640)
            ->nonQueued();

        $this
            ->addMediaConversion(MediaConversion::Large->value)
            ->performOnCollections(MediaCollection::Images->value)
            ->fit(Fit::Contain, 1280, 1280)
            ->nonQueued();
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'apimo_id' => 'integer',
            'type_id' => 'integer',
            'rank' => 'integer',
            'width_max' => 'integer',
            'height_max' => 'integer',
            'panorama' => 'boolean',
            'comments' => 'array',
            'ai_generated' => 'boolean',
        ];
    }
}
