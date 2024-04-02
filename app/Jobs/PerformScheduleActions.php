<?php

namespace App\Jobs;

use App\BotBuddy\Socket\Commands\StopBotCommand;
use App\BotBuddy\Socket\SocketService;
use App\Models\ScheduleEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PerformScheduleActions implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(SocketService $socket): void
    {
        $onlineStatuses = [
            'Running', 'Starting', 'Completed',
            'NoScript', 'ProxyBlocked', 'Banned',
        ];

        $time = now()->format('H:i') . ":00";

        $finishingSchedules = ScheduleEvent::query()
            ->with(['account_group' => function($query) use ($onlineStatuses) {
                $query->with(['accounts' => function($query) use ($onlineStatuses) {
                    $query->whereIn('status', $onlineStatuses);
                }]);
            }])
            ->where('finish_at', $time)
            ->get();

        foreach($finishingSchedules->account_group->accounts as $account) {
            $stopped = $socket->dispatch(new StopBotCommand($account));
            if ($stopped == "true") {
                $account->status = 'Stopping';
                $account->save();
            }
        }

        $offlineStatuses = ['Stopped', 'Queued'];

        $startingSchedules = ScheduleEvent::query()
            ->with(['account_group' => function($query) use ($offlineStatuses) {
                $query->with(['accounts' => function($query) use ($offlineStatuses) {
                    $query->whereIn('status', $offlineStatuses);
                }]);
            }])
            ->where('start_at', $time)
            ->get();

        // todo: perform configurable starting actions
    }
}
