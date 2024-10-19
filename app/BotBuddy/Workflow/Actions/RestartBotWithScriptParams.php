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

    /**
     * @param Account $model
     * @param array<string, mixed> $data
     */
    public function run(Model $model, array $data): void
    {
        /** @var string|null $scriptParams */
        $scriptParams = $data['script_params'] ?? null;
        $this->socket->dispatch(new StopBotCommand($model));
        $this->socket->dispatch(new StartBotCommand($model, script_params: $scriptParams));
        $model->last_started_at = now();
        $model->save();
    }

    public static function rules(): array
    {
        return [
            'script_params' => 'string|nullable',
        ];
    }
}
