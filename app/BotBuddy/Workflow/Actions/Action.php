<?php

namespace App\BotBuddy\Workflow\Actions;

use App\Models\Workflow;
use Illuminate\Database\Eloquent\Model;

class Action
{
    public function __construct(public Workflow $workflow)
    {
        //
    }

    public function run(Model $model, array $data): void
    {
        //
    }

    public static function rules(): array
    {
        return [];
    }
}
