<?php

namespace App\BotBuddy\Workflow\Actions;

use App\BotBuddy\Socket\Commands\StartBotCommand;
use App\BotBuddy\Socket\Commands\StopBotCommand;
use App\BotBuddy\Socket\SocketService;
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
            $model->user->notifications()->create([
                'message' => "Could not change to account group ID {$data['account_group_id']} to replenish from (was it deleted?)",
                'type' => 'error'
            ]);
            return;
        }

        $replenishAccount = $group->accounts()->whereNot('id', $model->id)
            ->where('status', 'Stopped')
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

        if ($replenishAccount->account_group_id != $model->account_group_id) {
            $replenishAccount->account_group_id = $model->account_group_id;
            $replenishAccount->save();
        }

        switch ($data['type']) {
            case 'existing':
                $this->socket->dispatch(new StartBotCommand($replenishAccount));
                break;
            case 'triggered':
                $replenishAccount->proxy_id = $model->proxy_id;
                $replenishAccount->save();
                $this->socket->dispatch(new StartBotCommand($replenishAccount));
                break;
            case 'random':
                $proxyGroup = $model->user->proxy_groups()->find($data['proxy_group_id']);
                if (!$proxyGroup) {
                    $model->user->notifications()->create([
                        'message' => "Replenish account could not be started, could not find proxy group ID: {$data['proxy_group_id']} (was it deleted?)",
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
                break;
            case 'random_unused':
                $proxyGroup = $model->user->proxy_groups()->find($data['proxy_group_id']);
                if (!$proxyGroup) {
                    $model->user->notifications()->create([
                        'message' => "Replenish account could not be started, could not find proxy group ID: {$data['proxy_group_id']} (was it deleted?)",
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
