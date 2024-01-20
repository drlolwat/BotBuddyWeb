<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index()
    {
        return view('settings');
    }

    public function update(Request $request)
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

        return redirect(route('settings'))->with('status', 'Global settings updated');
    }
}
