<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Locale;
use App\Enums\OfferType;
use App\Enums\PriceRange;
use App\Enums\RoomFilter;
use Database\Factories\PropertyFactory;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * A property imported from the Apimo API v3 `Property` response schema.
 *
 * `apimo_id` is Apimo's `id`; `created_at` / `updated_at` hold Apimo's own date strings, so Laravel timestamps are disabled.
 *
 * @property int $id
 * @property int $apimo_id
 * @property string|null $reference
 * @property int $agency
 * @property int $quality
 * @property int|null $agency_co_id
 * @property int|null $brand
 * @property string|null $sector
 * @property array{id: string, agency: string, active: bool, created_at: string, updated_at: string, firstname?: string, lastname?: string, language?: string, group?: string, email?: string|null, phone?: string|null, mobile?: string|null, fax?: string|null, birthday_at?: string|null, timezone?: string|null, picture?: string|null}|null $user
 * @property int $step
 * @property int $status
 * @property int $group
 * @property int|null $parent
 * @property int $category
 * @property int|null $subcategory
 * @property string|null $name
 * @property int $type
 * @property int $subtype
 * @property int|null $agreement_type
 * @property string|null $agreement_reference
 * @property Carbon|null $agreement_start_at
 * @property Carbon|null $agreement_end_at
 * @property string|null $block_name
 * @property string|null $lot_reference
 * @property string|null $cadastre_reference
 * @property string|null $stairs_reference
 * @property string|null $address
 * @property string|null $address_more
 * @property bool $publish_address
 * @property string $country
 * @property int|null $region_id
 * @property string|null $region_name
 * @property int|null $city_id
 * @property string|null $city_name
 * @property string|null $city_zipcode
 * @property int|null $district_id
 * @property string|null $district_name
 * @property float $longitude
 * @property float $latitude
 * @property int $radius
 * @property int $altitude
 * @property int $area_unit
 * @property float|null $area_value
 * @property float|null $area_total
 * @property float|null $area_weighted
 * @property string|null $plot_net_floor
 * @property int|null $plot_land_type
 * @property string|null $plot_width
 * @property string|null $plot_height
 * @property bool $plot_serviced_plot
 * @property float $rooms
 * @property int $bedrooms
 * @property int $sleeps
 * @property float|null $price_value
 * @property float|null $price_max
 * @property float|null $price_fees
 * @property int|null $price_unit
 * @property int|null $price_period
 * @property bool $price_hide
 * @property float|null $price_inventory
 * @property float|null $price_deposit
 * @property string $price_currency
 * @property float|null $price_commission
 * @property float|null $price_transfer_tax
 * @property float|null $price_contribution
 * @property float|null $price_pension
 * @property float|null $price_tenant
 * @property bool|null $price_vat
 * @property float|null $price_original_value
 * @property string|null $price_original_currency
 * @property float|null $price_commission_owner
 * @property float|null $price_commission_customer
 * @property float|null $price_sold
 * @property Carbon|null $price_sold_at
 * @property list<array{step: int, start_at: string, end_at: string, price: string|null, period: int|null, commission_rate: float|null, days: int|null}> $rates
 * @property int|null $residence_id
 * @property int|null $residence_type
 * @property float|null $residence_fees
 * @property int|null $residence_period
 * @property int|null $residence_lots
 * @property int|null $view_type
 * @property list<string> $view_landscape
 * @property list<string>|null $construction_method
 * @property string|null $construction_year
 * @property string|null $construction_step
 * @property string|null $construction_renovation_year
 * @property string|null $construction_renovation_cost
 * @property int|null $floor_type
 * @property string|null $floor_value
 * @property string|null $floor_levels
 * @property string|null $floor_floors
 * @property int|null $heating_device
 * @property list<string>|null $heating_devices
 * @property int|null $heating_access
 * @property int|null $heating_type
 * @property list<string>|null $heating_types
 * @property int|null $water_hot_device
 * @property int|null $water_hot_access
 * @property int|null $water_waste
 * @property int|null $condition
 * @property int|null $standing
 * @property string|null $style_name
 * @property string|null $twinned
 * @property string|null $facades
 * @property string|null $length
 * @property string|null $height
 * @property string|null $url
 * @property string|null $availability
 * @property Carbon|null $available_at
 * @property Carbon|null $delivered_at
 * @property list<string> $activities
 * @property list<string> $orientations
 * @property list<int> $tags
 * @property list<array{id: string, group: string, label: string, data: string|null, value: string}> $tags_customized
 * @property list<array{type: int, amount: string, year: int}> $financial
 * @property float|null $boat_length
 * @property float|null $boat_beam
 * @property float|null $boat_draft
 * @property int|null $boat_guests
 * @property int|null $boat_crew
 * @property float|null $boat_cruising_speed
 * @property int|null $boat_builder_id
 * @property string|null $boat_builder_name
 * @property float|null $life_annuity_length
 * @property list<array{gender: int, birthday: string|null}>|null $life_annuity_occupants
 * @property float|null $life_annuity_market_value
 * @property float|null $life_annuity_contribution
 * @property float|null $life_annuity_pension
 * @property string|null $auction_start_at
 * @property string|null $auction_end_at
 * @property float|null $auction_bid_min
 * @property float|null $shop_area
 * @property float|null $shop_window_width
 * @property float|null $shop_window_height
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class Property extends Model implements HasMedia
{
    /** @use HasFactory<PropertyFactory> */
    use HasFactory;

    use InteractsWithMedia;

    /**
     * Apimo `property_category` values listed as rentals: 2 rental, 3 seasonal rental.
     */
    public const array RENTAL_CATEGORIES = [2, 3];

    /**
     * Apimo `property_areas` values counted as bathrooms: 8 bathroom, 13 shower room, 41 and 42 the same with toilets.
     */
    public const array BATHROOM_AREA_TYPES = [8, 13, 41, 42];

    public $timestamps = false;

    /**
     * Delete pictures through Eloquent so their media files are removed; the database cascade alone would orphan them.
     */
    protected static function booted(): void
    {
        static::deleting(function (Property $property): void {
            $property->pictures()->get()->each->delete();
        });
    }

    public function offerType(): OfferType
    {
        return in_array($this->category, self::RENTAL_CATEGORIES, true) ? OfferType::Rent : OfferType::Sale;
    }

    public function commentIn(string $locale): ?PropertyComment
    {
        return $this->comments->firstWhere('language', $locale)
            ?? $this->comments->firstWhere('language', Locale::default()->value)
            ?? $this->comments->first();
    }

    /**
     * Bathrooms and shower rooms listed in the Apimo areas. Needs the `areas` relation loaded.
     */
    public function bathroomCount(): int
    {
        return (int) $this->areas
            ->filter(fn (PropertyArea $area): bool => in_array($area->type, self::BATHROOM_AREA_TYPES, true))
            ->sum('number');
    }

    /**
     * The URL segment of the property page: the local id, followed by a readable slug of the title.
     */
    public function routeSlug(string $title): string
    {
        return trim($this->id.'-'.Str::slug($title), '-');
    }

    /**
     * The local id leading a URL slug such as `42-villa-nice`, or null when the slug has none.
     */
    public static function idFromRouteSlug(string $slug): ?int
    {
        return preg_match('/^(\d+)(?:-|$)/', $slug, $matches) === 1 ? (int) $matches[1] : null;
    }

    /**
     * @return array<int, string>
     */
    public static function cities(): array
    {
        return static::query()
            ->whereNotNull('city_name')
            ->distinct()
            ->orderBy('city_name')
            ->pluck('city_name')
            ->values()
            ->all();
    }

    /**
     * @param  Builder<Property>  $query
     * @return Builder<Property>
     */
    #[Scope]
    protected function matchingSearch(Builder $query, ?int $type, ?PriceRange $priceRange, ?RoomFilter $rooms, string $city): Builder
    {
        return $query
            ->when($type, fn (Builder $query, int $type): Builder => $query->where('type', $type))
            ->when($priceRange, fn (Builder $query, PriceRange $priceRange): Builder => $query->inPriceRange($priceRange))
            ->when($rooms, fn (Builder $query, RoomFilter $rooms): Builder => $query->withRooms($rooms))
            ->when($city !== '', fn (Builder $query): Builder => $query->where('city_name', $city));
    }

    /**
     * Properties whose price is hidden on listings never match a price range.
     *
     * @param  Builder<Property>  $query
     * @return Builder<Property>
     */
    #[Scope]
    protected function inPriceRange(Builder $query, PriceRange $priceRange): Builder
    {
        return $query
            ->where('price_hide', false)
            ->whereNotNull('price_value')
            ->when($priceRange->minimum(), fn (Builder $query, int $minimum): Builder => $query->where('price_value', '>=', $minimum))
            ->when($priceRange->maximum(), fn (Builder $query, int $maximum): Builder => $query->where('price_value', '<', $maximum));
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
     * A studio is a single-room property (Apimo `rooms` = 1); the other filters count bedrooms.
     *
     * @param  Builder<Property>  $query
     * @return Builder<Property>
     */
    #[Scope]
    protected function withRooms(Builder $query, RoomFilter $rooms): Builder
    {
        return match ($rooms) {
            RoomFilter::Studio => $query->where('rooms', 1),
            RoomFilter::FourOrMore => $query->where('bedrooms', '>=', 4),
            default => $query->where('bedrooms', (int) $rooms->value),
        };
    }

    /**
     * The agency owning the property, matched on Apimo's `Property.agency` (integer) against `Agency.id` (string).
     *
     * @return BelongsTo<Agency, $this>
     */
    public function owningAgency(): BelongsTo
    {
        return $this->belongsTo(Agency::class, 'agency', 'apimo_id');
    }

    /**
     * The parent property (new-build programme or lot), matched on Apimo's `Property.parent`.
     *
     * @return BelongsTo<Property, $this>
     */
    public function parentProperty(): BelongsTo
    {
        return $this->belongsTo(Property::class, 'parent', 'apimo_id');
    }

    /**
     * @return HasMany<Property, $this>
     */
    public function childProperties(): HasMany
    {
        return $this->hasMany(Property::class, 'parent', 'apimo_id');
    }

    /**
     * @return HasMany<PropertyPicture, $this>
     */
    public function pictures(): HasMany
    {
        return $this->hasMany(PropertyPicture::class)->orderBy('rank');
    }

    /**
     * @return HasMany<PropertyMedia, $this>
     */
    public function medias(): HasMany
    {
        return $this->hasMany(PropertyMedia::class);
    }

    /**
     * @return HasMany<PropertyDocument, $this>
     */
    public function documents(): HasMany
    {
        return $this->hasMany(PropertyDocument::class);
    }

    /**
     * @return HasMany<PropertyComment, $this>
     */
    public function comments(): HasMany
    {
        return $this->hasMany(PropertyComment::class);
    }

    /**
     * @return HasMany<PropertyArea, $this>
     */
    public function areas(): HasMany
    {
        return $this->hasMany(PropertyArea::class);
    }

    /**
     * @return HasMany<PropertyRegulation, $this>
     */
    public function regulations(): HasMany
    {
        return $this->hasMany(PropertyRegulation::class);
    }

    /**
     * @return HasMany<PropertyService, $this>
     */
    public function services(): HasMany
    {
        return $this->hasMany(PropertyService::class);
    }

    /**
     * @return HasMany<PropertyProximity, $this>
     */
    public function proximities(): HasMany
    {
        return $this->hasMany(PropertyProximity::class);
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
            'agency' => 'integer',
            'quality' => 'integer',
            'agency_co_id' => 'integer',
            'brand' => 'integer',
            'user' => 'array',
            'step' => 'integer',
            'status' => 'integer',
            'group' => 'integer',
            'parent' => 'integer',
            'category' => 'integer',
            'subcategory' => 'integer',
            'type' => 'integer',
            'subtype' => 'integer',
            'agreement_type' => 'integer',
            'agreement_start_at' => 'date',
            'agreement_end_at' => 'date',
            'publish_address' => 'boolean',
            'region_id' => 'integer',
            'city_id' => 'integer',
            'district_id' => 'integer',
            'longitude' => 'float',
            'latitude' => 'float',
            'radius' => 'integer',
            'altitude' => 'integer',
            'area_unit' => 'integer',
            'area_value' => 'float',
            'area_total' => 'float',
            'area_weighted' => 'float',
            'plot_land_type' => 'integer',
            'plot_serviced_plot' => 'boolean',
            'rooms' => 'float',
            'bedrooms' => 'integer',
            'sleeps' => 'integer',
            'price_value' => 'float',
            'price_max' => 'float',
            'price_fees' => 'float',
            'price_unit' => 'integer',
            'price_period' => 'integer',
            'price_hide' => 'boolean',
            'price_inventory' => 'float',
            'price_deposit' => 'float',
            'price_commission' => 'float',
            'price_transfer_tax' => 'float',
            'price_contribution' => 'float',
            'price_pension' => 'float',
            'price_tenant' => 'float',
            'price_vat' => 'boolean',
            'price_original_value' => 'float',
            'price_commission_owner' => 'float',
            'price_commission_customer' => 'float',
            'price_sold' => 'float',
            'price_sold_at' => 'date',
            'rates' => 'array',
            'residence_id' => 'integer',
            'residence_type' => 'integer',
            'residence_fees' => 'float',
            'residence_period' => 'integer',
            'residence_lots' => 'integer',
            'view_type' => 'integer',
            'view_landscape' => 'array',
            'construction_method' => 'array',
            'floor_type' => 'integer',
            'heating_device' => 'integer',
            'heating_devices' => 'array',
            'heating_access' => 'integer',
            'heating_type' => 'integer',
            'heating_types' => 'array',
            'water_hot_device' => 'integer',
            'water_hot_access' => 'integer',
            'water_waste' => 'integer',
            'condition' => 'integer',
            'standing' => 'integer',
            'available_at' => 'date',
            'delivered_at' => 'date',
            'activities' => 'array',
            'orientations' => 'array',
            'tags' => 'array',
            'tags_customized' => 'array',
            'financial' => 'array',
            'boat_length' => 'float',
            'boat_beam' => 'float',
            'boat_draft' => 'float',
            'boat_guests' => 'integer',
            'boat_crew' => 'integer',
            'boat_cruising_speed' => 'float',
            'boat_builder_id' => 'integer',
            'life_annuity_length' => 'float',
            'life_annuity_occupants' => 'array',
            'life_annuity_market_value' => 'float',
            'life_annuity_contribution' => 'float',
            'life_annuity_pension' => 'float',
            'auction_bid_min' => 'float',
            'shop_area' => 'float',
            'shop_window_width' => 'float',
            'shop_window_height' => 'float',
        ];
    }
}
