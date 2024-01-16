<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Agent;
use App\Models\Proxy;
use App\Models\ProxyGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AgentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index()
    {
        return view('agent.index');
    }

    public function show(Agent $agent)
    {
        return view('agent.show', compact('agent'));
    }

    public function create()
    {
        return view('agent.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validate($request, [
            'name' => 'required',
        ]);

        $agent = Agent::create([
            'name' => $validated['name'],
            'user_id' => auth()->id(),
            'uuid' => Str::uuid()->toString(),
            'agent_key' => trim(bin2hex(random_bytes(32))),
        ]);

        return redirect(route('agent.show', $agent))->with('status', 'Agent created');
    }

    public function update(Request $request, Agent $agent)
    {
        $validated = $this->validate($request, [
            'name' => 'required',
        ]);

        $agent->update([
            'name' => $validated['name'],
        ]);

        return redirect(route('agent.show', $agent))->with('status', 'Agent updated');
    }

    public function destroy(Agent $agent)
    {
        $agentInUse = Account::where('agent_id', $agent->id)->count();

        if ($agent > 0) {
            return redirect(route('agent.show', $agent))->withErrors(['Cannot delete agent as it is in use']);
        }

        $agent->delete();

        return redirect(route('proxy'))->with('status', 'Agent deleted');
    }
}
