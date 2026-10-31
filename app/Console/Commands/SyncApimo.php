<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Jobs\SyncApimoAgencies;
use App\Jobs\SyncApimoCatalogs;
use App\Jobs\SyncApimoProperties;
use App\Services\Apimo\ApimoClient;
use App\Services\Apimo\ApimoException;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('apimo:sync
    {--full : Fetch every property and delete the ones Apimo no longer returns}
    {--catalogs : Also refresh the catalogs (about 40 requests)}')]
#[Description('Synchronize agencies and properties from Apimo now (picture downloads stay queued)')]
class SyncApimo extends Command
{
    public function handle(ApimoClient $client): int
    {
        $before = $client->requestsSentToday();

        try {
            $this->components->task('Agencies', fn () => SyncApimoAgencies::dispatchSync());

            if ($this->option('catalogs')) {
                $this->components->task('Catalogs', fn () => SyncApimoCatalogs::dispatchSync());
            }

            $this->components->task(
                $this->option('full') === true ? 'Properties (full)' : 'Properties (changes only)',
                fn () => SyncApimoProperties::dispatchSync($this->option('full') === true),
            );
        } catch (ApimoException $exception) {
            $this->components->error($exception->getMessage());

            return self::FAILURE;
        } finally {
            $this->components->info(sprintf(
                'Apimo requests used: %d this run, %d today.',
                $client->requestsSentToday() - $before,
                $client->requestsSentToday(),
            ));
        }

        return self::SUCCESS;
    }
}
