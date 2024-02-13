<?php

namespace App\Policies;

use App\Models\Account;

class AccountPolicy
{
    public function view(Account $account): bool
    {
        return $account->user_id === auth()->id();
    }
}
