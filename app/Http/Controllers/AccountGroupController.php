<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\AccountGroup;
use Illuminate\Http\Request;

class AccountGroupController extends Controller
{
    public function index()
    {
        return view('account.group.index');
    }

    public function show(AccountGroup $group)
    {
        return view('account.group.show', compact('group'));
    }

    public function create()
    {
        return view('account.group.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validate($request, [
            'name' => 'required',
            'script_params' => 'nullable',
        ]);

        $group = AccountGroup::create([
            'name' => $validated['name'],
            'user_id' => auth()->id(),
            'script_params' => $validated['script_params'] ?? null,
        ]);

        return redirect(route('account.group.show', $group))->with('status', 'Account group created');
    }

    public function update(Request $request, AccountGroup $group)
    {
        $validated = $this->validate($request, [
            'name' => 'required',
            'script_params' => 'nullable',
        ]);

        $group->update([
            'name' => $validated['name'],
            'script_params' => $validated['script_params'] ?? null,
        ]);

        return redirect(route('account.group.show', $group))->with('status', 'Account group updated');
    }

    public function destroy(AccountGroup $group)
    {
        $groupInUse = Account::where('account_group_id', $group->id)->count();

        if ($groupInUse > 0) {
            return redirect(route('account.group.show', $group))->withErrors(['Cannot delete account group as it is in use']);
        }

        $group->delete();

        return redirect(route('account'))->with('status', 'Account group deleted');
    }
}
