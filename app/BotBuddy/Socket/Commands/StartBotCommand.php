<?php

namespace App\BotBuddy\Socket\Commands;

use App\Models\Account;

class StartBotCommand extends Command
{
    public string $header = 'startBot';

    public function __construct(public Account $account) {}

    /**
     * @return array<string, int|string>
     */
    public function dispatchUsing(): array
    {
        return [
            'serverId' => $this->account->agent->uuid ?? $this->account->account_group->agent->uuid,
            'internalId' => $this->account->id,
            'jarLocation' => $this->account->agent->dreambot_client_path ?? '',
            'scriptsLocation' => $this->account->agent->dreambot_scripts_path ?? '',
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
            'accountTotp' => $this->account->password_2fa ?? '',
            'fps' => $this->account->fps,
            'world' => $this->account->world,
            'javaXmx' => $this->agent->dreambot_max_heap ?? '512M',
            'javaXms' => $this->agent->dreambot_min_heap ?? '256M',
        ];
    }
}
