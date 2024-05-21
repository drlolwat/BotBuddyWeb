<?php

namespace App\BotBuddy\Socket\Commands;

use App\Models\Account;

class StopBotCommand extends Command
{
    public string $header = 'stopBot';

    public function __construct(public Account $account) {}

    /**
     * @return array<string, int|string>
     */
    public function dispatchUsing(): array
    {
        return [
            'serverId' => $this->account->account_group->agent->uuid,
            'internalId' => $this->account->id,
        ];
    }
}
