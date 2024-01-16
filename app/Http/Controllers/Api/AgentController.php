<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    public function agentKey(Request $request)
    {
        $validated = $this->validate($request, [
            'uuid' => 'required|string',
        ]);

        return Agent::query()
            ->select('agent_key')
            ->where('uuid', $validated['uuid'])
            ->first()
            ->value('agent_key') ?? "";
    }
}
