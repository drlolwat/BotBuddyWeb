<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Proxy;
use App\Models\ProxyGroup;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProxyController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index()
    {
        $proxies = auth()->user()
            ->proxies()
            ->with('proxy_group')
            ->withCount('accounts')
            ->paginate(10);

        return view('v1.proxy.index', compact('proxies'));
    }

    public function show(Proxy $proxy)
    {
        $this->authorize('view', $proxy);

        $accounts = $proxy->accounts()->with('agent', 'script')->paginate(10);

        return view('v1.proxy.show', compact('proxy', 'accounts'));
    }

    public function create()
    {
        return view('v1.proxy.create');
    }

    public function store(Request $request)
    {
        if ($request['proxy_group_id'] == "0") {
            $request['proxy_group_id'] = null;
        }

        $validated = $this->validate($request, [
            'host' => 'required',
            'port' => 'required|int',
            'username' => 'nullable',
            'password' => 'nullable',
            'proxy_group_id' => [
                'nullable',
                'integer',
                Rule::exists('proxy_groups', 'id')
                    ->where(function ($query) {
                        $query->where('user_id', auth()->id());
                    }),
            ],
        ]);

        $account = Proxy::create([
            'host' => $validated['host'],
            'port' => $validated['port'],
            'username' => $validated['username'] ?? null,
            'password' => $validated['password'] ?? null,
            'proxy_group_id' => $validated['proxy_group_id'] ?? null,
            'user_id' => auth()->id(),
        ]);

        return redirect(route('proxy.show', $account))->with('status', 'Proxy added');
    }

    public function update(Request $request, Proxy $proxy)
    {
        $this->authorize('view', $proxy);

        if ($request['proxy_group_id'] == "0") {
            $request['proxy_group_id'] = null;
        }

        $validated = $this->validate($request, [
            'host' => 'required',
            'port' => 'required|int',
            'username' => 'nullable',
            'password' => 'nullable',
            'proxy_group_id' => [
                'nullable',
                'integer',
                Rule::exists('proxy_groups', 'id')
                    ->where(function ($query) {
                        $query->where('user_id', auth()->id());
                    }),
            ],
        ]);

        $proxy->update([
            'host' => $validated['host'],
            'port' => $validated['port'],
            'username' => $validated['username'] ?? null,
            'password' => $validated['password'] ?? null,
            'proxy_group_id' => $validated['proxy_group_id'] ?? null,
        ]);

        return redirect(route('proxy.show', $proxy))->with('status', 'Proxy updated');
    }

    public function destroy(Proxy $proxy)
    {
        $this->authorize('view', $proxy);

        $groupInUse = Account::where('proxy_id', $proxy->id)->count();

        if ($groupInUse > 0) {
            return redirect(route('proxy.show', $proxy))->withErrors(['Cannot delete proxy as it is in use']);
        }

        $proxy->delete();

        return redirect(route('proxy'))->with('status', 'Proxy deleted');
    }

    public function import()
    {
        return view('v1.proxy.import');
    }

    public function importStore(Request $request)
    {
        if ($request['proxy_group_id'] == "0") {
            $request['proxy_group_id'] = null;
        }

        $validated = $this->validate($request, [
            'proxy_file' => 'nullable|file|mimes:txt',
            'proxy_textarea' => 'nullable|string',
            'proxy_group_id' => [
                'nullable',
                Rule::exists('proxy_groups', 'id')
                    ->where(function ($query) {
                        $query->where('user_id', auth()->id());
                    }),
            ],
        ]);

        $proxyGroup = ProxyGroup::find($validated['proxy_group_id']);

        $linesFile = [];
        $linesTextarea = [];

        if (isset($validated['proxy_file'])) {
            $linesFile = explode("\n", file_get_contents($validated['proxy_file']));
        }
        if (isset($validated['proxy_textarea'])) {
            $linesTextarea = explode("\n", $validated['proxy_textarea']);
        }

        $lines = array_merge($linesFile, $linesTextarea);

        $accepted = [];
        $failed = 0;

        foreach($lines as $line) {
            $proxy = explode(",", $line);
            if (count($proxy) != 3 && count($proxy) != 1) {
                $failed++;
                continue;
            }
            $hostParts = explode(":", $proxy[0]);
            if (count($hostParts) != 2) {
                $failed++;
                continue;
            }
            if ($hostParts[1] < 1 || $hostParts[1] > 65535) {
                $failed++;
                continue;
            }
            if ($hostParts[0] == "") {
                $failed++;
                continue;
            }
            $accepted[] = $proxy;
        }

        foreach ($accepted as $proxy) {
            [$host, $port] = explode(":", $proxy[0]);

            if (count($proxy) == 3) {
                Proxy::create([
                    'host' => $host,
                    'port' => $port,
                    'username' => $proxy[1],
                    'password' => $proxy[2],
                    'proxy_group_id' => $proxyGroup?->id ?? null,
                    'user_id' => auth()->id(),
                ]);
            } else if (count($proxy) == 1) {
                Proxy::create([
                    'host' => $host,
                    'port' => $port,
                    'proxy_group_id' => $proxyGroup?->id ?? null,
                    'user_id' => auth()->id(),
                ]);
            }
        }

        $response = back();

        if (count($accepted) == 0) {
            return $response->withErrors(['No valid proxies found']);
        }

        if ($failed > 0) {
            $response = $response->withErrors(["Failed to import $failed proxies"]);
        }

        if (count($accepted) == 1) {
            return $response->with('status', '1 proxy imported');
        }

        return $response->with('status', sprintf("%d proxies imported", count($accepted)));
    }
}
