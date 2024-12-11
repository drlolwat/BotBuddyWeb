<?php

namespace App\BotBuddy\Workflow\Actions;

use App\BotBuddy\Socket\Commands\StartBotCommand;
use App\BotBuddy\Socket\Commands\StopBotCommand;
use App\BotBuddy\Socket\SocketService;
use App\BotBuddy\Status;
use App\Models\Account;
use App\Models\AccountGroup;
use App\Models\Workflow;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use function Sentry\captureException;

class StopAndReplenishFrom extends Action
{
    public function __construct(Workflow $workflow, public SocketService $socket)
    {
        parent::__construct($workflow);
    }

    /**
     * @param Account $model
     * @param array<string, mixed> $data
     */
    public function run(Model $model, array $data): void
    {
        $this->socket->dispatch(new StopBotCommand($model));

        $group = $model->user->account_groups()
            ->where('id', $data['account_group_id'])
            ->first();

        if (!$group) {
            /** @var int $accountGroupId */
            $accountGroupId = $data['account_group_id'];
            $model->user->notifications()->create([
                'message' => "Could not change to account group ID {$accountGroupId} to replenish from (was it deleted?)",
                'type' => 'error'
            ]);
            return;
        }

        $statuses = [Status::STOPPED->value];

        // todo: document this feature on site OR allow them to choose
        // if they want it to include banned accounts
        if (str_contains(strtolower($group->name), 'ban')) {
            $statuses[] = Status::BANNED->value;
        }

        $replenishAccount = $group->accounts()->whereNot('id', $model->id)
            ->whereIn('status', $statuses)
            ->whereNull(['perm_banned_at', 'temp_banned_at'])
            ->inRandomOrder()
            ->first();

        if (!$replenishAccount) {
            $model->user->notifications()->create([
                'message' => "Could not find a replenishment account in group {$group->name} to start",
                'type' => 'error'
            ]);
            return;
        }

        $replenishAccount->refresh();

        if (!$replenishAccount->account_group) {
            $model->user->notifications()->create([
                'message' => "{$group->name} needs to be assigned to an account group to start",
                'type' => 'error'
            ]);
            return;
        }

        if (!$replenishAccount->account_group->agent) {
            $model->user->notifications()->create([
                'message' => "{$group->name} needs to be assigned to an agent to start",
                'type' => 'error'
            ]);
            return;
        }

        if ($replenishAccount->account_group_id != $model->account_group_id) {
            $replenishAccount->account_group_id = $model->account_group_id;
            $replenishAccount->save();
        }

        $replenishAccount->refresh();

        switch ($data['type']) {
            case 'existing':
                $this->socket->dispatch(new StartBotCommand($replenishAccount));
                $replenishAccount->last_started_at = now();
                $replenishAccount->save();
                break;
            case 'triggered':
                $replenishAccount->proxy_id = $model->proxy_id;
                $replenishAccount->save();
                $this->socket->dispatch(new StartBotCommand($replenishAccount));
                $replenishAccount->last_started_at = now();
                $replenishAccount->save();
                break;
            case 'random':
                /** @var int $proxyGroupId */
                $proxyGroupId = $data['proxy_group_id'];
                $proxyGroup = $model->user->proxy_groups()->find($proxyGroupId);
                if (!$proxyGroup) {
                    $model->user->notifications()->create([
                        'message' => "Replenish account could not be started, could not find proxy group ID: {$proxyGroupId} (was it deleted?)",
                        'type' => 'error'
                    ]);
                    return;
                }
                $newProxy = $proxyGroup->proxies()
                    ->inRandomOrder()
                    ->first();
                if (!$newProxy) {
                    $model->user->notifications()->create([
                        'message' => "Replenish account could not be started, cannot find a new proxy to use from proxy group {$proxyGroup->name}",
                        'type' => 'error'
                    ]);
                    return;
                }
                $replenishAccount->proxy_id = $newProxy->id;
                $replenishAccount->save();
                $this->socket->dispatch(new StartBotCommand($replenishAccount));
                $replenishAccount->last_started_at = now();
                $replenishAccount->save();
                break;
            case 'random_unused':
                /** @var int $proxyGroupId */
                $proxyGroupId = $data['proxy_group_id'];
                $proxyGroup = $model->user->proxy_groups()->find($proxyGroupId);
                if (!$proxyGroup) {
                    $model->user->notifications()->create([
                        'message' => "Replenish account could not be started, could not find proxy group ID: {$proxyGroupId} (was it deleted?)",
                        'type' => 'error'
                    ]);
                    return;
                }
                $newProxy = $proxyGroup->proxies()
                    ->whereDoesntHave('accounts')
                    ->inRandomOrder()
                    ->first();
                if (!$newProxy) {
                    $model->user->notifications()->create([
                        'message' => "Replenish account could not be started, cannot find a new proxy to use from proxy group {$proxyGroup->name}",
                        'type' => 'error'
                    ]);
                    return;
                }
                $replenishAccount->proxy_id = $newProxy->id;
                $replenishAccount->save();
                $this->socket->dispatch(new StartBotCommand($replenishAccount));
                $replenishAccount->last_started_at = now();
                $replenishAccount->save();
                break;
        }
    }

    public static function rules(): array
    {
        return [
            'account_group_id' => [
                'required',
                'integer',
                Rule::exists('account_groups', 'id')
                    ->where(function ($query) {
                        $query->where('user_id', auth()->id());
                    }),
            ],
            'type' => [
                'required',
                'string',
                'in:existing,triggered,random,random_unused'
            ],
            'proxy_group_id' => [
                'nullable',
                'integer',
                Rule::exists('proxy_groups', 'id')
                    ->where(function ($query) {
                        $query->where('user_id', auth()->id());
                    }),
            ],
        ];
    }
}
