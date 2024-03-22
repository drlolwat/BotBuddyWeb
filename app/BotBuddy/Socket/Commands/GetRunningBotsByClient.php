<?php

namespace App\BotBuddy\Socket\Commands;

use App\Models\User;

class GetRunningBotsByClient extends Command
{
    public string $header = 'getRunningBotsByClient';

    public function __construct(public User $user) {}

    /**
     * @return array<string, int>
     */
    public function dispatchUsing(): array
    {
        return [
            'customerId' => $this->user->id,
        ];
    }
}
