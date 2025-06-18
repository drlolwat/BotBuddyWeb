<?php

namespace App\Jobs;

use App\BotBuddy\Socket\Commands\StartBotCommand;
use App\BotBuddy\Socket\SocketService;
use App\BotBuddy\Status;
use App\Models\Account;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class StartQueuedAccounts implements ShouldQueue
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
        $accounts = Account::query()
            ->with(['account_group.agent', 'user'])
            ->where('status', Status::QUEUED->value)
            ->where('start_queued_at', '<', now())
            ->get();

        foreach ($accounts as $account) {
            // todo: make common function to check if account can be started
            $agent = $account->account_group->agent ?? null;
            if (
                !$agent ||
                !$account->account_group->script ||
                $agent->client_type != 'DreamBot' ||
                !$agent->dreambot_client_path ||
                !$agent->dreambot_scripts_path ||
                !$account->user->dreambot_username ||
                !$account->user->dreambot_password
            ) {
                $account->status = Status::STOPPED->value;
                $account->start_queued_at = null;
                $account->save();
                continue;
            }

            $socket->dispatch(new StartBotCommand($account));
            $account->status = Status::STARTING->value;
            $account->start_queued_at = null;
            $account->last_started_at = now();
            $account->save();
        }
    }
}
