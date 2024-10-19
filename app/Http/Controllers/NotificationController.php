<?php

namespace App\Http\Controllers;

use App\Models\User;
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
        /** @var User $user */
        $user = auth()->user();

        $notifications = $user->notifications()
            ->orderByDesc('id')
            ->paginate(10);

        $user
            ->notifications()
            ->update(['opened_at' => now()]);

        return view('v1.notifications', compact('notifications'));
    }

    public function clear(): RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user();

        $user->notifications()->delete();

        return redirect()->back()->with('status', 'Notifications cleared');
    }
}
