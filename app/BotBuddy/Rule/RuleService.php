<?php

namespace App\BotBuddy\Rule;

use App\BotBuddy\Rule\Actions\ChangeAccountGroup;
use App\BotBuddy\Rule\Actions\ChangeScript;
use App\BotBuddy\Rule\Actions\RestartBot;
use App\BotBuddy\Rule\Actions\StopBot;
use App\Models\Rule;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class RuleService
{
    public array $actions = [
        'change_script' => ChangeScript::class,
        'stop_bot' => StopBot::class,
        'restart_bot' => RestartBot::class,
        'restart_bot_with_script_params' => RestartBot::class,
        'change_account_group' => ChangeAccountGroup::class,
    ];

    public function handle(Model $model, Rule $rule): void
    {
        foreach($rule->actions as $action) {
            $runner = app()->makeWith($this->actions[$action->name], ['rule' => $rule]);
            $runner->run($model, $action->data);
        }
    }

    public function getRules($modelType, $modelId, $event, $eventData): Collection
    {
        return Rule::query()
            ->with('model', 'actions')
            ->where('model_type', $modelType)
            ->where('model_id', $modelId)
            ->where('event', $event)
            ->whereRaw('data = CAST(? AS JSON)', [json_encode($eventData)])
            ->get();
    }
}
