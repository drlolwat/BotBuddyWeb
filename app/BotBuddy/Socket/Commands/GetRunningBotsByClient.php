<?php

namespace App\BotBuddy\Socket\Commands;

use App\Models\User;

class GetRunningBotsByClient
{
    public string $header = 'getRunningBotsByClient';

    public function __construct(public User $user) {}

    public function dispatchUsing(): array
    {
        return [
            'customerId' => $this->user->id,
        ];
    }
}
