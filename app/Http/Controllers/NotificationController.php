<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(): View
    {
        $notifications = auth()->user()->notifications()
            ->orderByDesc('id')
            ->paginate(10);

        auth()->user()
            ->notifications()
            ->update(['opened_at' => now()]);

        return view('v1.notifications', compact('notifications'));
    }

    public function clear(): RedirectResponse
    {
        auth()->user()->notifications()->delete();

        return redirect()->back()->with('status', 'Notifications cleared');
    }
}
