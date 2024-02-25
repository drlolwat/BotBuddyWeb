<?php

namespace App\Http\Controllers;

use App\BotBuddy\Socket\Commands\StartBotCommand;
use App\BotBuddy\Socket\Commands\StopBotCommand;
use App\BotBuddy\Socket\SocketService;
use App\Models\Account;
use App\Models\AccountGroup;
use App\Models\Proxy;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index()
    {
        $accounts = auth()->user()
            ->accounts()
            ->with('account_group', 'account_group.script', 'proxy', 'script', 'agent', 'stats')
            ->paginate(25);

        return view('v1.account.index', compact('accounts'));
    }

    public function bulkAction(SocketService $socket)
    {
        $validated = request()->validate([
            'action' => 'required',
            'accounts' => 'required|array',
        ]);

        $accounts = Account::query()
            ->with('agent','script')
            ->whereIn('id', array_keys($validated['accounts']))
            ->where('user_id', auth()->id())
            ->get();

        if ($validated['action'] == 'start') {

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

                if ($account->status == 'Running') {
                    $errors[] = "$account->email is already running";
                    continue;
                }

                if ($account->status == 'Starting') {
                    $errors[] = "$account->email is already starting";
                    continue;
                }

                if ($account->status == 'Completed') {
                    $errors[] = "$account->email is already running";
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

        if ($validated['action'] == 'stop') {
            foreach ($accounts as $account) {
                if(!$account->agent) {
                    // skip this account, not assigned to agent
                    continue;
                }
                $stopped = $socket->dispatch(new StopBotCommand($account));

                if ($stopped != "true") {
                    continue;
                }

                $account->status = 'Stopping';
                $account->save();
            }

            return back()->with('status', 'Accounts are being stopped');
        }

        if ($validated['action'] == 'queue') {
            $minutesValidated = request()->validate([
                'minutes' => 'required|int|min:1|max:120',
            ]);
            $minutes = $minutesValidated['minutes'];

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

                if ($account->status == 'Running') {
                    $errors[] = "$account->email is already running";
                    continue;
                }

                if ($account->status == 'Starting') {
                    $errors[] = "$account->email is already starting";
                    continue;
                }

                if ($account->status == 'Queued') {
                    $errors[] = "$account->email is already queued";
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

        return back()->withErrors('Invalid action');
    }

    public function show(Account $account)
    {
        $this->authorize('view', $account);

        $chunkedSkills = [];

        if ($account->stats) {
            foreach ($account->stats->skills->chunk(2)->toArray() as $skills) {
                $chunk = [];
                foreach ($skills as $skillName => $skillLevel) {
                    $chunk[] = ['skill' => $skillName, 'level' => $skillLevel];
                }
                $chunkedSkills[] = $chunk;
            }
        }

        $proxies = auth()->user()->proxies()->withCount('accounts')->get();

        return view('v1.account.show', compact('account', 'chunkedSkills', 'proxies'));
    }

    public function create()
    {
        return view('v1.account.create');
    }

    public function store(Request $request)
    {
        if ($request['proxy_id'] == "0") {
            $request['proxy_id'] = null;
        }
        if ($request['agent_id'] == "0") {
            $request['agent_id'] = null;
        }
        if ($request['account_group_id'] == "0") {
            $request['account_group_id'] = null;
        }

        $validated = $this->validate($request, [
            'email' => 'required',
            'password' => 'required',
            'password_2fa' => 'nullable',
            'account_group_id' => [
                'nullable',
                Rule::exists('account_groups', 'id')
                    ->where(function ($query) {
                        $query->where('user_id', auth()->id());
                    }),
            ],
            'proxy_id' => [
                'nullable',
                Rule::exists('proxies', 'id')
                    ->where(function ($query) {
                        $query->where('user_id', auth()->id());
                    }),
            ],
            'script_id' => [
                'required',
                Rule::exists('user_scripts', 'id')
                    ->where(function ($query) {
                        $query->where('user_id', auth()->id());
                    }),
            ],
            'script_params' => 'nullable',
            'agent_id' => [
                'nullable',
                Rule::exists('agents', 'id')
                    ->where(function ($query) {
                        $query->where('user_id', auth()->id());
                    }),
            ],
            'fps' => 'required|int',
            'world' => 'required',
        ]);

        if (!($validated['world'] == 'f2p' || $validated['world'] == 'members' || is_int($validated['world']))) {
            return back()->withErrors('Invalid world provided');
        }

        $account = Account::create([
            'email' => $validated['email'],
            'password' => $validated['password'],
            'password_2fa' => $validated['password_2fa'] ?? null,
            'account_group_id' => $validated['account_group_id'] ?? null,
            'proxy_id' => $validated['proxy_id'],
            'script_id' => $validated['script_id'],
            'script_params' => $validated['script_params'] ?? null,
            'agent_id' => $validated['agent_id'] ?? null,
            'user_id' => auth()->id(),
            'fps' => $validated['fps'],
            'world' => $validated['world'],
        ]);

        return redirect(route('account.show', $account))->with('status', 'Account created');
    }

    public function update(Request $request, Account $account)
    {
        $this->authorize('view', $account);

        if ($request['proxy_id'] == "0") {
            $request['proxy_id'] = null;
        }
        if ($request['agent_id'] == "0") {
            $request['agent_id'] = null;
        }
        if ($request['account_group_id'] == "0") {
            $request['account_group_id'] = null;
        }

        $validated = $this->validate($request, [
            'email' => 'required',
            'password' => 'required',
            'password_2fa' => 'nullable',
            'account_group_id' => [
                'nullable',
                Rule::exists('account_groups', 'id')
                    ->where(function ($query) {
                        $query->where('user_id', auth()->id());
                    }),
            ],
            'proxy_id' => [
                'nullable',
                Rule::exists('proxies', 'id')
                    ->where(function ($query) {
                        $query->where('user_id', auth()->id());
                    }),
            ],
            'script_id' => [
                'required',
                Rule::exists('user_scripts', 'id')
                    ->where(function ($query) {
                        $query->where('user_id', auth()->id());
                    }),
            ],
            'script_params' => 'nullable',
            'agent_id' => [
                'nullable',
                Rule::exists('agents', 'id')
                    ->where(function ($query) {
                        $query->where('user_id', auth()->id());
                    }),
            ],
            'fps' => 'required|int',
            'world' => 'required',
        ]);

        if (!($validated['world'] == 'f2p' || $validated['world'] == 'members' || is_int($validated['world']))) {
            return back()->withErrors('Invalid world provided');
        }

        $account->update([
            'email' => $validated['email'],
            'password' => $validated['password'],
            'password_2fa' => $validated['password_2fa'] ?? null,
            'account_group_id' => $validated['account_group_id'] ?? null,
            'proxy_id' => $validated['proxy_id'],
            'script_id' => $validated['script_id'],
            'agent_id' => $validated['agent_id'] ?? null,
            'script_params' => $validated['script_params'] ?? null,
            'fps' => $validated['fps'],
            'world' => $validated['world'],
        ]);

        return redirect(route('account.show', $account))->with('status', 'Account updated');
    }

    public function destroy(Account $account)
    {
        $this->authorize('view', $account);

        if ($account->status == 'Starting' || $account->status == 'Running' || $account->status == 'Stopping') {
            return back()->withErrors('Account is currently running');
        }

        $account->delete();

        return redirect(route('account'))->with('status', 'Account deleted');
    }

    public function start(Account $account, SocketService $socket)
    {
        $this->authorize('view', $account);

        $user = auth()->user();

        if (!$user->dreambot_username || !$user->dreambot_password) {
            return redirect(route('settings'))
                ->withErrors(['dreambot_username' => 'Please configure your DreamBot credentials to start an account']);
        }

        if(!$account->agent) {
            return back()->withErrors('Account is not assigned to an agent');
        }

        if(!$account->script) {
            return back()->withErrors('Select a script for the account');
        }

        if ($account->agent->client_type != 'DreamBot') {
            return back()->withErrors('Only DreamBot clients are allowed at this stage');
        }

        if (!$account->agent->dreambot_client_path) {
            return back()->withErrors('Please configure the agent DreamBot client.jar path');
        }

        if (!$account->agent->dreambot_scripts_path) {
            return back()->withErrors('Please configure the agent DreamBot scripts path');
        }

        $started = $socket->dispatch(new StartBotCommand($account));

        if ($started != "true") {
            return back()->withErrors(['status' => 'Failed to start account']);
        }

        $account->status = 'Starting';
        $account->save();

        return back()->with('status', 'Account is being started');
    }

    public function stop(Account $account, SocketService $socket)
    {
        $this->authorize('view', $account);

        if(!$account->agent) {
            return back()->withErrors('Account is not assigned to an agent');
        }

        $stopped = $socket->dispatch(new StopBotCommand($account));

        if ($stopped != "true") {
            return redirect(route('account'))->withErrors(['status' => 'Failed to stop account']);
        }

        $account->status = 'Stopping';
        $account->save();

        return redirect(route('account'))->with('status', 'Account stopped');
    }

    public function import()
    {
        return view('v1.account.import');
    }

    public function importStore(Request $request)
    {
        if ($request['proxy_id'] == "0") {
            $request['proxy_id'] = null;
        }
        if ($request['agent_id'] == "0") {
            $request['agent_id'] = null;
        }

        $validated = $this->validate($request, [
            'account_file' => 'nullable|file|mimes:txt',
            'account_textarea' => 'nullable|string',
            'account_group_id' => [
                'required',
                Rule::exists('account_groups', 'id')
                    ->where(function ($query) {
                        $query->where('user_id', auth()->id());
                    }),
            ],
            'proxy_id' => [
                'nullable',
                Rule::exists('proxies', 'id')
                    ->where(function ($query) {
                        $query->where('user_id', auth()->id());
                    }),
            ],
            'agent_id' => [
                'nullable',
                Rule::exists('agents', 'id')
                    ->where(function ($query) {
                        $query->where('user_id', auth()->id());
                    }),
            ],
        ]);

        $accountGroup = AccountGroup::find($validated['account_group_id']);

        $linesFile = [];
        $linesTextarea = [];

        if (isset($validated['account_file'])) {
            $linesFile = explode("\n", file_get_contents($validated['account_file']));
        }
        if (isset($validated['account_textarea'])) {
            $linesTextarea = explode("\n", $validated['account_textarea']);
        }

        $lines = array_merge($linesFile, $linesTextarea);

        $accounts = [];
        $failed = 0;

        foreach($lines as $line) {
            $line = trim($line);

            if(empty($line)) {
                continue;
            }

            $parts = explode(":", $line);

            if (count($parts) < 2 || count($parts) > 7) {
                $failed++;
                continue;
            }

            if (count($parts) == 2) {
                $accounts[] = [
                    'account_email' => $parts[0],
                    'account_password' => $parts[1],
                ];
            }

            if (count($parts) == 3) {
                $accounts[] = [
                    'account_email' => $parts[0],
                    'account_password' => $parts[1],
                    'account_2fa_password' => $parts[2],
                ];
            }

            if (count($parts) == 6) {
                $parts[3] = (int)$parts[3];
                if ($parts[3] < 1 || $parts[3] > 65535) {
                    $failed++;
                    continue;
                }
                if ($parts[2] == "") {
                    $failed++;
                    continue;
                }
                $accounts[] = [
                    'account_email' => $parts[0],
                    'account_password' => $parts[1],
                    'proxy_host' => $parts[2],
                    'proxy_port' => (int)$parts[3],
                    'proxy_username' => $parts[4],
                    'proxy_password' => $parts[5],
                ];
            }

            if (count($parts) == 7) {
                if ($parts[4] < 1 || $parts[4] > 65535) {
                    $failed++;
                    continue;
                }
                if ($parts[3] == "") {
                    $failed++;
                    continue;
                }
                $accounts[] = [
                    'account_email' => $parts[0],
                    'account_password' => $parts[1],
                    'account_2fa_password' => $parts[2],
                    'proxy_host' => $parts[3],
                    'proxy_port' => (int)$parts[4],
                    'proxy_username' => $parts[5],
                    'proxy_password' => $parts[6],
                ];
            }
        }

        $accountsToImport = [];

        foreach ($accounts as $account) {

            $newProxy = null;

            // todo: fix N+1 for proxy queries
            if (count($account) > 3) {

                $newProxy = Proxy::select('id')
                    ->where('user_id', auth()->id())
                    ->where('host', $account['proxy_host'])
                    ->where('port', $account['proxy_port'])
                    ->where('username', $account['proxy_username'])
                    ->where('password', $account['proxy_password'])
                    ->first();

                if (!$newProxy) {
                    $newProxy = Proxy::create([
                        'user_id' => auth()->id(),
                        'host' => $account['proxy_host'],
                        'port' => $account['proxy_port'],
                        'username' => $account['proxy_username'],
                        'password' => $account['proxy_password'],
                    ]);
                }
            }

            $accountsToImport[] = [
                'user_id' => auth()->id(),
                'email' => $account['account_email'],
                'password' => $account['account_password'],
                'password_2fa' => $account['account_2fa_password'] ?? null,
                'account_group_id' => $request->get('account_group_id') ?? null,
                'agent_id' => $request->get('agent_id'),
                'proxy_id' => $newProxy?->id ?? $request->get('proxy_id'),
                'script_id' => $accountGroup->script_id,
                'script_params' => $accountGroup->script_params,
                'fps' => $accountGroup->fps,
                'world' => $accountGroup->world,
            ];
        }

        $batches = array_chunk($accountsToImport, 100);

        foreach ($batches as $batch) {
            Account::insert($batch);
        }

        $response = back();

        if (count($accounts) == 0) {
            return $response->withErrors(['No valid accounts found']);
        }

        if ($failed > 0) {
            $response = $response->withErrors(["Failed to import $failed accounts"]);
        }

        if (count($accounts) == 1) {
            return $response->with('status', '1 account imported');
        }

        return $response->with('status', sprintf("%d accounts imported", count($accounts)));
    }

    public function dequeue(Account $account)
    {
        $this->authorize('view', $account);

        if ($account->status != 'Queued') {
            return back()->withErrors('Account is not queued');
        }

        $account->status = 'Stopped';
        $account->start_queued_at = null;
        $account->save();

        return back()->with('status', 'Account is no longer queued');
    }
}
