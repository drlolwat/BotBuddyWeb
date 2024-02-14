<?php

namespace App\BotBuddy\Workflow\Actions;

use App\BotBuddy\Socket\Commands\StartBotCommand;
use App\BotBuddy\Socket\Commands\StopBotCommand;
use App\BotBuddy\Socket\SocketService;
use App\Models\Account;
use App\Models\AccountGroup;
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
        $query = match ($model::class) {
            Account::class => $model->proxies(),
            AccountGroup::class => $model->account_group->proxies()
        };

        if (!$query) {
            throw new \Exception('Invalid model type received in ChangeProxy action');
        }

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
        }

        // todo: log when proxy is not available
    }

    public static function rules(): array
    {
        return [
            'type' => [
                'required',
                'string',
                'in:random,random_unused'
            ]
        ];
    }
}
