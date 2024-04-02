<?php

use App\Jobs\CheckTempBannedAccounts;
use App\Jobs\HeartbeatAccounts;
use App\Jobs\PerformScheduleActions;
use App\Jobs\StartQueuedAccounts;
use Illuminate\Support\Facades\Schedule;

Schedule::job(new HeartbeatAccounts())->everyMinute();
Schedule::job(new StartQueuedAccounts())->everyMinute();
Schedule::job(new CheckTempBannedAccounts())->everySixHours();
Schedule::job(new PerformScheduleActions())->everyThirtyMinutes();
