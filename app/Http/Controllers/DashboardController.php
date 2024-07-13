<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'subscription.expire.warning']);
    }

    public function index(): View
    {
        $yesterday = now()->subDay();

        $online = auth()->user()->accounts()->where('status', 'Running')->count();
        $offline = auth()->user()->accounts()->where('status', '!=', 'Running')->count();

        $bannedLast24h = auth()->user()->accounts()
            ->where(function ($query) use ($yesterday) {
                $query->where('perm_banned_at', '>=', $yesterday)
                    ->orWhere('temp_banned_at', '>=', $yesterday);
            })
            ->count();

        $query = auth()->user()->accounts()
            ->with('account_group', 'stats', 'agent', 'script');

        if (request()->get('status')) {
            $query = $query->where('status', request()->get('status'));
        } else {
            $query = $query->where('status', ['Running', 'NoScript', 'Completed', 'ProxyBlocked']);
        }

        if (request()->get('account_group_id')) {
            $query = $query->where('account_group_id', request()->get('account_group_id'));
        }

        $accounts = $query->paginate(25, ['*'], 'accounts');

        return view('v1.dashboard', compact('online', 'offline', 'bannedLast24h', 'accounts'));
    }
}
