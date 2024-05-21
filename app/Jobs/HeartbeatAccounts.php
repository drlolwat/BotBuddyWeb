<?php

namespace App\Jobs;

use App\BotBuddy\Socket\Commands\GetRunningBotsByClient;
use App\BotBuddy\Socket\SocketService;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class HeartbeatAccounts implements ShouldQueue
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
        $users = User::where('subscription_expires_at', '>', now())
            ->whereHas('accounts', function ($query) {
                $query->where('status', 'Running');
            })
            ->get();

        foreach ($users as $user) {
            $socket->dispatch(new GetRunningBotsByClient($user));
        }
    }
}
