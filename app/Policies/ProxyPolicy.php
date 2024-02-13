<?php

namespace App\Policies;

use App\Models\Proxy;

class ProxyPolicy
{
    public function view(Proxy $proxy): bool
    {
        return $proxy->user_id === auth()->id();
    }
}
