<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index()
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

        return view('v1.dashboard', compact('online', 'offline', 'bannedLast24h'));
    }
}
