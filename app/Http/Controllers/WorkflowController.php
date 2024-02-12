<?php

namespace App\Http\Controllers;

use App\Models\Workflow;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class WorkflowController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $workflows = Workflow::query()
            ->with('actions')
            ->where('user_id', auth()->id())
            ->paginate(5);

        return view('v1.workflow.index', compact('workflows'));
    }

    public function create()
    {
        return view('v1.workflow.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'model_type' => 'required|string',
            'model_id' => 'required|integer',
            'event' => 'required|string',
            'action' => 'required|array',
        ]);

        // todo: validate action names exist as fields, validate event_* field(s) exist
        $validated = $request->all();

        // todo: validate each action, action classes need to be able to define their own validation rules
        $actions = collect($validated['action'])
            ->mapWithKeys(fn ($action) => [$action => $validated[$action] ?? null])
            ->toArray();

        // todo: ensure only contains valid columns
        $workflowData = Arr::where($validated, function ($value, $key) use ($actions) {
            return !Str::startsWith($key, 'event_')
                && !Str::startsWith($key, 'action')
                && $key != '_token' && !array_key_exists($key, $actions);
        });

        $eventData = Arr::mapWithKeys(Arr::where($validated, function ($value, $key) {
            return Str::startsWith($key, 'event_');
        }), function ($value, $key) {
            return [Str::replaceFirst('event_', '', $key) => $value];
        });

        $workflow = Workflow::create([
            'name' => $validated['name'],
            'user_id' => auth()->user()->id,
            ...$workflowData,
            'data' => count($eventData) > 0 ? $eventData : null,
        ]);

        $i = 1;
        foreach ($actions as $action => $data) {
            $workflow->actions()->create([
                'name' => $action,
                'data' => $data ? array_filter($data, fn ($value) => !is_null($value)) : null,
                'order' => $i,
            ]);
            $i++;
        }

        return redirect(route('workflow'))->with('status','Workflow created');
    }

    public function destroy(Workflow $workflow)
    {
        $workflow->delete();
        return redirect(route('workflow'))->with('status','Workflow deleted');
    }
}
