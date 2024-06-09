<?php

namespace App\Jobs;

use App\BotBuddy\Socket\Commands\StartBotCommand;
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

        foreach ($finishingSchedules as $schedule) {
            if (!$schedule->account_group) { continue; }
            foreach ($schedule->account_group->accounts as $account) {
                $stopped = $socket->dispatch(new StopBotCommand($account));
                if ($stopped == "true") {
                    $account->status = 'Stopping';
                    $account->save();
                }
            }
        }

        $offlineStatuses = ['Stopping', 'Stopped'];

        $startingSchedules = ScheduleEvent::query()
            ->with(['account_group' => function($query) use ($offlineStatuses) {
                $query->with(['accounts' => function($query) use ($offlineStatuses) {
                    $query->whereIn('status', $offlineStatuses);
                }]);
            }])
            ->where('start_at', $time)
            ->get();

        foreach ($startingSchedules as $schedule) {

            $errors = [];

            if (!$schedule->account_group) { continue; }

            foreach ($schedule->account_group->accounts as $account) {

                if (!$account->user->dreambot_username || !$account->user->dreambot_password) {
                    $errors[] = 'Please configure your DreamBot credentials to start accounts via schedule';
                    break;
                }

                $agent = $account->account_group->agent ?? null;

                if(!$agent) {
                    $errors[] = "Account $account->email is not assigned to an agent and could not be started via schedule";
                    continue;
                }

                $started = $socket->dispatch(new StartBotCommand(
                    $account,
                    $schedule->script->script,
                    $schedule->script_params,
                ));

                if ($started != "true") {
                    $errors[] = "Failed to start $account->email via schedule";
                    continue;
                }

                $account->status = 'Starting';
                $account->start_queued_at = null;
                $account->last_started_at = now();
                $account->save();
            }

            foreach ($errors as $error) {
                $schedule->account_group->user->notifications()->create([
                    'message' => $error,
                    'type' => 'error'
                ]);
            }
        }
    }
}
