<?php

namespace App\BotBuddy\Socket\Commands;

use App\Models\Account;

class StopBotCommand
{
    public string $header = 'stopBot';

    public function __construct(public Account $account) {}

    public function dispatchUsing(): array
    {
        return [
            'serverId' => $this->account->agent->uuid,
            'internalId' => $this->account->id,
        ];
    }
}
