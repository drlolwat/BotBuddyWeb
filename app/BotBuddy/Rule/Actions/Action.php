<?php

namespace App\BotBuddy\Rule\Actions;

use App\Models\Rule;

class Action
{
    public function __construct(public Rule $rule)
    {
        //
    }

    public function run(array $data): void
    {
        //
    }
}
