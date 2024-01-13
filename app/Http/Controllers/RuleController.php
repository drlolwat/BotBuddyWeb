<?php

namespace App\Http\Controllers;

use App\Models\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class RuleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('rule');
    }

    public function create(Request $request)
    {
        // todo: ensure only contains valid columns
        $ruleData = Arr::where($request->all(), function ($value, $key) {
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

        // todo: check rules are valid e.g. moving script to same script

        $rule = Rule::create([
            'user_id' => auth()->user()->id,
            ...$ruleData,
            'data' => $eventData,
        ]);

        // todo: support multiple actions

        $action = $rule->actions()->create([
            'name' => $ruleData['action'],
            'data' => $actionData,
            'order' => 1,
        ]);

        return back()->with('status','Rule created');
    }
}
