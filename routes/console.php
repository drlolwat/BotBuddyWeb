<?php

use App\Jobs\CheckTempBannedAccounts;
use App\Jobs\HeartbeatAccounts;
use App\Jobs\StartQueuedAccounts;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::job(new HeartbeatAccounts())->everyMinute();
Schedule::job(new StartQueuedAccounts())->everyMinute();
Schedule::job(new CheckTempBannedAccounts())->everySixHours();
