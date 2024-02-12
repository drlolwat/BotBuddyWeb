<?php

namespace App\Jobs;

use App\BotBuddy\Socket\Commands\StartBotCommand;
use App\BotBuddy\Socket\SocketService;
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
        $accounts = Account::where('start_queued_at', '<', now())->get();

        foreach ($accounts as $account) {
            $socket->dispatch(new StartBotCommand($account));
            $account->status = 'Starting';
            $account->start_queued_at = null;
            $account->save();
        }
    }
}
