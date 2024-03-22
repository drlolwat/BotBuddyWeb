<?php

namespace App\Http\Controllers;

use App\BotBuddy\Socket\Commands\StartBotCommand;
use App\BotBuddy\Socket\Commands\StopBotCommand;
use App\BotBuddy\Socket\SocketService;
use App\Models\Account;
use App\Models\AccountGroup;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AccountGroupController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index()
    {
        $accountGroups = AccountGroup::query()
            ->with('script')
            ->where('user_id', auth()->id())
            ->withCount('accounts')
            ->paginate(10);

        return view('v1.account.group.index', compact('accountGroups'));
    }

    public function show(AccountGroup $group)
    {
        $this->authorize('view', $group);

        $accounts = $group->accounts()->with('agent', 'script', 'stats')->paginate(10);

        return view('v1.account.group.show', compact('group', 'accounts'));
    }

    public function create()
    {
        return view('v1.account.group.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validate($request, [
            'name' => 'required',
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
            'script_id' => $validated['script_id'],
            'script_params' => $validated['script_params'] ?? null,
            'fps' => $validated['fps'],
            'world' => $validated['world'],
        ]);

        return redirect(route('account.group.show', $group))->with('status', 'Account group created');
    }

    public function update(Request $request, AccountGroup $group)
    {
        $this->authorize('view', $group);

        $validated = $this->validate($request, [
            'name' => 'required',
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
            'script_id' => $validated['script_id'],
            'script_params' => $validated['script_params'] ?? null,
            'fps' => $validated['fps'],
            'world' => $validated['world'],
        ]);

        return redirect(route('account.group.show', $group))->with('status', 'Account group updated');
    }

    public function destroy(AccountGroup $group)
    {
        $this->authorize('view', $group);

        $groupInUse = Account::where('account_group_id', $group->id)->count();

        if ($groupInUse > 0) {
            return redirect(route('account.group.show', $group))->withErrors(['Cannot delete account group as it is in use']);
        }

        $group->delete();

        return redirect(route('account'))->with('status', 'Account group deleted');
    }

    public function start(AccountGroup $group, SocketService $socket)
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
            if(!$account->agent) {
                $errors[] = "$account->email is not assigned to an agent";
                continue;
            }

            if(!$account->script) {
                $errors[] = "$account->email does not have a script assigned";
                continue;
            }

            if ($account->agent->client_type != 'DreamBot') {
                $errors[] = "Agent \"{$account->agent->name}\" is not using DreamBot client";
                continue;
            }

            if (!$account->agent->dreambot_client_path) {
                $errors[] = "Agent \"{$account->agent->name}\" does not have DreamBot client.jar path configured";
                continue;
            }

            if (!$account->agent->dreambot_scripts_path) {
                $errors[] = "Agent \"{$account->agent->name}\" does not have DreamBot scripts path configured";
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

    public function stop(AccountGroup $group, SocketService $socket)
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

    public function queue(AccountGroup $group)
    {
        $this->authorize('view', $group);

        $minutesValidated = request()->validate([
            'minutes' => 'required|int|min:1|max:120',
        ]);
        $minutes = $minutesValidated['minutes'];

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
            if(!$account->agent) {
                $errors[] = "$account->email is not assigned to an agent";
                continue;
            }

            if(!$account->script) {
                $errors[] = "$account->email does not have a script assigned";
                continue;
            }

            if ($account->agent->client_type != 'DreamBot') {
                $errors[] = "Agent \"{$account->agent->name}\" is not using DreamBot client";
                continue;
            }

            if (!$account->agent->dreambot_client_path) {
                $errors[] = "Agent \"{$account->agent->name}\" does not have DreamBot client.jar path configured";
                continue;
            }

            if (!$account->agent->dreambot_scripts_path) {
                $errors[] = "Agent \"{$account->agent->name}\" does not have DreamBot scripts path configured";
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

    public function dequeue(AccountGroup $group)
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
}
