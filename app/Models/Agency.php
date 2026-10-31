<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\AgencyFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * An agency imported from the Apimo API v3 `Agency` response schema.
 *
 * `apimo_id` is Apimo's `id` (a string); `created_at` / `updated_at` hold Apimo's own date strings.
 *
 * @property int $id
 * @property string $apimo_id
 * @property string|null $reference
 * @property bool $active
 * @property string $name
 * @property string|null $company_id
 * @property string|null $company_name
 * @property string|null $brand_id
 * @property string|null $brand_name
 * @property list<array{brand: array{id: string, name: string|null}, name: string|null}> $networks
 * @property string|null $address
 * @property string|null $address_more
 * @property string|null $city_id
 * @property string|null $city_name
 * @property string|null $city_zipcode
 * @property string|null $district_id
 * @property string|null $district_name
 * @property string $country
 * @property string|null $region
 * @property string|null $latitude
 * @property string|null $longitude
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $fax
 * @property string|null $url
 * @property string $logo
 * @property string|null $logo_svg
 * @property list<string> $pictures
 * @property string|null $picture
 * @property int $quality
 * @property string $currency
 * @property string|null $timetable
 * @property list<array{id: string|null, category: string|null, range_min: string|null, range_max: string|null, commission_price: string|null, commission_rate: string|null, comment: string|null, url: string|null}> $rates
 * @property list<array{type: string, partner: string|null, name: string|null, reference: string|null, city?: string, amount: string|null, currency: string|null}> $partners
 * @property list<array{language: string, title: string, content: string}> $stories
 * @property string $subscription
 * @property string $created_at
 * @property string $updated_at
 */
class Agency extends Model
{
    /** @use HasFactory<AgencyFactory> */
    use HasFactory;

    public $timestamps = false;

    /**
     * Properties whose Apimo `agency` (integer) matches this agency's Apimo `id` (string).
     *
     * @return HasMany<Property, $this>
     */
    public function properties(): HasMany
    {
        return $this->hasMany(Property::class, 'agency', 'apimo_id');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'active' => 'boolean',
            'networks' => 'array',
            'pictures' => 'array',
            'quality' => 'integer',
            'rates' => 'array',
            'partners' => 'array',
            'stories' => 'array',
        ];
    }
}
