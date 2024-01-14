<?php

namespace App\BotBuddy\Socket\Commands;

use App\Models\Account;

class StartBotCommand
{
    public string $header = 'startBot';

    public function __construct(public Account $account) {}

    public function dispatchUsing(): array
    {
        return [
            'serverId' => $this->account->agent->uuid,
            'internalId' => $this->account->id,
            'jarLocation' => $this->account->user->dreambot_client,
            'scriptName' => $this->account->script->script ?? $this->account->account_group->script->script,
            'scriptParams' => $this->account->script_params ?? $this->account->account_group->script_params ?? "",
            'clientName' => $this->account->user->dreambot_username,
            'clientPassword' => $this->account->user->dreambot_password,
            'accountUsername' => $this->account->email,
            'accountPassword' => $this->account->password,
            'proxyHost' => $this->account->proxy?->host ?? '',
            'proxyPort' => $this->account->proxy?->port ?? 0,
            'proxyUsername' => $this->account->proxy?->username ?? '',
            'proxyPassword' => $this->account->proxy?->password ?? '',
        ];
    }
}
