<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index()
    {
        return view('account.index');
    }

    public function show(Account $account)
    {
        return view('account.show', compact('account'));
    }

    public function create()
    {
        return view('account.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validate($request, [
            'email' => 'required',
            'password' => 'required',
            'account_group_id' => 'nullable',
            'proxy_id' => 'nullable',
            'script_id' => 'required',
            'script_params' => 'nullable',
        ]);

        $account = Account::create([
            'email' => $validated['email'],
            'password' => $validated['password'],
            'account_group_id' => $validated['account_group_id'] ?? null,
            'proxy_id' => $validated['proxy_id'],
            'script_id' => $validated['script_id'],
            'script_params' => $validated['script_params'] ?? null,
            'user_id' => auth()->id(),
        ]);

        return redirect(route('account.show', $account))->with('status', 'Account created');
    }

    public function update(Request $request, Account $account)
    {
        $validated = $this->validate($request, [
            'email' => 'required',
            'password' => 'required',
            'account_group_id' => 'nullable',
            'proxy_id' => 'nullable',
            'script_id' => 'required',
            'script_params' => 'nullable',
            'agent_id' => 'nullable',
        ]);

        $account->update([
            'email' => $validated['email'],
            'password' => $validated['password'],
            'account_group_id' => $validated['account_group_id'] ?? null,
            'proxy_id' => $validated['proxy_id'],
            'script_id' => $validated['script_id'],
            'agent_id' => $validated['agent_id'] ?? null,
            'script_params' => $validated['script_params'] ?? null,
        ]);

        return redirect(route('account.show', $account))->with('status', 'Account updated');
    }

    public function destroy(Account $account)
    {
        $account->delete();

        return redirect(route('account'))->with('status', 'Account deleted');
    }

    public function start(Account $account)
    {
        $user = auth()->user();

        if (!$user->dreambot_username || !$user->dreambot_password || !$user->dreambot_client) {
            return redirect(route('settings'))
                ->withErrors(['dreambot_username' => 'Please configure your DreamBot credentials and client.jar to start an account']);
        }

        $started = app('socket')->send('startBot', [
            'serverId' => $account->agent->uuid,
            'internalId' => $account->id,
            'jarLocation' => $user->dreambot_client,
            'scriptName' => $account->script->script ?? $account->account_group->script->script,
            'scriptParams' => $account->script_params ?? $account->account_group->script_params ?? "",
            'clientName' => $user->dreambot_username,
            'clientPassword' => $user->dreambot_password,
            'accountUsername' => $account->email,
            'accountPassword' => $account->password,
        ]);

        if (!$started) {
            return redirect(route('account'))->withErrors(['status' => 'Failed to start account']);
        }

        $account->status = 'Starting';
        $account->save();

        return redirect(route('account'))->with('status', 'Account is being started');
    }

    public function stop(Account $account)
    {
        $stopped = app('socket')->send('stopBot', [
            'serverId' => 'dev-1',
            'internalId' => $account->id,
        ]);

        if (!$stopped) {
            return redirect(route('account'))->withErrors(['status' => 'Failed to stop account']);
        }

        $account->status = 'Stopped';
        $account->save();

        return redirect(route('account'))->with('status', 'Account stopped');
    }
}
