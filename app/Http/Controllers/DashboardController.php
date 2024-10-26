<?php

namespace App\Http\Controllers;

use App\BotBuddy\Status;
use App\Models\User;
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

        /** @var User $user */
        $user = auth()->user();

        $online = $user->accounts()->where('status', Status::RUNNING->value)->count();
        $offline = $user->accounts()->where('status', '!=', Status::RUNNING->value)->count();

        $bannedLast24h = $user->accounts()
            ->where(function ($query) use ($yesterday) {
                $query->where('perm_banned_at', '>=', $yesterday)
                    ->orWhere('temp_banned_at', '>=', $yesterday);
            })
            ->count();

        $query = $user->accounts()
            ->with('account_group', 'stats', 'agent', 'script');

        if (request()->get('status')) {
            $query = $query->where('status', request()->get('status'));
        } else {
            $query = $query->where('status', [Status::RUNNING->value, Status::NO_SCRIPT->value, Status::COMPLETED->value, Status::PROXY_BLOCKED->value]);
        }

        if (request()->get('account_group_id')) {
            $query = $query->where('account_group_id', request()->get('account_group_id'));
        }

        $accounts = $query->paginate(25, ['*'], 'accounts');

        return view('v1.dashboard', compact('online', 'offline', 'bannedLast24h', 'accounts'));
    }
}
