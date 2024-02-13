<?php

namespace App\Policies;

use App\Models\WorkflowAction;

class WorkflowActionPolicy
{
    public function view(WorkflowAction $workflowAction): bool
    {
        return $workflowAction->rule->user_id === auth()->id();
    }
}
