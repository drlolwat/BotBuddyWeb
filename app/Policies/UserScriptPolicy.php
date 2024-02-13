<?php

namespace App\Policies;

use App\Models\UserScript;

class UserScriptPolicy
{
    public function view(UserScript $userScript): bool
    {
        return $userScript->user_id === auth()->id();
    }
}
