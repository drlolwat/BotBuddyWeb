<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\UserScript;
use Illuminate\Http\Request;

class ScriptController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index()
    {
        $scripts = UserScript::where('user_id', auth()->id())->paginate(10);
        return view('v1.script.index', compact('scripts'));
    }

    public function show(UserScript $script)
    {
        $this->authorize('view', $script);

        return view('v1.script.show', compact('script'));
    }

    public function create()
    {
        return view('v1.script.create');
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
        $this->authorize('view', $script);

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
        $this->authorize('view', $script);

        $groupInUse = Account::where('script_id', $script->id)->count();

        if ($groupInUse > 0) {
            return back()->withErrors(['Cannot delete script as it is in use']);
        }

        $script->delete();

        return redirect(route('script'))->with('status', 'Script deleted');
    }
}
