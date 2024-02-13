<?php

namespace App\Policies;

use App\Models\Agent;

class AgentPolicy
{
    public function view(Agent $agent): bool
    {
        return $agent->user_id === auth()->id();
    }
}
