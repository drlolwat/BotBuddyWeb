<?php

namespace App\BotBuddy\Workflow\Actions;

use App\BotBuddy\Socket\Commands\StartBotCommand;
use App\BotBuddy\Socket\Commands\StopBotCommand;
use App\BotBuddy\Socket\SocketService;
use App\Models\Account;
use App\Models\Workflow;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class ChangeScript extends Action
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
        $model->user->notifications()->create([
            'message' => "The \"Change script\" workflow action has been removed. Please use the \"Change account group\" workflow action instead and configure the script via the account group.",
            'type' => 'error'
        ]);
    }

    public static function rules(): array
    {
        return [
            'script_id' => [
                'required',
                'integer',
                Rule::exists('user_scripts', 'id')
                    ->where(function ($query) {
                        $query->where('user_id', auth()->id());
                    }),
            ],
            'script_params' => [
                'nullable',
                'string',
            ]
        ];
    }
}
