<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WorkflowAction;

class WorkflowActionPolicy
{
    public function view(User $user, WorkflowAction $workflowAction): bool
    {
        return $workflowAction->rule?->user_id === $user->id;
    }
}
