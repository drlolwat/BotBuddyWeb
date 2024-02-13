<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserScript;

class UserScriptPolicy
{
    public function view(User $user, UserScript $userScript): bool
    {
        return $userScript->user_id === $user->id;
    }
}
