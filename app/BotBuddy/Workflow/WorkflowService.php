<?php

namespace App\BotBuddy\Workflow;

use App\BotBuddy\Workflow\Actions\ChangeAccountGroup;
use App\BotBuddy\Workflow\Actions\ChangeProxy;
use App\BotBuddy\Workflow\Actions\ChangeScript;
use App\BotBuddy\Workflow\Actions\RestartBot;
use App\BotBuddy\Workflow\Actions\StopAndReplenishFrom;
use App\BotBuddy\Workflow\Actions\StopBot;
use App\Models\Workflow;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class WorkflowService
{
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
            $runner = app()->makeWith($this->actions[$action->name], ['rule' => $workflow]);
            $runner->run($model, $action->data);
        }
    }

    public function getWorkflows($modelType, $modelId, $event, $eventData): Collection
    {
        $query = Workflow::query()
            ->with('model', 'actions')
            ->where('model_type', $modelType)
            ->where('model_id', $modelId)
            ->where('event', $event);

        if ($eventData) {
            foreach ($eventData as $key => $value) {
                $query = $query->whereRaw("data->>'$." . $key . "' = ?", [$value]);
            }
        }

        return $query->get();
    }
}
