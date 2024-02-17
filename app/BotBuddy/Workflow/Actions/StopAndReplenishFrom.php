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
        sleep(3);

        $group = $model->user->account_groups()
            ->where('id', $data['account_group_id'])
            ->firstOrFail();

        $replenishAccount = $group->accounts()->whereNot('id', $model->id)->inRandomOrder()->first();

        if ($replenishAccount->account_group_id != $model->account_group_id) {
            $replenishAccount->account_group_id = $model->account_group_id;
            $replenishAccount->script_id = $model->script_id;
            $replenishAccount->script_params = $model->script_params;

            if (!$replenishAccount->proxy) {
                switch ($data['type']) {
                    case 'existing':
                        break;
                    case 'random':
                        $query = match ($model::class) {
                            Account::class => $model->account_group()->proxies(),
                            AccountGroup::class => $model->proxies(),
                        };
                        if (!$query) {
                            throw new \Exception('Invalid model type received in ChangeProxy action');
                        }
                        $newProxy = $query->where('id', '!=', $model->proxy_id)
                            ->inRandomOrder()
                            ->first();
                        if ($newProxy) {
                            $replenishAccount->proxy_id = $newProxy->id;
                        }
                        break;
                    case 'random_unused':
                        $query = match ($model::class) {
                            Account::class => $model->account_group()->proxies(),
                            AccountGroup::class => $model->proxies(),
                        };
                        if (!$query) {
                            throw new \Exception('Invalid model type received in ChangeProxy action');
                        }
                        $query = $query->proxies()->whereDoesntHave('accounts', function ($subQuery) use ($replenishAccount) {
                            $subQuery->where('id', '!=', $replenishAccount->id);
                        });
                        $newProxy = $query->inRandomOrder()->first();
                        if ($newProxy) {
                            $replenishAccount->proxy_id = $newProxy->id;
                        }
                        break;
                }
            }
        }

        $replenishAccount->save();

        $this->socket->dispatch(new StartBotCommand($replenishAccount));
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
            ]
        ];
    }
}
