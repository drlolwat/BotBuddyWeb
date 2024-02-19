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
        if ($model::class === Account::class) {
            $this->socket->dispatch(new StopBotCommand($model));

            $group = $model->user->account_group()
                ->where('id', $data['account_group_id'])
                ->firstOrFail();
            $replenishAccount = $group->accounts()->whereNot('id', $model->id)->inRandomOrder()->first();

            if ($replenishAccount->account_group_id != $model->account_group_id) {
                $replenishAccount->account_group_id = $model->account_group_id;
                $replenishAccount->script_id = $model->script_id;
                $replenishAccount->script_params = $model->script_params;
                $replenishAccount->save();

                if (!$replenishAccount->proxy) {
                    switch ($data['type']) {
                        case 'existing':
                            $this->socket->dispatch(new StartBotCommand($replenishAccount));
                            break;
                        case 'random':
                            $newProxy = $model->proxy_group?->proxies()->where('id', '!=', $model->proxy_id)
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
                            $newProxy = $model->proxy_group?->proxies()
                                ->whereDoesntHave('accounts', function ($subQuery) use ($replenishAccount) {
                                    $subQuery->where('id', '!=', $replenishAccount->id);
                                })->inRandomOrder()
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
            }
            return;
        }

        if ($model::class === AccountGroup::class) {
            foreach ($model->accounts as $model) {
                $this->socket->dispatch(new StopBotCommand($model));

                $group = $model->user->account_group()
                    ->where('id', $data['account_group_id'])
                    ->firstOrFail();
                $replenishAccount = $group->accounts()->whereNot('id', $model->id)->inRandomOrder()->first();

                if ($replenishAccount->account_group_id != $model->account_group_id) {
                    $replenishAccount->account_group_id = $model->account_group_id;
                    $replenishAccount->script_id = $model->script_id;
                    $replenishAccount->script_params = $model->script_params;
                    $replenishAccount->save();

                    if (!$replenishAccount->proxy) {
                        switch ($data['type']) {
                            case 'existing':
                                $this->socket->dispatch(new StartBotCommand($replenishAccount));
                                break;
                            case 'random':
                                $newProxy = $model->proxy_group?->proxies()->where('id', '!=', $model->proxy_id)
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
                                $newProxy = $model->proxy_group?->proxies()
                                    ->whereDoesntHave('accounts', function ($subQuery) use ($replenishAccount) {
                                        $subQuery->where('id', '!=', $replenishAccount->id);
                                    })->inRandomOrder()
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
                }
            }
            return;
        }

        captureException(new \Exception("Invalid model received in action: {$model::class}: {$model->id}"));
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
