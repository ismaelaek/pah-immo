<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Agency;
use App\Services\Apimo\AgencyImporter;
use App\Services\Apimo\ApimoClient;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

/**
 * Imports the agencies connected to the Apimo partner account. Costs one Apimo request.
 */
class SyncApimoAgencies implements ShouldBeUnique, ShouldQueue
{
    use Queueable;

    /**
     * Never retried automatically: a retry spends quota again, and the next scheduled run picks up instead.
     */
    public int $tries = 1;

    public int $timeout = 120;

    public function handle(ApimoClient $client, AgencyImporter $importer): void
    {
        $agencyIds = collect($client->agencies())
            ->map(fn (array $payload): string => $importer->import($payload)->apimo_id);

        Agency::query()
            ->whereNotIn('apimo_id', $agencyIds)
            ->update(['active' => false]);
    }
}
