<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Proxy;
use App\Models\ProxyGroup;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProxyController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(): View
    {
        $proxies = auth()->user()
            ->proxies()
            ->with('proxy_group')
            ->withCount('accounts')
            ->paginate(10);

        return view('v1.proxy.index', compact('proxies'));
    }

    public function show(Proxy $proxy): View|RedirectResponse
    {
        $this->authorize('view', $proxy);

        $accounts = $proxy->accounts()->with('agent', 'script')->paginate(10);

        return view('v1.proxy.show', compact('proxy', 'accounts'));
    }

    public function create(): View
    {
        return view('v1.proxy.create');
    }

    public function store(Request $request): RedirectResponse
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

    public function update(Request $request, Proxy $proxy): RedirectResponse
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

    public function destroy(Proxy $proxy): RedirectResponse
    {
        $this->authorize('view', $proxy);

        $groupInUse = Account::where('proxy_id', $proxy->id)->count();

        if ($groupInUse > 0) {
            return redirect(route('proxy.show', $proxy))->withErrors(['Cannot delete proxy as it is in use']);
        }

        $proxy->delete();

        return redirect(route('proxy'))->with('status', 'Proxy deleted');
    }

    public function import(): View
    {
        return view('v1.proxy.import');
    }

    public function importStore(Request $request): RedirectResponse
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

        if (isset($validated['proxy_file']) && $file = file_get_contents($validated['proxy_file'])) {
            $linesFile = explode("\n", $file);
        }
        if (isset($validated['proxy_textarea'])) {
            $linesTextarea = explode("\n", $validated['proxy_textarea']);
        }

        $lines = array_merge($linesFile, $linesTextarea);

        $lines = array_map(function($line) {
            return rtrim($line, "\r");
        }, $lines);

        $accepted = [];
        $failed = 0;

        foreach($lines as $line) {
            $parts = explode(":", $line);
            if (count($parts) != 4 && count($parts) != 2) {
                $failed++;
                continue;
            }
            if ($parts[1] < 1 || $parts[1] > 65535) {
                $failed++;
                continue;
            }
            if ($parts[0] == "") {
                $failed++;
                continue;
            }

            $proxyData = [
                'host' => $parts[0],
                'port' => (int)$parts[1],
                'proxy_group_id' => $proxyGroup?->id ?? null,
                'user_id' => auth()->id(),
            ];

            if (count($parts) == 4) {
                $proxyData['username'] = $parts[2];
                $proxyData['password'] = $parts[3];
            }

            $accepted[] = $proxyData;
        }

        $batches = array_chunk($accepted, 100);

        foreach ($batches as $batch) {
            Proxy::insert($batch);
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
