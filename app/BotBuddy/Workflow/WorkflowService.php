<?php

namespace App\BotBuddy\Workflow;

use App\BotBuddy\Workflow\Actions\ChangeAccountGroup;
use App\BotBuddy\Workflow\Actions\ChangeProxy;
use App\BotBuddy\Workflow\Actions\ChangeScript;
use App\BotBuddy\Workflow\Actions\RemoveProxy;
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
    /**
     * The model types allowed to be used in workflows.
     * @var array<string, string> $modelTypes
     */
    public array $modelTypes = [
        //'account' => Account::class,
        'account_group' => AccountGroup::class,
    ];

    /**
     * @var array<string, string> $events
     */
    public array $events = [
        'script_complete' => ScriptComplete::class,
        'proxy_blocked' => ProxyBlocked::class,
        'temp_banned' => TempBanned::class,
        'perm_banned' => PermBanned::class,
        'stat_goal' => StatGoal::class,
    ];

    /**
     * @var array<string, string> $actions
     */
    public array $actions = [
        'change_script' => ChangeScript::class,
        'stop_bot' => StopBot::class,
        'restart_bot' => RestartBot::class,
        'restart_bot_with_script_params' => RestartBot::class,
        'change_account_group' => ChangeAccountGroup::class,
        'stop_and_replenish_with' => StopAndReplenishFrom::class,
        'change_proxy' => ChangeProxy::class,
        'remove_proxy' => RemoveProxy::class,
    ];

    public function handle(Model $model, Workflow $workflow): void
    {
        $actions = $workflow->actions;

        // stage 1: run any stop bot actions on the source model
        // it is ok for replenish accounts to start at this stage,
        // as the other actions of this workflow do not interact with them
        $stopActions = $actions->filter(fn($action) => in_array($action->name, ['stop_bot','stop_and_replenish_with']));
        foreach ($stopActions as $restartAction) {
            $runner = app()->makeWith($this->actions[$restartAction->name], ['workflow' => $workflow]);
            $runner->run($model, $restartAction->data ?? []);
        }

        // stage 2: run any change related actions on the source model
        // the bot will have been stopped at this point if requested
        // and will be updated before any restart actions can be run
        $changeActions = $actions->filter(fn($action) => in_array($action->name, ['change_script', 'change_account_group', 'change_proxy']));
        foreach ($changeActions as $restartAction) {
            $runner = app()->makeWith($this->actions[$restartAction->name], ['workflow' => $workflow]);
            $runner->run($model, $restartAction->data ?? []);
        }

        // stage 3: run any restart related actions on the source model.
        // the model will have been updated with a new script/account group/proxy by this point if requested
        $restartActions = $actions->filter(fn($action) => in_array($action->name, ['restart_bot','restart_bot_with_script_params']));
        foreach ($restartActions as $restartAction) {
            $runner = app()->makeWith($this->actions[$restartAction->name], ['workflow' => $workflow]);
            $runner->run($model, $restartAction->data ?? []);
        }

        // stage 4: remove any requested data from the source model. only the proxy can be removed at this time
        $removeProxyAction = $actions->filter(fn($action) => $action->name == 'remove_proxy')->first();
        if ($removeProxyAction) {
            $runner = app()->makeWith($this->actions[$removeProxyAction->name], ['workflow' => $workflow]);
            $runner->run($model, $removeProxyAction->data ?? []);
        }
    }

    /**
     * @param array<string, mixed> $eventData
     * @return Collection<int, Workflow>
     */
    public function getWorkflows(string $modelType, int $modelId, string $event, ?array $eventData, string $operator = '='): Collection
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

    /**
     * @param array<string, mixed> $eventData
     * @return Collection<int, Workflow>
     */
    public function getWorkflowsNullableAllowed(string $modelType, int $modelId, string $event, ?array $eventData, string $operator = '='): Collection
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
