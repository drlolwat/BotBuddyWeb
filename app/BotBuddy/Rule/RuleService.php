<?php

namespace App\BotBuddy\Rule;

use App\BotBuddy\Rule\Actions\ChangeScript;
use App\BotBuddy\Rule\Actions\RestartBot;
use App\BotBuddy\Rule\Actions\StopBot;
use App\Models\Rule;
use Illuminate\Database\Eloquent\Collection;

class RuleService
{
    public array $actions = [
        'change_script' => ChangeScript::class,
        'stop_bot' => StopBot::class,
        'restart_bot' => RestartBot::class,
    ];

    public function handle(Rule $rule): void
    {
        foreach($rule->actions as $action) {
            $runner = app()->makeWith($this->actions[$action->name], ['rule' => $rule]);
            $runner->run($action->data);
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
