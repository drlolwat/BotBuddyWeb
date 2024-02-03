<?php

namespace App\Http\Controllers;

use App\BotBuddy\Socket\Commands\StartBotCommand;
use App\BotBuddy\Socket\Commands\StopBotCommand;
use App\BotBuddy\Socket\SocketService;
use App\Models\Account;
use App\Models\AccountGroup;
use App\Models\Proxy;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index()
    {
        $accounts = auth()->user()
            ->accounts()
            ->with('account_group', 'account_group.script', 'proxy', 'script', 'agent')
            ->paginate(25);

        return view('v1.account.index', compact('accounts'));
    }

    public function show(Account $account)
    {
        return view('v1.account.show', compact('account'));
    }

    public function create()
    {
        return view('v1.account.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validate($request, [
            'email' => 'required',
            'password' => 'required',
            'password_2fa' => 'nullable',
            'account_group_id' => 'nullable',
            'proxy_id' => 'nullable',
            'script_id' => 'required',
            'script_params' => 'nullable',
            'agent_id' => 'nullable',
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
        $validated = $this->validate($request, [
            'email' => 'required',
            'password' => 'required',
            'password_2fa' => 'nullable',
            'account_group_id' => 'nullable',
            'proxy_id' => 'nullable',
            'script_id' => 'required',
            'script_params' => 'nullable',
            'agent_id' => 'nullable',
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
        if ($account->status == 'Starting' || $account->status == 'Running' || $account->status == 'Stopping') {
            return back()->withErrors('Account is currently running');
        }

        $account->delete();

        return redirect(route('account'))->with('status', 'Account deleted');
    }

    public function start(Account $account, SocketService $socket)
    {
        if(!$account->agent) {
            return back()->withErrors('Account is not assigned to an agent');
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

        $user = auth()->user();

        if (!$user->dreambot_username || !$user->dreambot_password) {
            return redirect(route('settings'))
                ->withErrors(['dreambot_username' => 'Please configure your DreamBot credentials and client.jar to start an account']);
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
        $validated = $this->validate($request, [
            'account_file' => 'nullable|file|mimes:txt',
            'account_textarea' => 'nullable|string',
            'account_group_id' => 'required',
            'proxy_id' => 'nullable',
            'agent_id' => 'nullable',
        ]);

        $accountGroup = AccountGroup::find($validated['account_group_id']);

        if (!$accountGroup) {
            return back()->withErrors('Account group does not exist');
        }

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

        foreach($lines as $line) {
            $line = trim($line);

            if(empty($line)) {
                continue;
            }

            $parts = explode(":", $line);

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
                $accounts[] = [
                    'account_email' => $parts[0],
                    'account_password' => $parts[1],
                    'proxy_host' => $parts[2],
                    'proxy_port' => $parts[3],
                    'proxy_username' => $parts[4],
                    'proxy_password' => $parts[5],
                ];
            }

            if (count($parts) == 7) {
                $accounts[] = [
                    'account_email' => $parts[0],
                    'account_password' => $parts[1],
                    'account_2fa_password' => $parts[2],
                    'proxy_host' => $parts[3],
                    'proxy_port' => $parts[4],
                    'proxy_username' => $parts[5],
                    'proxy_password' => $parts[6],
                ];
            }
        }

        foreach ($accounts as $account) {

            $newProxy = null;

            if (count($account) > 3) {

                $newProxy = Proxy::select('id')
                    ->where('user_id', auth()->id())
                    ->where('host', $account['proxy_host'])
                    ->where('port', $account['proxy_port'])
                    ->where('username', $account['proxy_username'])
                    ->where('password', $account['proxy_password'])
                    ->value('id');

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

            Account::create([
                'user_id' => auth()->id(),
                'email' => $account['account_email'],
                'password' => $account['account_password'],
                'password_2fa' => $account['account_2fa_password'] ?? null,
                'account_group_id' => $request->get('account_group_id'),
                'agent_id' => $request->get('agent_id'),
                'proxy_id' => $newProxy?->id ?? $request->get('proxy_id'),
                'script_id' => $accountGroup->script_id,
                'script_params' => $accountGroup->script_params,
                'fps' => $accountGroup->fps,
                'world' => $accountGroup->world,
            ]);
        }

        return redirect(route('account'))->with('status', 'Accounts imported');
    }
}
