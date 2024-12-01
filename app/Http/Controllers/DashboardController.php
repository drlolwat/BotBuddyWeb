<?php

namespace App\Http\Controllers;

use App\BotBuddy\Status;
use App\Models\User;
use Illuminate\View\View;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'subscription.expire.warning']);
    }

    public function index(): \Inertia\Response
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
            ->select(['id', 'email', 'status', 'account_group_id'])
            ->with([
                'stats:id,account_id,name,gp,qp,ttl',
                'account_group:id,name,agent_id,script_id',
                'account_group.agent:id,name',
                'account_group.script:id,name',
            ]);

        if (request()->get('status')) {
            $query = $query->where('status', request()->get('status'));
        } else {
            $query = $query->where('status', [Status::RUNNING->value, Status::NO_SCRIPT->value, Status::COMPLETED->value, Status::PROXY_BLOCKED->value]);
        }

        if (request()->get('account_group_id')) {
            $query = $query->where('account_group_id', request()->get('account_group_id'));
        }

        $accounts = $query->paginate(25);

        foreach ($accounts as $account) {
            if (isset($account->stats)) {
                $account->stats->gp_formatted = $account->stats?->gp_formatted;
            }
            $account->status_formatted = $account->status_formatted;
        }

        //return view('v1.dashboard', compact('online', 'offline', 'bannedLast24h', 'accounts'));

        return Inertia::render('Dashboard', compact('online', 'offline', 'bannedLast24h', 'accounts'));
    }
}
