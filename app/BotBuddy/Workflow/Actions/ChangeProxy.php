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
            captureException(new \Exception("Cannot find proxy group: {$data['proxy_group_id']}"));
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
            // todo: log when proxy is not available
            captureException(new \Exception("No proxy could be found for group: {$proxyGroup->id}"));
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
