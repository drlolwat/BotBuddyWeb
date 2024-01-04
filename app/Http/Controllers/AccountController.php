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
        ]);

        $account = Account::create([
            'email' => $validated['email'],
            'password' => $validated['password'],
            'account_group_id' => $validated['account_group_id'] ?? null,
            'proxy_id' => $validated['proxy_id'],
            'script_id' => $validated['script_id'],
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
        ]);

        $account->update([
            'email' => $validated['email'],
            'password' => $validated['password'],
            'account_group_id' => $validated['account_group_id'] ?? null,
            'proxy_id' => $validated['proxy_id'],
            'script_id' => $validated['script_id'],
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
        $started = app('socket')->send('startBot', [
            'serverId' => 'dev-1',
            'internalId' => $account->id,
            'jarLocation' => 'C:\\Users\\lolwat\\DreamBot\\Bot\\Data\\client.jar', // bb account
            'scriptName' => $account->script->name,
            'clientName' => 'chocolatesoda', // dreambot
            'clientPassword' => 'Kv$w@*DMzj@8Bsh', // dreambot
            'accountUsername' => $account->email,
            'accountPassword' => $account->password,
        ]);

        if (!$started) {
            return redirect(route('account'))->withErrors(['status' => 'Failed to start account']);
        }

        $account->status = 'Running';
        $account->save();

        return redirect(route('account'))->with('status', 'Account started');
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
