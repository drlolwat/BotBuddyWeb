<?php

namespace App\Policies;

use App\Models\AccountStat;

class AccountStatPolicy
{
    public function view(AccountStat $stats): bool
    {
        return $stats->account->user_id === auth()->id();
    }
}
