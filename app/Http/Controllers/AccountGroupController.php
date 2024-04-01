<?php

namespace App\Http\Controllers;

use App\BotBuddy\Socket\Commands\StartBotCommand;
use App\BotBuddy\Socket\Commands\StopBotCommand;
use App\BotBuddy\Socket\SocketService;
use App\Models\Account;
use App\Models\AccountGroup;
use App\Models\ScheduleEvent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AccountGroupController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(): View
    {
        $accountGroups = AccountGroup::query()
            ->with('script')
            ->where('user_id', auth()->id())
            ->withCount('accounts')
            ->paginate(10);

        return view('v1.account.group.index', compact('accountGroups'));
    }

    public function show(AccountGroup $group): View|RedirectResponse
    {
        $this->authorize('view', $group);

        $accounts = $group->accounts()->with('agent', 'script', 'stats')->paginate(10);

        return view('v1.account.group.show', compact('group', 'accounts'));
    }

    public function create(): View
    {
        return view('v1.account.group.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validate($request, [
            'name' => 'required',
            'agent_id' => [
                'nullable',
                'integer',
                Rule::exists('agents', 'id')
                    ->where(function ($query) {
                        $query->where('user_id', auth()->id());
                    }),
            ],
            'script_id' => [
                'required',
                'integer',
                Rule::exists('user_scripts', 'id')
                    ->where(function ($query) {
                        $query->where('user_id', auth()->id());
                    }),
            ],
            'script_params' => 'nullable',
            'fps' => 'required|int',
            'world' => 'required',
        ]);

        if (!($validated['world'] == 'f2p' || $validated['world'] == 'members' || filter_var($validated['world'], FILTER_VALIDATE_INT))) {
            return back()->withErrors('Invalid world provided');
        }

        $group = AccountGroup::create([
            'name' => $validated['name'],
            'user_id' => auth()->id(),
            'agent_id' => $validated['agent_id'] ?? null,
            'script_id' => $validated['script_id'],
            'script_params' => $validated['script_params'] ?? null,
            'fps' => $validated['fps'],
            'world' => $validated['world'],
        ]);

        return redirect(route('account.group.show', $group))->with('status', 'Account group created');
    }

    public function update(Request $request, AccountGroup $group): RedirectResponse
    {
        $this->authorize('view', $group);

        $validated = $this->validate($request, [
            'name' => 'required',
            'agent_id' => [
                'nullable',
                'integer',
                Rule::exists('agents', 'id')
                    ->where(function ($query) {
                        $query->where('user_id', auth()->id());
                    }),
            ],
            'script_id' => [
                'required',
                'integer',
                Rule::exists('user_scripts', 'id')
                    ->where(function ($query) {
                        $query->where('user_id', auth()->id());
                    }),
            ],
            'script_params' => 'nullable',
            'fps' => 'required|int',
            'world' => 'required',
        ]);

        if (!($validated['world'] == 'f2p' || $validated['world'] == 'members' || filter_var($validated['world'], FILTER_VALIDATE_INT))) {
            return back()->withErrors('Invalid world provided');
        }

        $group->update([
            'name' => $validated['name'],
            'agent_id' => $validated['agent_id'] ?? null,
            'script_id' => $validated['script_id'],
            'script_params' => $validated['script_params'] ?? null,
            'fps' => $validated['fps'],
            'world' => $validated['world'],
        ]);

        return redirect(route('account.group.show', $group))->with('status', 'Account group updated');
    }

    public function destroy(AccountGroup $group): RedirectResponse
    {
        $this->authorize('view', $group);

        $groupInUse = Account::where('account_group_id', $group->id)->count();

        if ($groupInUse > 0) {
            return redirect(route('account.group.show', $group))->withErrors(['Cannot delete account group as it is in use']);
        }

        $group->delete();

        return redirect(route('account.group'))->with('status', 'Account group deleted');
    }

    public function start(AccountGroup $group, SocketService $socket): RedirectResponse
    {
        $this->authorize('view', $group);

        $accounts = $group->accounts()
            ->with('agent', 'script')
            ->whereIn('status', ['Stopped', 'Queued'])->get();
        $user = auth()->user();

        if (!$user->dreambot_username || !$user->dreambot_password) {
            return redirect(route('settings'))
                ->withErrors(['dreambot_username' => 'Please configure your DreamBot credentials to start an account']);
        }

        $response = back();
        $errors = [];
        $started_count = 0;

        foreach ($accounts as $account) {
            $agent = $account->agent ?? $account->account_group->agent ?? null;
            if(!$agent) {
                $errors[] = "$account->email is not assigned to an agent";
                continue;
            }

            if(!$account->script) {
                $errors[] = "$account->email does not have a script assigned";
                continue;
            }

            if ($agent->client_type != 'DreamBot') {
                $errors[] = "Agent \"{$agent->name}\" is not using DreamBot client";
                continue;
            }

            if (!$agent->dreambot_client_path) {
                $errors[] = "Agent \"{$agent->name}\" does not have DreamBot client.jar path configured";
                continue;
            }

            if (!$agent->dreambot_scripts_path) {
                $errors[] = "Agent \"{$agent->name}\" does not have DreamBot scripts path configured";
                continue;
            }

            $started = $socket->dispatch(new StartBotCommand($account));

            if ($started != "true") {
                $errors[] = "Failed to start $account->email";
                continue;
            }

            $account->status = 'Starting';
            $account->start_queued_at = null; // in case it was formerly queued
            $account->save();

            $started_count++;
        }

        if ($started_count == 1) {
            $response = $response->with('status', '1 account is being started');
        }

        if ($started_count > 1) {
            $response = $response->with('status', "$started_count accounts are being started");
        }

        return $response->withErrors($errors);
    }

    public function stop(AccountGroup $group, SocketService $socket): RedirectResponse
    {
        $statuses = [
            'Running', 'Starting', 'Completed',
            'NoScript', 'ProxyBlocked', 'Banned',
        ];

        $this->authorize('view', $group);
        $accounts = $group->accounts()
            ->whereIn('status', $statuses)
            ->get();

        $stop_count = 0;

        foreach ($accounts as $account) {
            $stopped = $socket->dispatch(new StopBotCommand($account));

            if ($stopped != "true") {
                continue;
            }

            $account->status = 'Stopping';
            $account->save();
            $stop_count++;
        }

        if ($stop_count == 0) {
            return back()->withErrors('No accounts are running');
        }

        if ($stop_count == 1) {
            return back()->with('status', '1 account is being stopped');
        }

        return back()->with('status', 'Accounts are being stopped');
    }

    public function queue(AccountGroup $group): RedirectResponse
    {
        $this->authorize('view', $group);

        $minutesValidated = request()->validate([
            'minutes' => 'required|int|min:1|max:120',
        ]);
        $minutes = (int)$minutesValidated['minutes'];

        $accounts = $group->accounts()
            ->with('agent', 'script')
            ->whereIn('status', ['Stopped', 'Stopping'])->get();

        $user = auth()->user();

        if (!$user->dreambot_username || !$user->dreambot_password) {
            return redirect(route('settings'))
                ->withErrors(['dreambot_username' => 'Please configure your DreamBot credentials to start an account']);
        }

        $response = back();
        $errors = [];
        $queued_count = 0;
        $start_queue = now()->addMinute()->second(0);

        foreach ($accounts as $account) {
            $agent = $account->agent ?? $account->account_group->agent ?? null;
            if(!$agent) {
                $errors[] = "$account->email is not assigned to an agent";
                continue;
            }

            if(!$account->script) {
                $errors[] = "$account->email does not have a script assigned";
                continue;
            }

            if ($agent->client_type != 'DreamBot') {
                $errors[] = "Agent \"{$agent->name}\" is not using DreamBot client";
                continue;
            }

            if (!$agent->dreambot_client_path) {
                $errors[] = "Agent \"{$agent->name}\" does not have DreamBot client.jar path configured";
                continue;
            }

            if (!$agent->dreambot_scripts_path) {
                $errors[] = "Agent \"{$agent->name}\" does not have DreamBot scripts path configured";
                continue;
            }

            $start_queue->addMinutes($minutes);
            $account->start_queued_at = $start_queue;
            $account->status = 'Queued';
            $account->save();

            $queued_count++;
        }

        if ($queued_count == 1) {
            $response = $response->with('status', '1 account has been queued to start');
        }

        if ($queued_count > 1) {
            $response = $response->with('status', "$queued_count accounts have been queued to start");
        }

        return $response->withErrors($errors);
    }

    public function dequeue(AccountGroup $group): RedirectResponse
    {
        $this->authorize('view', $group);
        $accounts = $group->accounts()->where('status', 'Queued')->get();

        $response = back();
        $queued_count = 0;

        foreach ($accounts as $account) {
            $account->status = 'Stopped';
            $account->start_queued_at = null;
            $account->save();
            $queued_count++;
        }

        if ($queued_count == 0) {
            return $response->withErrors('No accounts are queued');
        }

        if ($queued_count == 1) {
            return $response->with('status', '1 account has been dequeued');
        }

        return $response->with('status', "$queued_count accounts have been dequeued");
    }

    public function export(AccountGroup $group)
    {
        $this->authorize('view', $group);

        $accounts = $group->accounts()
            ->select('email', 'password', 'password_2fa')
            ->get();

        if ($accounts->isEmpty()) {
            return back()->withErrors('No accounts in group');
        }

        return response()->streamDownload(function () use ($accounts) {
            $file = fopen('php://output', 'w');
            foreach ($accounts as $account) {
                $fields = array_filter($account->attributesToArray(), function ($field) {
                    return $field !== null;
                });
                fputcsv($file, $fields, ':');
            }

            fclose($file);
        }, 'account_group_' . $group->id . '_'.now()->unix().'.csv');
    }

    public function schedule(AccountGroup $group): View
    {
        $this->authorize('view', $group);

        $events = $group->schedule_events()->get();

        foreach ($events as $event) {
            $startMinutes = intval($event->start_at->format('G')) * 60 + intval($event->start_at->format('i'));
            $finishMinutes = intval($event->finish_at->format('G')) * 60 + intval($event->finish_at->format('i'));
            $durationMinutes = $finishMinutes - $startMinutes;
            $event->start = $startMinutes * 12 / 60;
            $event->duration = (int) ceil($durationMinutes / 6 + (ceil($durationMinutes / 32)));
        }

        $events = $events->map(function ($event) {
            return [
                'id' => $event->id,
                'name' => $event->name,
                'color' => $event->color,
                'day' => $event->day,
                'start' => $event->start,
                'duration' => $event->duration,
                'url' => route('account.group.schedule.event.show', ['group' => $event->account_group, 'event' => $event]),
            ];
        });

        return view('v1.account.group.schedule', ['group' => $group, 'events' => $events]);
    }

    public function schedule_create_event(AccountGroup $group): View
    {
        $this->authorize('view', $group);

        return view('v1.account.group.schedule.create', ['group' => $group]);
    }

    public function schedule_create_event_submit(AccountGroup $group): RedirectResponse
    {
        $this->authorize('view', $group);

        $this->validate(request(), [
            'name' => 'required',
            'color' => 'required|in:red,green,blue,pink,purple,yellow,orange',
            'day' => 'required|int|between:1,7',
            'start_time' => 'required|date_format:H:i',
            'finish_time' => 'required|date_format:H:i',
        ]);

        $withinRange = $group->schedule_events()
            ->where(function ($query) {
                $query->where('start_at', '<', now()->setTimeFromTimeString(request('start_time')))
                    ->where('finish_at', '>', now()->setTimeFromTimeString(request('start_time')))->where('day', request('day'));
            })->orWhere(function ($query) {
                $query->where('start_at', '<', now()->setTimeFromTimeString(request('finish_time')))
                    ->where('finish_at', '>', now()->setTimeFromTimeString(request('finish_time')))->where('day', request('day'));
            })->exists();

        if ($withinRange) {
            return back()->withErrors('You have an event within this time range.');
        }

        if (now()->setTimeFromTimeString(request('start_time')) > now()->setTimeFromTimeString(request('finish_time'))) {
            return back()->withErrors('Start time must be before finish time');
        }

        $group->schedule_events()->create([
            'name' => request('name'),
            'color' => request('color'),
            'day' => request('day'),
            'action' => request('action') ?? 'test',
            'data' => request('data') ?? [],
            'start_at' => now()->setTimeFromTimeString(request('start_time')),
            'finish_at' => now()->setTimeFromTimeString(request('finish_time')),
        ]);

        return redirect(route('account.group.schedule', $group))->with('status', 'Schedule event created');
    }

    public function schedule_event(AccountGroup $group, ScheduleEvent $event): View|RedirectResponse
    {
        $this->authorize('view', $group);

        return view('v1.account.group.schedule.show', compact('group', 'event'));
    }

    public function schedule_event_update(AccountGroup $group, ScheduleEvent $event): View|RedirectResponse
    {
        $this->authorize('view', $group);

        $validated = $this->validate(request(), [
            'name' => 'required',
            'color' => 'required|in:red,green,blue,pink,purple,yellow,orange',
            'day' => 'required|int|between:1,7',
            'start_time' => 'required|date_format:H:i',
            'finish_time' => 'required|date_format:H:i',
        ]);

        $validated = collect($validated);

        $withinRange = $group->schedule_events()
            ->where(function ($query) use($event) {
                $query->where('start_at', '<', now()->setTimeFromTimeString(request('start_time')))
                    ->where('finish_at', '>', now()->setTimeFromTimeString(request('start_time')))
                    ->where('day', request('day'))
                    ->whereNot('id', $event->id);
            })->orWhere(function ($query) use($event) {
                $query->where('start_at', '<', now()->setTimeFromTimeString(request('finish_time')))
                    ->where('finish_at', '>', now()->setTimeFromTimeString(request('finish_time')))
                    ->where('day', request('day'))
                    ->whereNot('id', $event->id);
            })->exists();

        if ($withinRange) {
            return back()->withErrors('You have an event within this time range.');
        }

        if (now()->setTimeFromTimeString(request('start_time')) > now()->setTimeFromTimeString(request('finish_time'))) {
            return back()->withErrors('Start time must be before finish time');
        }

        $event->update([...$validated->except('start_time', 'finish_time'),
            'start_at' => now()->setTimeFromTimeString(request('start_time')),
            'finish_at' => now()->setTimeFromTimeString(request('finish_time')),
        ]);

        return back()->with('status', 'Schedule event updated');
    }

    public function schedule_event_destroy(AccountGroup $group, ScheduleEvent $event): RedirectResponse
    {
        $this->authorize('view', $group);

        $event->delete();

        return redirect(route('account.group.schedule', $group))->with('status', 'Schedule event deleted');
    }
}
