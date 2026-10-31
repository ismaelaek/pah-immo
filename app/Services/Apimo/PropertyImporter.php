<?php

declare(strict_types=1);

namespace App\Services\Apimo;

use App\Jobs\DownloadPropertyPicture;
use App\Models\Property;
use App\Models\PropertyPicture;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

/**
 * Stores one Apimo `Property` payload and its nested resources.
 *
 * Values are copied as documented. A missing non-nullable field is not defaulted: the insert fails, so the
 * gap shows up in the logs instead of being hidden.
 */
class PropertyImporter
{
    /**
     * @param  array<string, mixed>  $payload
     */
    public function import(array $payload): Property
    {
        return DB::transaction(function () use ($payload): Property {
            $property = Property::query()->updateOrCreate(
                ['agency' => (int) $payload['agency'], 'apimo_id' => (int) $payload['id']],
                $this->propertyAttributes($payload),
            );

            $this->syncPictures($property, $payload['pictures']);

            $this->replaceChildren($property->medias(), $payload['medias'], fn (array $media): array => [
                'apimo_id' => (string) $media['id'],
                'type' => Arr::get($media, 'type'),
                'value' => Arr::get($media, 'value'),
                'internet' => Arr::get($media, 'internet'),
            ]);

            $this->replaceChildren($property->documents(), $payload['documents'], fn (array $document): array => [
                'apimo_id' => (string) $document['id'],
                'owner' => Arr::get($document, 'owner'),
                'contact' => Arr::get($document, 'contact'),
                'name' => Arr::get($document, 'name'),
                'url' => Arr::get($document, 'url'),
                'download_url' => Arr::get($document, 'download_url'),
                'filesize' => Arr::get($document, 'filesize'),
                'type_id' => Arr::get($document, 'type_id'),
                'internet' => Arr::get($document, 'internet'),
                'child' => Arr::get($document, 'child'),
                'extranet' => Arr::get($document, 'extranet'),
                'private' => Arr::get($document, 'private'),
                'created_at' => Arr::get($document, 'created_at'),
                'updated_at' => Arr::get($document, 'updated_at'),
            ]);

            $this->replaceChildren($property->comments(), $payload['comments'], fn (array $comment): array => [
                'language' => Arr::get($comment, 'language'),
                'title' => Arr::get($comment, 'title'),
                'subtitle' => Arr::get($comment, 'subtitle'),
                'hook' => Arr::get($comment, 'hook'),
                'comment' => Arr::get($comment, 'comment'),
                'comment_full' => Arr::get($comment, 'comment_full'),
            ]);

            $this->replaceChildren($property->areas(), $payload['areas'], fn (array $area): array => [
                'type' => Arr::get($area, 'type'),
                'number' => Arr::get($area, 'number'),
                'area' => Arr::get($area, 'area'),
                'flooring' => Arr::get($area, 'flooring'),
                'ceiling_height' => Arr::get($area, 'ceiling_height'),
                'floor_type' => Arr::get($area, 'floor.type'),
                'floor_value' => Arr::get($area, 'floor.value'),
                'orientations' => Arr::get($area, 'orientations'),
                'comments' => Arr::get($area, 'comments'),
                'lot_type' => Arr::get($area, 'lot.type'),
                'lot_rank' => Arr::get($area, 'lot.rank'),
                'lot_name' => Arr::get($area, 'lot.name'),
                'beds_single' => Arr::get($area, 'beds.single'),
                'beds_double' => Arr::get($area, 'beds.double'),
                'price' => Arr::get($area, 'price'),
            ]);

            $this->replaceChildren($property->regulations(), $payload['regulations'], fn (array $regulation): array => [
                'type' => Arr::get($regulation, 'type'),
                'value' => Arr::get($regulation, 'value'),
                'date' => Arr::get($regulation, 'date'),
                'label' => Arr::get($regulation, 'label'),
                'graph' => Arr::get($regulation, 'graph'),
                'graph_i18ns' => Arr::get($regulation, 'graph_i18ns'),
            ]);

            $this->replaceChildren($property->services(), $payload['services'], fn (int|string|array $service): array => is_array($service)
                ? ['type' => Arr::get($service, 'type'), 'comments' => Arr::get($service, 'comments')]
                : ['type' => (int) $service, 'comments' => null]);

            $this->replaceChildren($property->proximities(), $payload['proximities'], fn (int|string|array $proximity): array => is_array($proximity)
                ? ['type' => Arr::get($proximity, 'type'), 'length' => Arr::get($proximity, 'length'), 'length_type' => Arr::get($proximity, 'length_type')]
                : ['type' => (int) $proximity, 'length' => null, 'length_type' => null]);

            return $property;
        });
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    private function propertyAttributes(array $payload): array
    {
        $reference = Arr::get($payload, 'reference');
        $floorValue = Arr::get($payload, 'floor.value');

        return [
            'reference' => $reference === null ? null : (string) $reference,
            'quality' => Arr::get($payload, 'quality'),
            'agency_co_id' => Arr::get($payload, 'agency_co_id'),
            'brand' => Arr::get($payload, 'brand'),
            'sector' => Arr::get($payload, 'sector'),
            'user' => Arr::get($payload, 'user'),
            'step' => Arr::get($payload, 'step'),
            'status' => Arr::get($payload, 'status'),
            'group' => Arr::get($payload, 'group'),
            'parent' => Arr::get($payload, 'parent'),
            'category' => Arr::get($payload, 'category'),
            'subcategory' => Arr::get($payload, 'subcategory'),
            'name' => Arr::get($payload, 'name'),
            'type' => Arr::get($payload, 'type'),
            'subtype' => Arr::get($payload, 'subtype'),
            'agreement_type' => Arr::get($payload, 'agreement.type'),
            'agreement_reference' => Arr::get($payload, 'agreement.reference'),
            'agreement_start_at' => Arr::get($payload, 'agreement.start_at'),
            'agreement_end_at' => Arr::get($payload, 'agreement.end_at'),
            'block_name' => Arr::get($payload, 'block_name'),
            'lot_reference' => Arr::get($payload, 'lot_reference'),
            'cadastre_reference' => Arr::get($payload, 'cadastre_reference'),
            'stairs_reference' => Arr::get($payload, 'stairs_reference'),
            'address' => Arr::get($payload, 'address'),
            'address_more' => Arr::get($payload, 'address_more'),
            'publish_address' => Arr::get($payload, 'publish_address'),
            'country' => Arr::get($payload, 'country'),
            'region_id' => Arr::get($payload, 'region.id'),
            'region_name' => Arr::get($payload, 'region.name'),
            'city_id' => Arr::get($payload, 'city.id'),
            'city_name' => Arr::get($payload, 'city.name'),
            'city_zipcode' => Arr::get($payload, 'city.zipcode'),
            'district_id' => Arr::get($payload, 'district.id'),
            'district_name' => Arr::get($payload, 'district.name'),
            'longitude' => Arr::get($payload, 'longitude'),
            'latitude' => Arr::get($payload, 'latitude'),
            'radius' => Arr::get($payload, 'radius'),
            'altitude' => Arr::get($payload, 'altitude'),
            'area_unit' => Arr::get($payload, 'area.unit'),
            'area_value' => Arr::get($payload, 'area.value'),
            'area_total' => Arr::get($payload, 'area.total'),
            'area_weighted' => Arr::get($payload, 'area.weighted'),
            'plot_net_floor' => Arr::get($payload, 'plot.net_floor'),
            'plot_land_type' => Arr::get($payload, 'plot.land_type'),
            'plot_width' => Arr::get($payload, 'plot.width'),
            'plot_height' => Arr::get($payload, 'plot.height'),
            'plot_serviced_plot' => Arr::get($payload, 'plot.serviced_plot'),
            'rooms' => Arr::get($payload, 'rooms'),
            'bedrooms' => Arr::get($payload, 'bedrooms'),
            'sleeps' => Arr::get($payload, 'sleeps'),
            'price_value' => Arr::get($payload, 'price.value'),
            'price_max' => Arr::get($payload, 'price.max'),
            'price_fees' => Arr::get($payload, 'price.fees'),
            'price_unit' => Arr::get($payload, 'price.unit'),
            'price_period' => Arr::get($payload, 'price.period'),
            'price_hide' => Arr::get($payload, 'price.hide'),
            'price_inventory' => Arr::get($payload, 'price.inventory'),
            'price_deposit' => Arr::get($payload, 'price.deposit'),
            'price_currency' => Arr::get($payload, 'price.currency'),
            'price_commission' => Arr::get($payload, 'price.commission'),
            'price_transfer_tax' => Arr::get($payload, 'price.transfer_tax'),
            'price_contribution' => Arr::get($payload, 'price.contribution'),
            'price_pension' => Arr::get($payload, 'price.pension'),
            'price_tenant' => Arr::get($payload, 'price.tenant'),
            'price_vat' => Arr::get($payload, 'price.vat'),
            'price_original_value' => Arr::get($payload, 'price.original_value'),
            'price_original_currency' => Arr::get($payload, 'price.original_currency'),
            'price_commission_owner' => Arr::get($payload, 'price.commission_owner'),
            'price_commission_customer' => Arr::get($payload, 'price.commission_customer'),
            'price_sold' => Arr::get($payload, 'price.sold'),
            'price_sold_at' => Arr::get($payload, 'price.sold_at'),
            'rates' => Arr::get($payload, 'rates'),
            'residence_id' => Arr::get($payload, 'residence.id'),
            'residence_type' => Arr::get($payload, 'residence.type'),
            'residence_fees' => Arr::get($payload, 'residence.fees'),
            'residence_period' => Arr::get($payload, 'residence.period'),
            'residence_lots' => Arr::get($payload, 'residence.lots'),
            'view_type' => Arr::get($payload, 'view.type'),
            'view_landscape' => Arr::get($payload, 'view.landscape'),
            'construction_method' => Arr::get($payload, 'construction.method'),
            'construction_year' => Arr::get($payload, 'construction.construction_year'),
            'construction_step' => Arr::get($payload, 'construction.construction_step'),
            'construction_renovation_year' => Arr::get($payload, 'construction.renovation_year'),
            'construction_renovation_cost' => Arr::get($payload, 'construction.renovation_cost'),
            'floor_type' => Arr::get($payload, 'floor.type'),
            'floor_value' => $floorValue === null ? null : (string) $floorValue,
            'floor_levels' => Arr::get($payload, 'floor.levels'),
            'floor_floors' => Arr::get($payload, 'floor.floors'),
            'heating_device' => Arr::get($payload, 'heating.device'),
            'heating_devices' => Arr::get($payload, 'heating.devices'),
            'heating_access' => Arr::get($payload, 'heating.access'),
            'heating_type' => Arr::get($payload, 'heating.type'),
            'heating_types' => Arr::get($payload, 'heating.types'),
            'water_hot_device' => Arr::get($payload, 'water.hot_device'),
            'water_hot_access' => Arr::get($payload, 'water.hot_access'),
            'water_waste' => Arr::get($payload, 'water.waste'),
            'condition' => Arr::get($payload, 'condition'),
            'standing' => Arr::get($payload, 'standing'),
            'style_name' => Arr::get($payload, 'style.name'),
            'twinned' => Arr::get($payload, 'twinned'),
            'facades' => Arr::get($payload, 'facades'),
            'length' => Arr::get($payload, 'length'),
            'height' => Arr::get($payload, 'height'),
            'url' => Arr::get($payload, 'url'),
            'availability' => Arr::get($payload, 'availability'),
            'available_at' => Arr::get($payload, 'available_at'),
            'delivered_at' => Arr::get($payload, 'delivered_at'),
            'activities' => Arr::get($payload, 'activities'),
            'orientations' => Arr::get($payload, 'orientations'),
            'tags' => Arr::get($payload, 'tags'),
            'tags_customized' => Arr::get($payload, 'tags_customized'),
            'financial' => Arr::get($payload, 'financial'),
            'boat_length' => Arr::get($payload, 'boat.length'),
            'boat_beam' => Arr::get($payload, 'boat.beam'),
            'boat_draft' => Arr::get($payload, 'boat.draft'),
            'boat_guests' => Arr::get($payload, 'boat.guests'),
            'boat_crew' => Arr::get($payload, 'boat.crew'),
            'boat_cruising_speed' => Arr::get($payload, 'boat.cruising_speed'),
            'boat_builder_id' => Arr::get($payload, 'boat.builder.id'),
            'boat_builder_name' => Arr::get($payload, 'boat.builder.name'),
            'life_annuity_length' => Arr::get($payload, 'life_annuity.length'),
            'life_annuity_occupants' => Arr::get($payload, 'life_annuity.occupants'),
            'life_annuity_market_value' => Arr::get($payload, 'life_annuity.market_value'),
            'life_annuity_contribution' => Arr::get($payload, 'life_annuity.contribution'),
            'life_annuity_pension' => Arr::get($payload, 'life_annuity.pension'),
            'auction_start_at' => Arr::get($payload, 'auction.start_at'),
            'auction_end_at' => Arr::get($payload, 'auction.end_at'),
            'auction_bid_min' => Arr::get($payload, 'auction.bid_min'),
            'shop_area' => Arr::get($payload, 'shop.area'),
            'shop_window_width' => Arr::get($payload, 'shop.window_width'),
            'shop_window_height' => Arr::get($payload, 'shop.window_height'),
            'created_at' => Arr::get($payload, 'created_at'),
            'created_by' => Arr::get($payload, 'created_by'),
            'updated_at' => Arr::get($payload, 'updated_at'),
            'updated_by' => Arr::get($payload, 'updated_by'),
        ];
    }

    /**
     * Upsert pictures by Apimo id, delete the ones Apimo no longer returns through Eloquent so their files go too,
     * and queue a download for every picture whose current URL is not stored yet.
     *
     * @param  list<array<string, mixed>>  $pictures
     */
    private function syncPictures(Property $property, array $pictures): void
    {
        $existing = $property->pictures()->with('media')->get()->keyBy('apimo_id');
        $keptIds = [];

        foreach ($pictures as $data) {
            $apimoId = (int) $data['id'];
            $keptIds[] = $apimoId;

            /** @var PropertyPicture $picture */
            $picture = $existing->get($apimoId) ?? $property->pictures()->make(['apimo_id' => $apimoId]);

            $picture->fill([
                'type_id' => Arr::get($data, 'type_id'),
                'rank' => Arr::get($data, 'rank'),
                'url' => Arr::get($data, 'url'),
                'width_max' => Arr::get($data, 'width_max'),
                'height_max' => Arr::get($data, 'height_max'),
                'internet' => Arr::get($data, 'internet'),
                'print' => Arr::get($data, 'print'),
                'panorama' => Arr::get($data, 'panorama'),
                'child' => Arr::get($data, 'child'),
                'reference' => Arr::get($data, 'reference'),
                'comments' => Arr::get($data, 'comments'),
                'ai_generated' => Arr::get($data, 'ai_generated'),
            ])->save();

            if ($picture->wasRecentlyCreated || ! $picture->hasImageFrom($picture->url)) {
                DownloadPropertyPicture::dispatch($picture)->afterCommit();
            }
        }

        $existing
            ->reject(fn (PropertyPicture $picture): bool => in_array($picture->apimo_id, $keptIds, true))
            ->each(fn (PropertyPicture $picture): ?bool => $picture->delete());
    }

    /**
     * Nested resources without a stable Apimo identifier are replaced wholesale on every import.
     *
     * @template TRelated of Model
     *
     * @param  HasMany<TRelated, Property>  $relation
     * @param  list<mixed>  $items
     * @param  callable(mixed): array<string, mixed>  $toAttributes
     */
    private function replaceChildren(HasMany $relation, array $items, callable $toAttributes): void
    {
        $relation->delete();
        $relation->createMany(array_map($toAttributes, $items));
    }
}
