<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\AccountGroup;
use Illuminate\Http\Request;

class AccountGroupController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index()
    {
        $accountGroups = AccountGroup::where('user_id', auth()->id())->paginate(10);
        return view('v1.account.group.index', compact('accountGroups'));
    }

    public function show(AccountGroup $group)
    {
        return view('v1.account.group.show', compact('group'));
    }

    public function create()
    {
        return view('v1.account.group.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validate($request, [
            'name' => 'required',
            'script_id' => 'required',
            'script_params' => 'nullable',
            'fps' => 'required|int',
            'world' => 'required',
        ]);

        if (!($validated['world'] == 'f2p' || $validated['world'] == 'members' || is_int($validated['world']))) {
            return back()->withErrors('Invalid world provided');
        }

        $group = AccountGroup::create([
            'name' => $validated['name'],
            'user_id' => auth()->id(),
            'script_id' => $validated['script_id'],
            'script_params' => $validated['script_params'] ?? null,
            'fps' => $validated['fps'],
            'world' => $validated['world'],
        ]);

        return redirect(route('account.group.show', $group))->with('status', 'Account group created');
    }

    public function update(Request $request, AccountGroup $group)
    {
        $validated = $this->validate($request, [
            'name' => 'required',
            'script_id' => 'required',
            'script_params' => 'nullable',
            'fps' => 'required|int',
            'world' => 'required',
        ]);

        if (!($validated['world'] == 'f2p' || $validated['world'] == 'members' || is_int($validated['world']))) {
            return back()->withErrors('Invalid world provided');
        }

        $group->update([
            'name' => $validated['name'],
            'script_id' => $validated['script_id'],
            'script_params' => $validated['script_params'] ?? null,
            'fps' => $validated['fps'],
            'world' => $validated['world'],
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
