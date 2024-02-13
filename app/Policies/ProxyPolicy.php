<?php

namespace App\Policies;

use App\Models\Proxy;
use App\Models\User;

class ProxyPolicy
{
    public function view(User $user, Proxy $proxy): bool
    {
        return $proxy->user_id === $user->id;
    }
}
