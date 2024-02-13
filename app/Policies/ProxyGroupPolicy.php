<?php

namespace App\Policies;

use App\Models\ProxyGroup;

class ProxyGroupPolicy
{
    public function view(ProxyGroup $proxyGroup): bool
    {
        return $proxyGroup->user_id === auth()->id();
    }
}
