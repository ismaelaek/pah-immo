<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Agency;
use App\Models\Property;
use App\Services\Apimo\ApimoClient;
use App\Services\Apimo\PropertyImporter;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Imports the properties of every active agency in one paginated Apimo call (`agency_id = 101_102_103`).
 *
 * Incremental runs send the `timestamp` returned by the previous run, so only changed properties come back and
 * a run usually costs one request. A full run fetches everything (one request per 1,000 properties) and deletes
 * local properties Apimo no longer returns, such as sold or withdrawn listings.
 */
class SyncApimoProperties implements ShouldBeUnique, ShouldQueue
{
    use Queueable;

    public const string TIMESTAMP_CACHE_KEY = 'apimo:properties:timestamp';

    /**
     * Never retried automatically: a retry spends quota again, and the next scheduled run picks up instead.
     */
    public int $tries = 1;

    public int $timeout = 600;

    public function __construct(public bool $full = false) {}

    public function uniqueId(): string
    {
        return $this->full ? 'full' : 'incremental';
    }

    /**
     * @return list<object>
     */
    public function middleware(): array
    {
        return [(new WithoutOverlapping('apimo-properties'))->dontRelease()->expireAfter($this->timeout)];
    }

    public function handle(ApimoClient $client, PropertyImporter $importer): void
    {
        /** @var Collection<int, string> $agencyIds */
        $agencyIds = Agency::query()->where('active', true)->orderBy('apimo_id')->pluck('apimo_id');

        if ($agencyIds->isEmpty()) {
            Log::warning('Apimo property sync skipped: no active agency. Run the agency sync first.');

            return;
        }

        $updatedSince = $this->full ? null : Cache::get(self::TIMESTAMP_CACHE_KEY);
        $latestTimestamp = $updatedSince;
        $returnedKeys = [];
        $offset = 0;

        do {
            $page = $client->properties($agencyIds->all(), $updatedSince, $offset);

            foreach ($page['properties'] as $payload) {
                $returnedKeys[$payload['agency'].'-'.$payload['id']] = true;

                try {
                    $importer->import($payload);
                } catch (Throwable $exception) {
                    Log::error('Apimo property import failed', [
                        'apimo_id' => $payload['id'],
                        'agency' => $payload['agency'],
                        'exception' => $exception,
                    ]);
                }
            }

            $latestTimestamp = max($latestTimestamp, $page['timestamp']);
            $offset += count($page['properties']);
        } while ($page['properties'] !== [] && $offset < $page['total_items']);

        if ($this->full) {
            $this->deletePropertiesNotReturned($agencyIds, $returnedKeys);
        }

        if ($latestTimestamp !== null) {
            Cache::forever(self::TIMESTAMP_CACHE_KEY, $latestTimestamp);
        }
    }

    /**
     * Deleted one by one through Eloquent so picture files are removed with them.
     *
     * @param  Collection<int, string>  $agencyIds
     * @param  array<string, true>  $returnedKeys
     */
    private function deletePropertiesNotReturned(Collection $agencyIds, array $returnedKeys): void
    {
        Property::query()
            ->whereIn('agency', $agencyIds->map(fn (string $agencyId): int => (int) $agencyId))
            ->lazyById()
            ->reject(fn (Property $property): bool => isset($returnedKeys[$property->agency.'-'.$property->apimo_id]))
            ->each(fn (Property $property): ?bool => $property->delete());
    }
}
