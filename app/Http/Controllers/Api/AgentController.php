<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Agent;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    public function agentKey(Request $request)
    {
        $validated = $this->validate($request, [
            'uuid' => 'required|string',
        ]);

        $agent = Agent::query()
            ->select('agent_key')
            ->where('uuid', $validated['uuid'])
            ->first();

        if (!$agent) {
            return "";
        }

        return $agent->agent_key;
    }

    public function agentData(Request $request)
    {
        $uuids = array_keys($request->all());
        Account::query()
            ->whereIn('status', ['Running', 'Starting', 'Stopping'])
            ->whereHas('agent', function ($query) use ($uuids) {
                $query->whereNotIn('uuid', $uuids);
            })->update(['status' => 'Stopped']);

        foreach ($request->all() as $agentUUid => $accounts) {
            $agent = Agent::query()
                ->where('uuid', $agentUUid)
                ->first();

            $accountIds = array_keys($accounts);

            $deadAccounts = Account::query()
                ->with('agent')
                ->whereNotIn('id', $accountIds)
                ->where('status', 'Running')
                ->where('agent_id', $agent->id)
                ->get();

            foreach ($deadAccounts as $deadAccount) {
                $deadAccount->status = 'Stopped';
                $deadAccount->save();
            }

            foreach ($accounts as $accountId => $accountStatus) {
                $account = Account::find($accountId);
                if ($account->status != $accountStatus) {
                    $account->status = $accountStatus;
                    $account->save();
                }
            }
        }
    }
}
