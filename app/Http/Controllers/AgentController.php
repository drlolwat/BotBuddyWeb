<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Agent;
use App\Models\Proxy;
use App\Models\ProxyGroup;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AgentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(): View
    {
        $agents = Agent::where('user_id', auth()->id())->paginate(10);
        return view('v1.agent.index', compact('agents'));
    }

    public function show(Agent $agent): View|RedirectResponse
    {
        $this->authorize('view', $agent);

        return view('v1.agent.show', compact('agent'));
    }

    public function create():View
    {
        return view('v1.agent.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $agentCount = Agent::query()
            ->where('user_id', auth()->id())
            ->count();

        $maxAgents = auth()->user()->subscription->max_agents;

        if ($agentCount >= $maxAgents) {
            return back()->withErrors("You are not allowed to create more than $maxAgents agents");
        }

        $validated = $this->validate($request, [
            'name' => 'required',
            'dreambot_client_path' => '',
            'dreambot_scripts_path' => '',
            'dreambot_min_heap' => 'required|regex:/^\d+[MG]$/',
            'dreambot_max_heap' => 'required|regex:/^\d+[MG]$/',
        ]);

        $agent = Agent::create([
            'name' => $validated['name'],
            'user_id' => auth()->id(),
            'uuid' => Str::uuid()->toString(),
            'agent_key' => trim(bin2hex(random_bytes(32))),
            'client_type' => 'DreamBot',
            'dreambot_client_path' => $validated['dreambot_client_path'] ?? null,
            'dreambot_scripts_path' => $validated['dreambot_scripts_path'] ?? null,
            'dreambot_min_heap' => $validated['dreambot_min_heap'],
            'dreambot_max_heap' => $validated['dreambot_max_heap'],
        ]);

        return redirect(route('agent.show', $agent))->with('status', 'Agent created');
    }

    public function update(Request $request, Agent $agent): RedirectResponse
    {
        $this->authorize('view', $agent);

        $validated = $this->validate($request, [
            'name' => 'required',
            'dreambot_client_path' => '',
            'dreambot_scripts_path' => '',
            'dreambot_min_heap' => 'required|regex:/^\d+[MG]$/',
            'dreambot_max_heap' => 'required|regex:/^\d+[MG]$/',
        ]);

        $agent->update([
            'name' => $validated['name'],
            'client_type' => 'DreamBot',
            'dreambot_client_path' => $validated['dreambot_client_path'] ?? $agent->dreambot_client_path,
            'dreambot_scripts_path' => $validated['dreambot_scripts_path'] ?? $agent->dreambot_scripts_path,
            'dreambot_min_heap' => $validated['dreambot_heap_min'] ?? $agent->dreambot_min_heap,
            'dreambot_max_heap' => $validated['dreambot_heap_max'] ?? $agent->dreambot_max_heap,
        ]);

        return redirect(route('agent.show', $agent))->with('status', 'Agent updated');
    }

    public function destroy(Agent $agent): RedirectResponse
    {
        $this->authorize('view', $agent);

        $agentInUse = Account::where('agent_id', $agent->id)->count();

        if ($agentInUse > 0) {
            return back()->withErrors(['Cannot delete agent as it is in use']);
        }

        $agent->delete();

        return redirect(route('agent'))->with('status', 'Agent deleted');
    }

    public function download(Agent $agent): BinaryFileResponse|RedirectResponse
    {
        $this->authorize('view', $agent);

        $arch = request()->get('arch');

        $command = match($arch) {
            'linux' => 'cd /var/www/html/goagent && go build -o %s -ldflags "-s -w -X main.CLIENT_UUID=%s -X main.CLIENT_KEY=%s" .',
            'windows' => 'cd /var/www/html/goagent && GOOS=windows GOARCH=amd64 go build -o %s -ldflags "-s -w -X main.CLIENT_UUID=%s -X main.CLIENT_KEY=%s" .',
            default => null,
        };

        if (!$command) {
            abort(404);
        }

        $dir = public_path("agents/$agent->id");

        if (!is_dir($dir)) {
            mkdir($dir);
        }

        $name = Str::of(Str::snake(strtolower($agent->name)))->replaceMatches('/[^a-zA-Z0-9\-_\.]/', '') . '-bbagent' . ($arch == 'linux' ? '' : '.exe');

        $file = $dir . "/$name";

        if (file_exists($file)) {
            return response()->download($file, $name, ['Cache-Control' => 'no-cache, must-revalidate']);
        }

        $command = sprintf(
            $command,
            $file,
            $agent->uuid,
            $agent->agent_key
        );

        if (exec($command) === false) {
            return back()->withErrors('Could not download agent, please contact support');
        }

        return response()->download($file, $name, ['Cache-Control' => 'no-cache, must-revalidate']);
    }
}
