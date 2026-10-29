<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Equipment;
use App\Enums\MediaCollection;
use App\Enums\MediaConversion;
use App\Enums\OfferType;
use App\Enums\PropertyType;
use Database\Factories\PropertyFactory;
use Illuminate\Database\Eloquent\Casts\AsEnumCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string $reference
 * @property string $description
 * @property string $body
 * @property PropertyType $type
 * @property OfferType $offer_type
 * @property int $price
 * @property int $area
 * @property int|null $bedrooms
 * @property int|null $bathrooms
 * @property string|null $neighborhood
 * @property string $city
 * @property string|null $latitude
 * @property string|null $longitude
 * @property Collection<int, Equipment>|null $equipment
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 */
class Property extends Model implements HasMedia
{
    /** @use HasFactory<PropertyFactory> */
    use HasFactory, HasSlug, InteractsWithMedia, SoftDeletes;

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate()
            ->preventOverwrite();
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(MediaCollection::Images->value)
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
     * @return array<string, mixed>
     */
    protected function casts(): array
    {
        return [
            'type' => PropertyType::class,
            'offer_type' => OfferType::class,
            'price' => 'integer',
            'area' => 'integer',
            'bedrooms' => 'integer',
            'bathrooms' => 'integer',
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'equipment' => AsEnumCollection::of(Equipment::class),
        ];
    }
}
