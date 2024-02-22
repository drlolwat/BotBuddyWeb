<?php

namespace App\Http\Controllers;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index()
    {
        $notifications = auth()->user()->notifications()
            ->orderByDesc('id')
            ->paginate(10);

        auth()->user()
            ->notifications()
            ->update(['opened_at' => now()]);

        return view('v1.notifications', compact('notifications'));
    }

    public function clear()
    {
        auth()->user()->notifications()->delete();

        return redirect()->back()->with('status', 'Notifications cleared');
    }
}
