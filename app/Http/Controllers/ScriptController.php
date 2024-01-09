<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\UserScript;
use Illuminate\Http\Request;

class ScriptController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index()
    {
        return view('script.index');
    }

    public function show(UserScript $script)
    {
        return view('script.show', compact('script'));
    }

    public function create()
    {
        return view('script.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validate($request, [
            'name' => 'required',
            'script' => 'required',
        ]);

        $account = UserScript::create([
            'name' => $validated['name'],
            'script' => $validated['script'],
            'user_id' => auth()->id(),
        ]);

        return redirect(route('script.show', $account))->with('status', 'Script added');
    }

    public function update(Request $request, UserScript $script)
    {
        $validated = $this->validate($request, [
            'name' => 'required',
            'script' => 'required',
        ]);

        $script->update([
            'name' => $validated['name'],
            'script' => $validated['script'],
        ]);

        return redirect(route('script.show', $script))->with('status', 'Script updated');
    }

    public function destroy(UserScript $script)
    {
        $groupInUse = Account::where('script_id', $script->id)->count();

        if ($groupInUse > 0) {
            return redirect(route('script.show', $script))->withErrors(['Cannot delete script as it is in use']);
        }

        $script->delete();

        return redirect(route('script'))->with('status', 'Script deleted');
    }
}
