<?php

declare(strict_types=1);

namespace App\Models;

use App\Casts\Money;
use App\Enums\Equipment;
use App\Enums\MediaCollection;
use App\Enums\MediaConversion;
use App\Enums\OfferType;
use App\Enums\PriceRange;
use App\Enums\PropertyType;
use App\Enums\RoomFilter;
use Database\Factories\PropertyFactory;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
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
 * @property float $price
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

    /**
     * @return array<int, string>
     */
    public static function cities(): array
    {
        return static::query()
            ->distinct()
            ->orderBy('city')
            ->get(['city'])
            ->map(fn (Property $property): string => $property->city)
            ->values()
            ->all();
    }

    /**
     * @param  Builder<Property>  $query
     * @return Builder<Property>
     */
    #[Scope]
    protected function matchingSearch(Builder $query, ?PropertyType $type, ?PriceRange $priceRange, ?RoomFilter $rooms, string $city): Builder
    {
        return $query
            ->when($type, fn (Builder $query, PropertyType $type): Builder => $query->where('type', $type))
            ->when($priceRange, fn (Builder $query, PriceRange $priceRange): Builder => $query->inPriceRange($priceRange))
            ->when($rooms, fn (Builder $query, RoomFilter $rooms): Builder => $query->withRooms($rooms))
            ->when($city !== '', fn (Builder $query): Builder => $query->where('city', $city));
    }

    /**
     * @param  Builder<Property>  $query
     * @return Builder<Property>
     */
    #[Scope]
    protected function inPriceRange(Builder $query, PriceRange $priceRange): Builder
    {
        return $query
            ->when($priceRange->minimum(), fn (Builder $query, int $minimum): Builder => $query->where('price', '>=', Money::toMinorUnits($minimum)))
            ->when($priceRange->maximum(), fn (Builder $query, int $maximum): Builder => $query->where('price', '<', Money::toMinorUnits($maximum)));
    }

    /**
     * @param  Builder<Property>  $query
     * @return Builder<Property>
     */
    #[Scope]
    protected function withinBounds(Builder $query, float $south, float $west, float $north, float $east): Builder
    {
        return $query
            ->whereBetween('latitude', [$south, $north])
            ->whereBetween('longitude', [$west, $east]);
    }

    /**
     * @param  Builder<Property>  $query
     * @return Builder<Property>
     */
    #[Scope]
    protected function withRooms(Builder $query, RoomFilter $rooms): Builder
    {
        return match ($rooms) {
            RoomFilter::Studio => $query->where('type', PropertyType::Studio),
            RoomFilter::FourOrMore => $query->where('bedrooms', '>=', 4),
            default => $query->where('bedrooms', (int) $rooms->value),
        };
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
            'price' => Money::class,
            'area' => 'integer',
            'bedrooms' => 'integer',
            'bathrooms' => 'integer',
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'equipment' => AsEnumCollection::of(Equipment::class),
        ];
    }
}
