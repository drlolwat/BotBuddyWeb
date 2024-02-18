<?php

namespace App\BotBuddy\Workflow;

use App\BotBuddy\Workflow\Actions\ChangeAccountGroup;
use App\BotBuddy\Workflow\Actions\ChangeProxy;
use App\BotBuddy\Workflow\Actions\ChangeScript;
use App\BotBuddy\Workflow\Actions\RestartBot;
use App\BotBuddy\Workflow\Actions\StopAndReplenishFrom;
use App\BotBuddy\Workflow\Actions\StopBot;
use App\BotBuddy\Workflow\Events\PermBanned;
use App\BotBuddy\Workflow\Events\ProxyBlocked;
use App\BotBuddy\Workflow\Events\ScriptComplete;
use App\BotBuddy\Workflow\Events\StatGoal;
use App\BotBuddy\Workflow\Events\TempBanned;
use App\Models\Account;
use App\Models\AccountGroup;
use App\Models\Workflow;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class WorkflowService
{
    // the model types allowed to be used in workflows
    public array $modelTypes = [
        'account' => Account::class,
        'account_group' => AccountGroup::class,
    ];

    public array $events = [
        'script_complete' => ScriptComplete::class,
        'proxy_blocked' => ProxyBlocked::class,
        'temp_banned' => TempBanned::class,
        'perm_banned' => PermBanned::class,
        'stat_goal' => StatGoal::class,
    ];

    public array $actions = [
        'change_script' => ChangeScript::class,
        'stop_bot' => StopBot::class,
        'restart_bot' => RestartBot::class,
        'restart_bot_with_script_params' => RestartBot::class,
        'change_account_group' => ChangeAccountGroup::class,
        'stop_and_replenish_with' => StopAndReplenishFrom::class,
        'change_proxy' => ChangeProxy::class,
    ];

    public function handle(Model $model, Workflow $workflow): void
    {
        foreach($workflow->actions as $action) {
            $runner = app()->makeWith($this->actions[$action->name], ['workflow' => $workflow]);
            $runner->run($model, $action->data ?? []);
        }
    }

    public function getWorkflows($modelType, $modelId, $event, $eventData, $operator = '='): Collection
    {
        $query = Workflow::query()
            ->with('model', 'actions')
            ->where('model_type', $modelType)
            ->where('model_id', $modelId)
            ->where('event', $event);

        if ($eventData) {
            foreach ($eventData as $key => $value) {
                switch($operator) {
                    case '=':
                        $query = $query->whereRaw("data->>'$." . $key . "' = ?", [$value]);
                        break;
                    case '>':
                        $query = $query->whereRaw("data->>'$." . $key . "' > ?", [$value]);
                        break;
                    case '>=':
                        $query = $query->whereRaw("data->>'$." . $key . "' >= ?", [$value]);
                        break;
                    case '<':
                        $query = $query->whereRaw("data->>'$." . $key . "' < ?", [$value]);
                        break;
                    case '<=':
                        $query = $query->whereRaw("data->>'$." . $key . "' <= ?", [$value]);
                        break;
                }
            }
        }

        return $query->get();
    }

    public function getWorkflowsNullableAllowed($modelType, $modelId, $event, $eventData, $operator = '='): Collection
    {
        $query = Workflow::query()
            ->with('model', 'actions')
            ->where('model_type', $modelType)
            ->where('model_id', $modelId)
            ->where('event', $event);

        if ($eventData) {
            foreach ($eventData as $key => $value) {
                switch($operator) {
                    case '=':
                        $query = $query->whereRaw("(data->>'$.$key' = ? OR data->>'$.$key' IS NULL)", [$value]);
                        break;
                    case '>':
                        $query = $query->whereRaw("(data->>'$.$key' > ? OR data->>'$.$key' IS NULL)", [$value]);
                        break;
                    case '>=':
                        $query = $query->whereRaw("(data->>'$.$key' >= ? OR data->>'$.$key' IS NULL)", [$value]);
                        break;
                    case '<':
                        $query = $query->whereRaw("(data->>'$.$key' < ? OR data->>'$.$key' IS NULL)", [$value]);
                        break;
                    case '<=':
                        $query = $query->whereRaw("(data->>'$.$key' <= ? OR data->>'$.$key' IS NULL)", [$value]);
                        break;
                }
            }
        }

        return $query->get();
    }
}
