<?php

declare(strict_types=1);

use App\Jobs\SyncApimoAgencies;
use App\Jobs\SyncApimoCatalogs;
use App\Jobs\SyncApimoProperties;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
 * Apimo sync: about 30 requests a day out of the daily quota (1,000 by default, reset every night).
 */
Schedule::when(fn (): bool => filled(config('services.apimo.provider_id')) && filled(config('services.apimo.token')))
    ->timezone('Europe/Paris')
    ->group(function (): void {
        Schedule::job(new SyncApimoProperties)->hourly();
        Schedule::job(new SyncApimoAgencies)->dailyAt('04:30');
        Schedule::job(new SyncApimoProperties(full: true))->dailyAt('05:20');
        Schedule::job(new SyncApimoCatalogs)->weeklyOn(0, '04:00');
    });
