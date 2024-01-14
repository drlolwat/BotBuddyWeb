<?php

namespace App\BotBuddy\Rule\Actions;

use App\BotBuddy\Socket\Commands\StartBotCommand;
use App\BotBuddy\Socket\Commands\StopBotCommand;
use App\BotBuddy\Socket\SocketService;
use App\Models\Rule;

class ChangeAccountGroup extends Action
{
    public function __construct(Rule $rule, public SocketService $socket)
    {
        parent::__construct($rule);
    }

    public function run(array $data): void
    {
        $this->socket->dispatch(new StopBotCommand($this->rule->model));

        $this->rule->model->account_group_id = $data['account_group_id'];
        $this->rule->model->save();

        $this->socket->dispatch(new StartBotCommand($this->rule->model));
    }
}
