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

    /** @var Account $model */
    public function run(Model $model, array $data): void
    {
        $this->socket->dispatch(new StopBotCommand($model));

        $group = $model->user->account_groups()
            ->where('id', $data['account_group_id'])
            ->first();

        if (!$group) {
            captureException(new \Exception("Cannot find account group: {$data['account_group_id']}"));
            return;
        }

        $replenishAccount = $group->accounts()->whereNot('id', $model->id)
            ->where('status', 'Stopped')
            ->whereNull(['perm_banned_at', 'temp_banned_at'])
            ->inRandomOrder()
            ->first();

        if (!$replenishAccount) {
            captureException(new \Exception("Cannot find replenish account"));
            return;
        }

        if ($replenishAccount->account_group_id != $model->account_group_id) {
            $replenishAccount->account_group_id = $model->account_group_id;
            $replenishAccount->script_id = $model->script_id;
            $replenishAccount->script_params = $model->script_params;
            $replenishAccount->save();
        }

        switch ($data['type']) {
            case 'existing':
                $this->socket->dispatch(new StartBotCommand($replenishAccount));
                break;
            case 'random':
                $proxyGroup = $model->user->proxy_groups()->find($data['proxy_group_id']);
                if (!$proxyGroup) {
                    captureException(new \Exception("Cannot find proxy group: {$data['proxy_group_id']}"));
                    break;
                }
                $newProxy = $proxyGroup->proxies()
                    ->inRandomOrder()
                    ->first();
                if (!$newProxy) {
                    captureException(new \Exception("Cannot find a new proxy via account: {$model->id}"));
                    break;
                }
                $replenishAccount->proxy_id = $newProxy->id;
                $replenishAccount->save();
                $this->socket->dispatch(new StartBotCommand($replenishAccount));
                break;
            case 'random_unused':
                $proxyGroup = $model->user->proxy_groups()->find($data['proxy_group_id']);
                if (!$proxyGroup) {
                    captureException(new \Exception("Cannot find proxy group: {$data['proxy_group_id']}"));
                    break;
                }
                $newProxy = $proxyGroup->proxy_group?->proxies()
                    ->whereDoesntHave('accounts')
                    ->inRandomOrder()
                    ->first();
                if (!$newProxy) {
                    captureException(new \Exception("Cannot find a new proxy via account: {$model->id}"));
                    break;
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
                'in:existing,random,random_unused'
            ],
            'proxy_group_id' => [
                'required',
                'integer',
                Rule::exists('proxy_groups', 'id')
                    ->where(function ($query) {
                        $query->where('user_id', auth()->id());
                    }),
            ],
        ];
    }
}
