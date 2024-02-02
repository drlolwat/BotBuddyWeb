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
        $agents = Agent::where('user_id', auth()->id())->paginate(10);
        return view('v1.agent.index', compact('agents'));
    }

    public function show(Agent $agent)
    {
        return view('v1.agent.show', compact('agent'));
    }

    public function create()
    {
        return view('v1.agent.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validate($request, [
            'name' => 'required',
            'dreambot_client_path' => '',
            'dreambot_scripts_path' => '',
        ]);

        $agent = Agent::create([
            'name' => $validated['name'],
            'user_id' => auth()->id(),
            'uuid' => Str::uuid()->toString(),
            'agent_key' => trim(bin2hex(random_bytes(32))),
            'client_type' => 'DreamBot',
            'dreambot_client_path' => $validated['dreambot_client_path'] ?? null,
            'dreambot_scripts_path' => $validated['dreambot_scripts_path'] ?? null,
        ]);

        return redirect(route('agent.show', $agent))->with('status', 'Agent created');
    }

    public function update(Request $request, Agent $agent)
    {
        $validated = $this->validate($request, [
            'name' => 'required',
            'dreambot_client_path' => '',
            'dreambot_scripts_path' => '',
        ]);

        $agent->update([
            'name' => $validated['name'],
            'client_type' => 'DreamBot',
            'dreambot_client_path' => $validated['dreambot_client_path'] ?? $agent->dreambot_client_path,
            'dreambot_scripts_path' => $validated['dreambot_scripts_path'] ?? $agent->dreambot_scripts_path,
        ]);

        return redirect(route('agent.show', $agent))->with('status', 'Agent updated');
    }

    public function destroy(Agent $agent)
    {
        $agentInUse = Account::where('agent_id', $agent->id)->count();

        if ($agentInUse > 0) {
            return back()->withErrors(['Cannot delete agent as it is in use']);
        }

        $agent->delete();

        return redirect(route('agent'))->with('status', 'Agent deleted');
    }
}
