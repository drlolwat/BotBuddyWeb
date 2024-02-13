<?php

namespace App\Policies;

use App\Models\Agent;
use App\Models\User;

class AgentPolicy
{
    public function view(User $user, Agent $agent): bool
    {
        return $agent->user_id === $user->id;
    }
}
