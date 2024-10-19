<?php

namespace App\BotBuddy\Socket\Commands;

use App\Models\Account;
use Exception;

class StopBotCommand extends Command
{
    public string $header = 'stopBot';

    public function __construct(public Account $account) {}

    /**
     * @return array<string, int|string>
     */
    public function dispatchUsing(): array
    {
        if ($this->account->account_group === null || $this->account->account_group->agent === null) {
            throw new Exception('Cannot start bot with no account group');
        }

        if ($this->account->account_group->agent === null) {
            throw new Exception('Cannot start bot with no agent');
        }

        return [
            'serverId' => $this->account->account_group->agent->uuid,
            'internalId' => $this->account->id,
        ];
    }
}
