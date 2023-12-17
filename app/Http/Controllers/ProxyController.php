<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Proxy;
use Illuminate\Http\Request;

class ProxyController extends Controller
{
    public function index()
    {
        return view('proxy.index');
    }

    public function show(Proxy $proxy)
    {
        return view('proxy.show', compact('proxy'));
    }

    public function create()
    {
        return view('proxy.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validate($request, [
            'host' => 'required',
            'port' => 'required|int',
            'password' => 'required',
            'proxy_group_id' => 'nullable',
        ]);

        $account = Proxy::create([
            'host' => $validated['host'],
            'port' => $validated['port'],
            'password' => $validated['password'],
            'proxy_group_id' => $validated['proxy_group_id'] ?? null,
            'user_id' => auth()->id(),
        ]);

        return redirect(route('proxy.show', $account))->with('status', 'Proxy added');
    }

    public function update(Request $request, Proxy $proxy)
    {
        $validated = $this->validate($request, [
            'host' => 'required',
            'port' => 'required|int',
            'password' => 'required',
            'proxy_group_id' => 'nullable',
        ]);

        $proxy->update([
            'host' => $validated['host'],
            'port' => $validated['port'],
            'password' => $validated['password'],
            'proxy_group_id' => $validated['proxy_group_id'] ?? null,
        ]);

        return redirect(route('proxy.show', $proxy))->with('status', 'Proxy updated');
    }

    public function destroy(Proxy $proxy)
    {
        $groupInUse = Account::where('proxy_id', $proxy->id)->count();

        if ($groupInUse > 0) {
            return redirect(route('proxy.show', $proxy))->withErrors(['Cannot delete proxy as it is in use']);
        }

        $proxy->delete();

        return redirect(route('proxy'))->with('status', 'Proxy deleted');
    }
}
