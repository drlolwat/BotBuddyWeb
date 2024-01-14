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
        return view('workflow');
    }

    public function create(Request $request)
    {
        // todo: ensure only contains valid columns
        $workflowData = Arr::where($request->all(), function ($value, $key) {
            return !Str::startsWith($key, 'event_') && !Str::startsWith($key, 'action_') && $key != '_token';
        });

        $eventData = Arr::mapWithKeys(Arr::where($request->all(), function ($value, $key) {
            return Str::startsWith($key, 'event_');
        }), function ($value, $key) {
            return [Str::replaceFirst('event_', '', $key) => $value];
        });

        $actionData = Arr::mapWithKeys(Arr::where($request->all(), function ($value, $key) {
            return Str::startsWith($key, 'action_');
        }), function ($value, $key) {
            return [Str::replaceFirst('action_', '', $key) => $value];
        });

        // todo: check workflow is valid e.g. moving script to same script

        $workflow = Workflow::create([
            'user_id' => auth()->user()->id,
            ...$workflowData,
            'data' => $eventData,
        ]);

        // todo: support multiple actions

        $action = $workflow->actions()->create([
            'name' => $workflowData['action'],
            'data' => $actionData,
            'order' => 1,
        ]);

        return back()->with('status','Workflow created');
    }
}
