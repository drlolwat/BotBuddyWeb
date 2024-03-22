<?php

namespace App\Http\Controllers;

use App\Models\Proxy;
use App\Models\ProxyGroup;
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
        $proxyGroups = auth()->user()
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

        $groupInUse = Proxy::where('proxy_group_id', $group->id)->count();

        if ($groupInUse > 0) {
            return back()->withErrors(['Cannot delete proxy group as it is in use']);
        }

        $group->delete();

        return redirect(route('proxy.group'))->with('status', 'Proxy group deleted');
    }
}
