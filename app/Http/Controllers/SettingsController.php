<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(): View
    {
        return view('v1.settings');
    }

    public function dark_mode(Request $request): RedirectResponse
    {
        //dd($request->all());
        $this->validate($request, [
            'dark_mode' => 'nullable',
        ]);

        // convert checkbox value to bool
        $darkMode = $request->input('dark_mode') === 'on';

        $user = auth()->user();
        $user->dark_mode = $darkMode;
        $user->save();

        return back()->with('status', "Dark mode " . ($darkMode ? 'enabled' : 'disabled'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $this->validate($request, [
            'dreambot_username' => '',
            'dreambot_password' => '',
        ]);

        $user = auth()->user();

        if ($validated['dreambot_username'] != $user->dreambot_username) {
            $user->dreambot_username = $validated['dreambot_username'];
        }

        if (strlen($validated['dreambot_password']) > 0 && $validated['dreambot_password'] != $user->dreambot_password) {
            $user->dreambot_password = $validated['dreambot_password'];
        }

        $user->save();

        return redirect(route('settings'))->with('status', 'DreamBot settings updated');
    }
}
