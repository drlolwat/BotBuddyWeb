<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\AccountGroup;
use App\Models\Proxy;
use App\Models\ProxyGroup;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProxyGroupController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(): View
    {
        /** @var User $user */
        $user = auth()->user();

        $proxyGroups = $user
            ->proxy_groups()
            ->withCount('proxies')
            ->paginate(10);

        return view('v1.proxy.group.index', compact('proxyGroups'));
    }

    public function show(ProxyGroup $group): View|RedirectResponse
    {
        $this->authorize('view', $group);

        $proxies = $group->proxies()->withCount('accounts')->paginate(10);

        return view('v1.proxy.group.show', compact('group', 'proxies'));
    }

    public function create(): View
    {
        return view('v1.proxy.group.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validate($request, [
            'name' => 'required',
        ]);

        $group = ProxyGroup::create([
            'name' => $validated['name'],
            'user_id' => auth()->id(),
        ]);

        return redirect(route('proxy.group.show', $group))->with('status', 'Proxy group created');
    }

    public function update(Request $request, ProxyGroup $group): RedirectResponse
    {
        $this->authorize('view', $group);

        $validated = $this->validate($request, [
            'name' => 'required',
        ]);

        $group->update([
            'name' => $validated['name'],
        ]);

        return redirect(route('proxy.group.show', $group))->with('status', 'Proxy group updated');
    }

    public function destroy(ProxyGroup $group): RedirectResponse
    {
        $this->authorize('view', $group);

        // unassign proxies in use from group
        Account::query()
            ->whereIn('proxy_id', $group->proxies->pluck('id'))
            ->update(['proxy_id' => null]);

        // soft delete proxies
        $group->proxies()->update(['deleted_at' => now()]);

        // soft delete group
        $group->delete();

        return redirect(route('proxy.group'))->with('status', 'Proxy group deleted');
    }

    public function delete_confirm(ProxyGroup $group): View
    {
        $this->authorize('view', $group);

        $group->loadCount('proxies');
        $group->load('proxies.accounts');

        $totalAccounts = $group->proxies->sum(function ($proxy) {
            return $proxy->accounts->count();
        });

        return view('v1.proxy.group.delete_confirm', compact('group', 'totalAccounts'));
    }
}
