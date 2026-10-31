<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\CatalogEntry;
use App\Services\Apimo\ApimoClient;
use App\Services\Apimo\ApimoException;
use App\Services\Apimo\ApimoQuotaExceededException;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Imports, in every language, the Apimo catalogs referenced by the stored property columns.
 * Costs one Apimo request per catalog.
 */
class SyncApimoCatalogs implements ShouldBeUnique, ShouldQueue
{
    use Queueable;

    /**
     * @var list<string>
     */
    public const array CATALOGS = [
        'construction_step',
        'document_type',
        'property_activity',
        'property_adjacency',
        'property_agreement',
        'property_areas',
        'property_availability',
        'property_building',
        'property_category',
        'property_condition',
        'property_construction_method',
        'property_financial',
        'property_floor',
        'property_flooring',
        'property_group',
        'property_heating_access',
        'property_heating_device',
        'property_heating_type',
        'property_hot_water_access',
        'property_hot_water_device',
        'property_land',
        'property_orientation',
        'property_period',
        'property_proximity',
        'property_regulation',
        'property_service',
        'property_standing',
        'property_status',
        'property_step',
        'property_subcategory',
        'property_subtype',
        'property_type',
        'property_view_landscape',
        'property_view_type',
        'property_waste_water',
        'tags',
        'unit_area',
        'unit_length',
    ];

    /**
     * Never retried automatically: a retry spends quota again, and the next scheduled run picks up instead.
     */
    public int $tries = 1;

    public int $timeout = 300;

    public function handle(ApimoClient $client): void
    {
        foreach (self::CATALOGS as $catalog) {
            try {
                $entries = $client->catalog($catalog);
            } catch (ApimoQuotaExceededException $exception) {
                throw $exception;
            } catch (ApimoException $exception) {
                Log::error('Apimo catalog sync failed', ['catalog' => $catalog, 'exception' => $exception]);

                continue;
            }

            DB::transaction(function () use ($catalog, $entries): void {
                CatalogEntry::query()->where('catalog', $catalog)->delete();

                foreach (array_chunk($entries, 500) as $chunk) {
                    CatalogEntry::query()->insert(array_map(fn (array $entry): array => [
                        'catalog' => $catalog,
                        'apimo_id' => Arr::get($entry, 'id'),
                        'culture' => Arr::get($entry, 'culture'),
                        'name' => Arr::get($entry, 'name'),
                        'name_plurial' => Arr::get($entry, 'name_plurial'),
                        'value' => Arr::get($entry, 'value'),
                    ], $chunk));
                }
            });
        }
    }
}
