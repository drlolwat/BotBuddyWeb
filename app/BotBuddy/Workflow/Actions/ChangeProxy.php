<?php

namespace App\BotBuddy\Workflow\Actions;

use App\BotBuddy\Socket\Commands\StartBotCommand;
use App\BotBuddy\Socket\Commands\StopBotCommand;
use App\BotBuddy\Socket\SocketService;
use App\Models\Account;
use App\Models\Workflow;
use Illuminate\Database\Eloquent\Model;

class ChangeProxy extends Action
{
    public function __construct(Workflow $workflow, public SocketService $socket)
    {
        parent::__construct($workflow);
    }

    /** @var Account $model */
    public function run(Model $model, array $data): void
    {
        $this->socket->dispatch(new StopBotCommand($model));

        $query = $model->account_group->proxies();

        switch($data['type']) {
            case 'random':
                break;
            case 'random_unused':
                $query->whereDoesntHave('accounts', function($subQuery) use ($model) {
                    $subQuery->where('id', '!=', $model->id);
                });
                break;
        }

        $proxy = $query->where('id', '!=', $model->proxy_id)
            ->inRandomOrder()
            ->first();

        if ($proxy) {
            $model->proxy_id = $proxy->id;
            $model->save();

            $this->socket->dispatch(new StartBotCommand($model));
        }

        // todo: log when proxy is not available
    }
}
