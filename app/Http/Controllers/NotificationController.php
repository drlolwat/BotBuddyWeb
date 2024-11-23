<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Inertia\Inertia;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(): View|\Inertia\Response
    {
        /** @var User $user */
        $user = auth()->user();

        $notifications = $user->notifications()
            ->orderByDesc('id')
            ->paginate(10);

        $notifications = $notifications->toArray();

        foreach ($notifications['data'] as $key => $notification) {
            $notifications['data'][$key]['created_at'] = Carbon::parse($notification['created_at'])->diffForHumans();
        }

        $user
            ->notifications()
            ->update(['opened_at' => now()]);

        return Inertia::render('Notifications', [
            'notifications' => $notifications,
        ]);
    }

    public function clear(): RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user();

        $user->notifications()->delete();

        return back()->with('status', 'Notifications cleared');
    }
}
