<?php

namespace App\Policies;

use App\Models\AccountStat;
use App\Models\User;

class AccountStatPolicy
{
    public function view(User $user, AccountStat $stats): bool
    {
        return $stats->account?->user_id === $user->id;
    }
}
