<?php

namespace App\Http\Controllers;

use App\Models\ScriptLogTrigger;
use App\Models\UserScript;

class ScriptTriggerController extends Controller
{
    public function create(UserScript $script)
    {
        $this->authorize('view', $script);

        $user = auth()->user();
        if (!in_array($user->subscription?->name, ['Farm', 'Founder'])) {
            return back()->withErrors(['You need the Farm subscription tier to create triggers']);
        }
        return view('v1.script.trigger.create', compact('script'));
    }

    public function store()
    {
        $script = UserScript::findOrFail(request('script_id'));
        $this->authorize('view', $script);
        $user = auth()->user();
        if (!in_array($user->subscription?->name, ['Farm', 'Founder'])) {
            return back()->withErrors(['You need the Farm subscription tier to create triggers']);
        }

        // todo: validation
        $trigger = ScriptLogTrigger::create([
            'script_id' => request('script_id'),
            'name' => request('name'),
            'message' => request('message'),
        ]);

        return redirect()
            ->route('script.trigger.show', $trigger)
            ->with('status', 'Script trigger created');
    }

    public function show(ScriptLogTrigger $trigger)
    {
        $this->authorize('view', $trigger->script);

        return view('v1.script.trigger.show', compact('trigger'));
    }

    public function update(ScriptLogTrigger $trigger)
    {
        $this->authorize('view', $trigger->script);
        $user = auth()->user();
        if (!in_array($user->subscription?->name, ['Farm', 'Founder'])) {
            return back()->withErrors(['You need the Farm subscription tier to update triggers']);
        }

        $trigger->update([
            'name' => request('name'),
            'message' => request('message'),
        ]);

        return redirect(route('script.trigger.show', $trigger))
            ->with('status', 'Script trigger updated');
    }

    public function destroy(ScriptLogTrigger $trigger)
    {
        $this->authorize('view', $trigger->script);

        $trigger->delete();

        return redirect()
            ->route('script.show', $trigger->script_id)
            ->with('success', 'Script trigger deleted');
    }

    public function bulkAction()
    {
        $script = UserScript::findOrFail(request('script_id'));
        $this->authorize('view', $script);

        $this->validate(request(), [
            'script_id' => 'required',
            'action' => 'required|in:delete',
            'triggers' => 'required|array',
        ]);

        $ids = request('triggers');
        $action = request('action');
        $triggers = ScriptLogTrigger::query()
            ->whereIn('id', array_keys($ids))
            ->whereHas('script', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->get();

        if ($triggers->count() === 0) {
            return back()
                ->withErrors(['No valid triggers selected']);
        }

        if ($action === 'delete') {
            $triggers->each->delete();
        }

        return back()->with('status', 'Script triggers deleted');
    }
}
