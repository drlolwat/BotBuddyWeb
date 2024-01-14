<?php

namespace App\BotBuddy\Rule\Actions;

use App\Models\Rule;
use Illuminate\Database\Eloquent\Model;

class Action
{
    public function __construct(public Rule $rule)
    {
        //
    }

    public function run(Model $model, array $data): void
    {
        //
    }
}
