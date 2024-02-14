<?php

namespace App\BotBuddy\Workflow\Actions;

use App\BotBuddy\Socket\Commands\StartBotCommand;
use App\BotBuddy\Socket\Commands\StopBotCommand;
use App\BotBuddy\Socket\SocketService;
use App\Models\Account;
use App\Models\Workflow;
use Illuminate\Database\Eloquent\Model;

class RestartBotWithScriptParams extends Action
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

        $model->script_params = $data['script_params'] ?? '';
        $model->save();

        $this->socket->dispatch(new StartBotCommand($model));
    }

    public static function rules(): array
    {
        return [
            'script_params' => 'string|nullable',
        ];
    }
}
