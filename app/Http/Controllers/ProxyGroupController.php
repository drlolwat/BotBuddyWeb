<?php

namespace App\Http\Controllers;

use App\Models\Proxy;
use App\Models\ProxyGroup;
use Illuminate\Http\Request;

class ProxyGroupController extends Controller
{
    public function index()
    {
        return view('proxy.group.index');
    }

    public function show(ProxyGroup $group)
    {
        return view('proxy.group.show', compact('group'));
    }

    public function create()
    {
        return view('proxy.group.create');
    }

    public function store(Request $request)
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

    public function update(Request $request, ProxyGroup $group)
    {
        $validated = $this->validate($request, [
            'name' => 'required',
        ]);

        $group->update([
            'name' => $validated['name'],
        ]);

        return redirect(route('proxy.group.show', $group))->with('status', 'Proxy group updated');
    }

    public function destroy(ProxyGroup $group)
    {
        $groupInUse = Proxy::where('proxy_group_id', $group->id)->count();

        if ($groupInUse > 0) {
            return redirect(route('proxy.group.show', $group))->withErrors(['Cannot delete proxy group as it is in use']);
        }

        $group->delete();

        return redirect(route('proxy'))->with('status', 'Proxy group deleted');
    }
}
