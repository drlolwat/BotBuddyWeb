<?php

namespace App\BotBuddy\Workflow\Actions;

use App\Models\Account;
use App\Models\Workflow;
use Illuminate\Database\Eloquent\Model;

class Action
{
    public function __construct(public Workflow $workflow)
    {
        //
    }

    /**
     * @param Account $model
     * @param array<string, mixed> $data
     */
    public function run(Model $model, array $data): void
    {
        //
    }

    /**
     * @return array<string|int, \Illuminate\Contracts\Validation\Rule|array<\Illuminate\Contracts\Validation\Rule|string>|string>
     */
    public static function rules(): array
    {
        return [];
    }
}
