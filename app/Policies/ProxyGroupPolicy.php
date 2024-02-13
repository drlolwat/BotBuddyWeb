<?php

namespace App\Policies;

use App\Models\ProxyGroup;
use App\Models\User;

class ProxyGroupPolicy
{
    public function view(User $user, ProxyGroup $proxyGroup): bool
    {
        return $proxyGroup->user_id === $user->id;
    }
}
