<?php

namespace App\Http\Controllers;

use App\BotBuddy\Socket\Commands\StartBotCommand;
use App\BotBuddy\Socket\Commands\StopBotCommand;
use App\BotBuddy\Socket\SocketService;
use App\BotBuddy\Status;
use App\Models\Account;
use App\Models\AccountGroup;
use App\Models\ScheduleEvent;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

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


    private function countIntervals(Carbon $start, Carbon $finish): int
    {
        $diffInMinutes = (int) $start->diffInMinutes($finish);
        return intdiv($diffInMinutes, 30);
    }


    public function show(AccountGroup $group): View|RedirectResponse
    {
        $this->authorize('view', $group);

        $accounts = $group->accounts()->with('agent', 'script', 'stats')->paginate(10);

        $events = $group->schedule_events;

        foreach ($events as $event) {
            $startMinutes = intval($event->start_at->format('G')) * 60 + intval($event->start_at->format('i'));
            $finishMinutes = intval($event->finish_at->format('G')) * 60 + intval($event->finish_at->format('i'));
            $durationMinutes = $finishMinutes - $startMinutes;
            $event->start = $startMinutes * 12 / 60;
            $event->duration = (int) ceil($durationMinutes / 6 + (ceil($durationMinutes / 32)));
        }

        if ($events->isNotEmpty()) {
            $events = $events->map(function ($event) {
                return [
                    'id' => $event->id,
                    'name' => $event->name,
                    'color' => $event->color,
                    'day' => $event->day,
                    'start' => $event->start,
                    'start_formatted' => $event->start_at->format('h:i A'),
                    'finish_formatted' => $event->finish_at->format('h:i A'),
                    'duration' => $this->countIntervals($event->start_at, $event->finish_at),
                    'url' => route('account.group.schedule.event.show', ['group' => $event->account_group, 'event' => $event]),
                ];
            });
        }

        return view('v1.account.group.show', compact('group', 'accounts', 'events'));
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

            'disable_browser_proxy' => 'nullable',

            'db_debug' => 'nullable',
            'db_disable_animations' => 'nullable',
            'db_disable_models' => 'nullable',
            'db_disable_sounds' => 'nullable',
            'db_dismiss_random_events' => 'nullable',
            'db_low_detail' => 'nullable',
            'db_menu_manipulation' => 'nullable',
            'db_no_click_walk' => 'nullable',
            'db_minimized' => 'nullable',
            'db_beta' => 'nullable',
            'db_render' => 'required|string',
        ]);

        if (!in_array($validated['db_render'], ['all', 'script', 'none'])) {
            return back()->withErrors('Invalid render mode');
        }

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
            'disable_browser_proxy' => isset($validated['disable_browser_proxy']) && $validated['disable_browser_proxy'] == "on",
            'db_debug' => isset($validated['db_debug']) && $validated['db_debug'] == "on",
            'db_disable_animations' => isset($validated['db_disable_animations']) && $validated['db_disable_animations'] == "on",
            'db_disable_models' => isset($validated['db_disable_models']) && $validated['db_disable_models'] == "on",
            'db_disable_sounds' => isset($validated['db_disable_sounds']) && $validated['db_disable_sounds'] == "on",
            'db_dismiss_random_events' => isset($validated['db_dismiss_random_events']) && $validated['db_dismiss_random_events'] == "on",
            'db_low_detail' => isset($validated['db_low_detail']) && $validated['db_low_detail'] == "on",
            'db_menu_manipulation' => isset($validated['db_menu_manipulation']) && $validated['db_menu_manipulation'] == "on",
            'db_no_click_walk' => isset($validated['db_no_click_walk']) && $validated['db_no_click_walk'] == "on",
            'db_minimized' => isset($validated['db_minimized']) && $validated['db_minimized'] == "on",
            'db_beta' => isset($validated['db_beta']) && $validated['db_beta'] == "on" && in_array(auth()->user()->subscription->name, ['Farm', 'Founder']),
            'db_render' => $validated['db_render'],
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

            'disable_browser_proxy' => 'nullable',

            'db_debug' => 'nullable',
            'db_disable_animations' => 'nullable',
            'db_disable_models' => 'nullable',
            'db_disable_sounds' => 'nullable',
            'db_dismiss_random_events' => 'nullable',
            'db_low_detail' => 'nullable',
            'db_menu_manipulation' => 'nullable',
            'db_no_click_walk' => 'nullable',
            'db_minimized' => 'nullable',
            'db_beta' => 'nullable',
            'db_render' => 'required|string',
        ]);

        if (!in_array($validated['db_render'], ['all', 'script', 'none'])) {
            return back()->withErrors('Invalid render mode');
        }

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
            'disable_browser_proxy' => isset($validated['disable_browser_proxy']) && $validated['disable_browser_proxy'] == "on",
            'db_debug' => isset($validated['db_debug']) && $validated['db_debug'] == "on",
            'db_disable_animations' => isset($validated['db_disable_animations']) && $validated['db_disable_animations'] == "on",
            'db_disable_models' => isset($validated['db_disable_models']) && $validated['db_disable_models'] == "on",
            'db_disable_sounds' => isset($validated['db_disable_sounds']) && $validated['db_disable_sounds'] == "on",
            'db_dismiss_random_events' => isset($validated['db_dismiss_random_events']) && $validated['db_dismiss_random_events'] == "on",
            'db_low_detail' => isset($validated['db_low_detail']) && $validated['db_low_detail'] == "on",
            'db_menu_manipulation' => isset($validated['db_menu_manipulation']) && $validated['db_menu_manipulation'] == "on",
            'db_no_click_walk' => isset($validated['db_no_click_walk']) && $validated['db_no_click_walk'] == "on",
            'db_minimized' => isset($validated['db_minimized']) && $validated['db_minimized'] == "on",
            'db_beta' => isset($validated['db_beta']) && $validated['db_beta'] == "on" && in_array(auth()->user()->subscription->name, ['Farm', 'Founder']),
            'db_render' => $validated['db_render'],
        ]);

        return redirect(route('account.group.show', $group))->with('status', 'Account group updated');
    }

    public function destroy(AccountGroup $group): RedirectResponse
    {
        $this->authorize('view', $group);

        $group->schedule_events()->delete(); // no soft delete setup for schedule events
        $group->accounts()->update(['deleted_at' => now()]); // soft delete query

        $group->delete();

        return redirect(route('account.group'))->with('status', 'Account group deleted');
    }

    public function start(AccountGroup $group, SocketService $socket): RedirectResponse
    {
        $this->authorize('view', $group);

        $accounts = $group->accounts()
            ->with('agent', 'script')
            ->whereIn('status', [Status::STOPPED, Status::QUEUED])->get();
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

            $account->status = Status::STARTING;
            $account->start_queued_at = null; // in case it was formerly queued
            $account->last_started_at = now();
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
            Status::RUNNING, Status::STARTING, Status::COMPLETED,
            Status::NO_SCRIPT, Status::PROXY_BLOCKED, Status::BANNED,
        ];

        $this->authorize('view', $group);
        $accounts = $group->accounts()
            ->whereIn('status', $statuses)
            ->get();

        $stop_count = 0;

        foreach ($accounts as $account) {

            // edge case for people who have removed the agent for some reason
            if (!isset($this->account->account_group->agent->uuid)) {
                $account->status = Status::STOPPED;
                $account->save();
                $stop_count++;
                continue;
            }
            $stopped = $socket->dispatch(new StopBotCommand($account));

            if ($stopped != "true") {
                continue;
            }

            $account->status = Status::STOPPING;
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
            ->whereIn('status', [Status::STOPPED, Status::STOPPING])->get();

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
            $account->status = Status::QUEUED;
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
        $updated = $group->accounts()
            ->where('status', Status::QUEUED)
            ->update([
                'status' => Status::STOPPED,
                'start_queued_at' => null,
            ]);

        $response = back();

        if ($updated == 0) {
            return $response->withErrors('No accounts are queued');
        }

        if ($updated == 1) {
            return $response->with('status', '1 account has been dequeued');
        }

        return $response->with('status', "$updated accounts have been dequeued");
    }

    public function change_proxy(AccountGroup $group): RedirectResponse {
        $this->authorize('view', $group);

        $req = request()->all()['change_proxy'] ?? null;
        if (!$req) {
            return back()->withErrors('Invalid payload');
        }
        $proxyGroup = auth()->user()->proxy_groups()->find($req['proxy_group_id']);
        if (!$proxyGroup) {
            return back()->withErrors('Proxy group not found');
        }
        $success = 0;
        $fail = 0;
        foreach ($group->accounts as $account) {
            $query = $proxyGroup->proxies();

            switch($req['type']) {
                case 'random':
                    break;
                case 'random_unused':
                    $query->whereDoesntHave('accounts')->where('id', '!=', $account->proxy_id);
                    break;
            }

            $proxy = $query->inRandomOrder()->first();

            if (!$proxy) {
                $fail++;
                continue;
            }

            $success++;

            $account->proxy_id = $proxy->id;
            $account->save();
        }

        $response = back();

        if ($success > 0) {
            $response->with('status', $success.' accounts have been updated');
        }

        if ($fail > 0) {
            $response->withErrors($fail.' accounts could not be updated');
        }

        return $response;
    }

    public function remove_proxy(AccountGroup $group): RedirectResponse
    {
        $this->authorize('view', $group);

        foreach ($group->accounts as $account) {
            $account->proxy_id = null;
            $account->save();
        }
        return back()->with('status', 'Proxies removed');
    }

    public function export(AccountGroup $group): RedirectResponse|StreamedResponse
    {
        $this->authorize('view', $group);

        $accounts = $group->accounts()
            ->select('email', 'password', 'password_2fa')
            ->get();

        if ($accounts->isEmpty()) {
            return back()->withErrors('No accounts in group');
        }

        return response()->streamDownload(function () use ($accounts) {
            /** @var resource $file */
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

    public function schedule_event(AccountGroup $group, ScheduleEvent $event): View
    {
        $this->authorize('view', $group);

        return view('v1.account.group.schedule.show', ['group' => $group, 'event' => $event]);
    }

    public function schedule_create_event(AccountGroup $group): View
    {
        $this->authorize('view', $group);

        return view('v1.account.group.schedule.create', ['group' => $group]);
    }

    public function schedule_create_event_submit(AccountGroup $group): RedirectResponse
    {
        $this->authorize('view', $group);

        $validated = $this->validate(request(), [
            'name' => 'required',
            'color' => 'required|in:red,green,blue,pink,purple,yellow,orange',
            'days' => 'required|array|max:7',
            'days.*' => 'int|between:1,7',
            'start_time' => 'required|date_format:H:i',
            'finish_time' => 'required|date_format:H:i',
            'script_id' => [
                'required',
                'integer',
                Rule::exists('user_scripts', 'id')
                    ->where(function ($query) {
                        $query->where('user_id', auth()->id());
                    }),
            ],
            'script_params' => 'nullable|string',
        ]);

        $errors = [];
        $status = [];

        foreach ($validated['days'] as $day) {
            if (now()->setTimeFromTimeString(request('start_time')) == now()->setTimeFromTimeString(request('finish_time'))) {
                return back()->withErrors('Start time must be before finish time');
            }

            if (now()->setTimeFromTimeString(request('start_time')) > now()->setTimeFromTimeString(request('finish_time'))) {
                return back()->withErrors('Start time must be before finish time');
            }

            $withinRange = $group->schedule_events()
                ->where(function ($query) use($day) {
                    $query->where('start_at', '<', now()->setTimeFromTimeString(request('start_time')))
                        ->where('finish_at', '>', now()->setTimeFromTimeString(request('start_time')))->where('day', $day);
                })->orWhere(function ($query) use($day) {
                    $query->where('start_at', '<', now()->setTimeFromTimeString(request('finish_time')))
                        ->where('finish_at', '>', now()->setTimeFromTimeString(request('finish_time')))->where('day', $day);
                })->exists();

            if ($withinRange) {
                $errors[] = "You have an event within this time range on day $day";
            }

            $group->schedule_events()->create([
                'name' => request('name'),
                'color' => request('color'),
                'day' => $day,
                'script_id' => request('script_id'),
                'script_params' => request('script_params') ?? null,
                'data' => request('data') ?? [],
                'start_at' => now()->setTimeFromTimeString(request('start_time')),
                'finish_at' => now()->setTimeFromTimeString(request('finish_time')),
            ]);

            $status[] = $day;
        }

        $response = redirect(route('account.group.show', $group));

        if (count($errors) > 0) {
            $response = $response->withErrors($errors);
        }

        if (count($status) > 0) {
            $response = $response->with('status', 'Schedule events created for days: ' . implode(', ', $status));
        }

        return $response;
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
            'script_id' => [
                'required',
                'integer',
                Rule::exists('user_scripts', 'id')
                    ->where(function ($query) {
                        $query->where('user_id', auth()->id());
                    }),
            ],
            'script_params' => 'nullable|string',
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

        $event->update([...$validated->except('start_time', 'finish_time', 'script_params'),
            'start_at' => now()->setTimeFromTimeString(request('start_time')),
            'finish_at' => now()->setTimeFromTimeString(request('finish_time')),
            'script_params' => $validated['script_params'] ?? null,
        ]);

        return back()->with('status', 'Schedule event updated');
    }

    public function schedule_event_destroy(AccountGroup $group, ScheduleEvent $event): RedirectResponse
    {
        $this->authorize('view', $group);

        $event->delete();

        return redirect(route('account.group.show', $group))->with('status', 'Schedule event deleted');
    }

    public function delete_confirm(AccountGroup $group): View
    {
        $this->authorize('view', $group);

        $group->loadCount('accounts', 'schedule_events');

        return view('v1.account.group.delete_confirm', compact('group'));
    }
}
