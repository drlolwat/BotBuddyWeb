<?php

namespace App\BotBuddy\Workflow\Actions;

use App\BotBuddy\Socket\SocketService;
use App\Models\Account;
use App\Models\Workflow;
use Illuminate\Database\Eloquent\Model;

class RemoveProxy extends Action
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
        $model->proxy_id = null;
        $model->save();
    }
}
