<?php

namespace App\BotBuddy\Socket\Commands;

use App\Models\Account;
use Exception;

class StartBotCommand extends Command
{
    public string $header = 'startBot';

    public function __construct(public Account $account, public ?string $script = null, public ?string $script_params = null) {}

    /**
     * @return array<string, bool|int|string|null>
     */
    public function dispatchUsing(): array
    {
        if ($this->account->account_group === null) {
            throw new Exception('Cannot start bot with no account group');
        }

        if ($this->account->account_group->agent === null) {
            throw new Exception('Cannot start bot with no agent');
        }

        return [
            'serverId' => $this->account->account_group->agent->uuid,
            'internalId' => $this->account->id,
            'jarLocation' => $this->account->account_group->agent->dreambot_client_path ?? '',
            'scriptsLocation' => $this->account->account_group->agent->dreambot_scripts_path ?? '',
            'scriptName' => $this->account->account_group->script->script,
            'scriptParams' => $this->script_params ?? $this->account->account_group->script_params ?? "",
            'clientName' => $this->account->user->dreambot_username,
            'clientPassword' => $this->account->user->dreambot_password,
            'accountUsername' => $this->account->email,
            'accountPassword' => $this->account->password,
            'proxyHost' => $this->account->proxy?->host ?? '',
            'proxyPort' => $this->account->proxy?->port ?? 0,
            'proxyUsername' => $this->account->proxy?->username ?? '',
            'proxyPassword' => $this->account->proxy?->password ?? '',
            'accountTotp' => $this->account->password_2fa ?? '',
            'fps' => $this->account->account_group->fps,
            'world' => $this->account->account_group->world,
            'disableBrowserProxy' => (bool) $this->account->account_group->disable_browser_proxy,
            'javaXmx' => $this->account->account_group->agent->dreambot_max_heap ?? '512M',
            'javaXms' => $this->account->account_group->agent->dreambot_min_heap ?? '256M',
            'render' => $this->account->account_group->db_render,
            'debug' => (bool) $this->account->account_group->db_debug,
            'disableAnimations' => (bool) $this->account->account_group->db_disable_animations,
            'disableModels' => (bool) $this->account->account_group->db_disable_models,
            'beta' => (bool) $this->account->account_group->db_beta,
            'disableSounds' => (bool) $this->account->account_group->db_disable_sounds,
            'dismissRandomEvents' => (bool) $this->account->account_group->db_dismiss_random_events,
            'lowDetail' => (bool) $this->account->account_group->db_low_detail,
            'menuManipulation' => (bool) $this->account->account_group->db_menu_manipulation,
            'noClickWalk' => (bool) $this->account->account_group->db_no_click_walk,
            'minimized' => (bool) $this->account->account_group->db_minimized,
            'accountPin' => $this->account->bank_pin ?? '',
        ];
    }
}
