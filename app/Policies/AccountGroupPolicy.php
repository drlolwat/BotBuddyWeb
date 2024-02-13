<?php

namespace App\Policies;

use App\Models\AccountGroup;

class AccountGroupPolicy
{
    public function view(AccountGroup $accountGroup): bool
    {
        return $accountGroup->user_id === auth()->id();
    }
}
