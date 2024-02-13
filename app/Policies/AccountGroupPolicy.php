<?php

namespace App\Policies;

use App\Models\AccountGroup;
use App\Models\User;

class AccountGroupPolicy
{
    public function view(User $user, AccountGroup $accountGroup): bool
    {
        return $accountGroup->user_id === $user->id;
    }
}
