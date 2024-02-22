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

class ChangeProxy extends Action
{
    public function __construct(Workflow $workflow, public SocketService $socket)
    {
        parent::__construct($workflow);
    }

    /** @var Account $model */
    public function run(Model $model, array $data): void
    {
        $proxyGroup = $model->user->proxy_groups()->find($data['proxy_group_id']);
        if (!$proxyGroup) {
            $model->user->notifications()->create([
                'message' => "{$model->name} could not be restarted, could not find proxy group ID: {$data['proxy_group_id']} (was it deleted?)",
                'type' => 'error'
            ]);
            return;
        }

        $query = $proxyGroup->proxies();

        switch($data['type']) {
            case 'random':
                break;
            case 'random_unused':
                $query->whereDoesntHave('accounts')->where('id', '!=', $model->proxy_id);
                break;
        }

        $proxy = $query->inRandomOrder()->first();

        if (!$proxy) {
            $model->user->notifications()->create([
                'message' => "{$model->name} could not be restarted, no available proxy could be found in group: {$proxyGroup->name}",
                'type' => 'error'
            ]);
            return;
        }

        $model->proxy_id = $proxy->id;
        $model->save();
    }

    public static function rules(): array
    {
        return [
            'type' => [
                'required',
                'string',
                'in:random,random_unused'
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
