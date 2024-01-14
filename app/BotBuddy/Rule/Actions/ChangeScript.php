<?php

namespace App\BotBuddy\Rule\Actions;

use App\BotBuddy\Socket\Commands\StartBotCommand;
use App\BotBuddy\Socket\Commands\StopBotCommand;
use App\BotBuddy\Socket\SocketService;
use App\Models\Account;
use App\Models\Rule;
use Illuminate\Database\Eloquent\Model;

class ChangeScript extends Action
{
    public function __construct(Rule $rule, public SocketService $socket)
    {
        parent::__construct($rule);
    }

    /** @var Account $model */
    public function run(Model $model, array $data): void
    {
        $this->socket->dispatch(new StopBotCommand($model));

        $model->script_id = $data['script_id'];
        $model->save();

        $this->socket->dispatch(new StartBotCommand($model));
    }
}
