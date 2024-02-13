<?php

namespace App\Policies;

use App\Models\Workflow;

class WorkflowPolicy
{
    public function view(Workflow $workflow): bool
    {
        return $workflow->user_id === auth()->id();
    }
}
