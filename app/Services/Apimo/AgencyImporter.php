<?php

declare(strict_types=1);

namespace App\Services\Apimo;

use App\Models\Agency;
use Illuminate\Support\Arr;

/**
 * Stores one Apimo `Agency` payload. Missing non-nullable fields are not defaulted.
 */
class AgencyImporter
{
    /**
     * @param  array<string, mixed>  $payload
     */
    public function import(array $payload): Agency
    {
        return Agency::query()->updateOrCreate(['apimo_id' => (string) $payload['id']], [
            'reference' => Arr::get($payload, 'reference'),
            'active' => Arr::get($payload, 'active'),
            'name' => Arr::get($payload, 'name'),
            'company_id' => Arr::get($payload, 'company.id'),
            'company_name' => Arr::get($payload, 'company.name'),
            'brand_id' => Arr::get($payload, 'brand.id'),
            'brand_name' => Arr::get($payload, 'brand.name'),
            'networks' => Arr::get($payload, 'networks'),
            'address' => Arr::get($payload, 'address'),
            'address_more' => Arr::get($payload, 'address_more'),
            'city_id' => Arr::get($payload, 'city.id'),
            'city_name' => Arr::get($payload, 'city.name'),
            'city_zipcode' => Arr::get($payload, 'city.zipcode'),
            'district_id' => Arr::get($payload, 'district.id'),
            'district_name' => Arr::get($payload, 'district.name'),
            'country' => Arr::get($payload, 'country'),
            'region' => Arr::get($payload, 'region'),
            'latitude' => Arr::get($payload, 'latitude'),
            'longitude' => Arr::get($payload, 'longitude'),
            'email' => Arr::get($payload, 'email'),
            'phone' => Arr::get($payload, 'phone'),
            'fax' => Arr::get($payload, 'fax'),
            'url' => Arr::get($payload, 'url'),
            'logo' => Arr::get($payload, 'logo'),
            'logo_svg' => Arr::get($payload, 'logo_svg'),
            'pictures' => Arr::get($payload, 'pictures'),
            'picture' => Arr::get($payload, 'picture'),
            'quality' => Arr::get($payload, 'quality'),
            'currency' => Arr::get($payload, 'currency'),
            'timetable' => Arr::get($payload, 'timetable'),
            'rates' => Arr::get($payload, 'rates'),
            'partners' => Arr::get($payload, 'partners'),
            'stories' => Arr::get($payload, 'stories'),
            'subscription' => Arr::get($payload, 'subscription'),
            'created_at' => Arr::get($payload, 'created_at'),
            'updated_at' => Arr::get($payload, 'updated_at'),
        ]);
    }
}
