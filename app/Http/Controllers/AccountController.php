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
            'email' => 'required|email',
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
            'email' => 'required|email',
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
}
