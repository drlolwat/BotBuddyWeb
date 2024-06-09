<?php

namespace App\Http\Controllers;

use App\BotBuddy\Workflow\Events\Event;
use App\BotBuddy\Workflow\Events\PermBanned;
use App\BotBuddy\Workflow\Events\ProxyBlocked;
use App\BotBuddy\Workflow\Events\ScriptComplete;
use App\BotBuddy\Workflow\Events\StatGoal;
use App\BotBuddy\Workflow\Events\TempBanned;
use App\BotBuddy\Workflow\WorkflowService;
use App\Models\Workflow;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\View\View;

class WorkflowController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(): View
    {
        $workflows = Workflow::query()
            ->with('actions')
            ->where('user_id', auth()->id())
            ->paginate(5);

        return view('v1.workflow.index', compact('workflows'));
    }

    public function create(): View
    {
        return view('v1.workflow.create');
    }

    public function store(Request $request, WorkflowService $workflowService): RedirectResponse
    {
        $workflowCount = Workflow::query()
            ->where('user_id', auth()->id())
            ->count();

        $maxWorkflows = auth()->user()->subscription->max_workflows;

        if ($workflowCount >= $maxWorkflows) {
            return back()->withErrors("You are not allowed to create more than $maxWorkflows workflows");
        }

        $this->validate($request, [
            'name' => 'required|string',
            'model_type' => [
                'required',
                'string',
                function ($attribute, $value, $fail) use($workflowService) {
                    if (!array_key_exists($value, $workflowService->modelTypes)) {
                        $fail($attribute.' is not a valid model type.');
                    }
                },
            ],
        ]);

        $this->validate($request, [
            'model_id' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) use($request, $workflowService) {
                    if (!$workflowService->modelTypes[$request->model_type]::query()->where('id', $value)->exists()) {
                        $fail($value.' is not a valid model.');
                    }
                },
            ],
            'event' => [
                'required',
                'string',
                function ($attribute, $value, $fail) use($workflowService) {
                    if (!array_key_exists($value, $workflowService->events)) {
                        $fail($attribute.' is not a valid event.');
                    }
                },
            ],
            'action' => [
                'required',
                'array',
                function ($attribute, $value, $fail) use($workflowService) {
                    foreach ($value as $action) {
                        if (!array_key_exists($action, $workflowService->actions)) {
                            $fail($attribute.' is not a valid action.');
                        }
                    }
                },
            ],
        ]);

        $validated = $request->all();

        $eventData = Arr::mapWithKeys(Arr::where($validated, function ($value, $key) {
            return Str::startsWith($key, 'event_');
        }), function ($value, $key) {
            return [Str::replaceFirst('event_', '', (string)$key) => $value];
        });

        if (auth()->user()->subscription->name == 'Basic' && in_array($validated['event'], ['temp_banned', 'stat_goal'])) {
            return back()->withErrors('You are not allowed to use this workflow event');
        }

        if ($validated['event'] == 'stat_goals' && count($eventData) == 0) {
            return back()->withErrors('You have not provided any stat goals');
        }

        /** @var PermBanned|ProxyBlocked|ScriptComplete|StatGoal|TempBanned $eventValidator */
        $eventValidator = new $workflowService->events[$validated['event']]();
        $eventValidator->replace($eventData);
        $eventDataValidated = $eventValidator->validate($eventValidator->rules());

        $actions = (new Collection($validated['action']))
            ->mapWithKeys(fn ($action) => [$action => $validated[$action] ?? null])
            ->toArray();

        foreach ($actions as $action => $actionData) {
            $actionDataRequest = (new Request())->replace($actionData ?? []);
            $this->validate($actionDataRequest, $workflowService->actions[$action]::rules());
        }

        // todo: handle via a workflow event class
        if ($validated['event'] == 'stat_goal') {
            if (!isset($eventDataValidated['stat'])) {
                return back()->withErrors('You have not provided any stat goals');
            }
            $eventDataValidated = [...$eventDataValidated['stat']];
        }

        $workflow = Workflow::create([
            'name' => $validated['name'],
            'user_id' => auth()->user()->id,
            'model_type' => $validated['model_type'],
            'model_id' => $validated['model_id'],
            'event' => $validated['event'],
            'data' => count($eventDataValidated) > 0 ? $eventDataValidated : null,
        ]);

        $i = 1;
        foreach ($actions as $action => $actionData) {
            $workflow->actions()->create([
                'name' => $action,
                'data' => $actionData ? array_filter($actionData, fn ($value) => !is_null($value)) : null,
                'order' => $i,
            ]);
            $i++;
        }

        return redirect(route('workflow'))->with('status','Workflow created');
    }

    public function destroy(Workflow $workflow): RedirectResponse
    {
        $this->authorize('view', $workflow);
        $workflow->delete();
        return redirect(route('workflow'))->with('status','Workflow deleted');
    }

    /**
     * Returns the workflow events the user is allowed to use.
     * @return array<string>
     */
    public function events(WorkflowService $workflowService): array
    {
        $subscription = auth()->user()->subscription;
        if (!$subscription) {
            return [];
        }

        $events = array_keys($workflowService->events);

        if ($subscription->name == 'Basic') {
            unset($events[array_search('temp_banned', $events)]);
            unset($events[array_search('stat_goal', $events)]);
        }

        return array_values($events);
    }
}
