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

        $userId = null;

        foreach ($request->all() as $agentUUid => $accounts) {
            $agent = Agent::query()
                ->where('uuid', $agentUUid)
                ->first();

            if (!$agent) {
                continue;
            }

            if (!$userId) {
                $userId = $agent->user_id;
            }

            $accountIds = collect($accounts)->map(function ($account) {
                return array_keys($account)[0];
            })->toArray();

            $deadAccounts = Account::query()
                ->with('agent')
                ->whereNotIn('id', $accountIds)
                ->where('status', 'Running')
                ->where('user_id', $agent->user_id)
                ->where('agent_id', $agent->id)
                ->get();

            foreach ($deadAccounts as $deadAccount) {
                $deadAccount->status = 'Stopped';
                $deadAccount->save();
            }

            $accountModels = Account::query()
                ->whereIn('id', $accountIds)
                ->get()->mapWithKeys(function ($account) {
                    return [$account->id => $account->status];
                })->toArray();

            foreach ($accounts as $accountData) {
                foreach ($accountData as $accountId => $accountStatus) {
                    $account = $accountModels[$accountId];
                    if ($account && $account->status != $accountStatus) {
                        $account->status = $accountStatus;
                        $account->save();
                    }
                }
            }
        }

        if ($userId) {
            Account::query()
                ->whereIn('status', ['Running', 'Stopping'])
                ->where('user_id', $userId)
                ->whereHas('agent', function ($query) use ($uuids) {
                    $query->whereNotIn('uuid', $uuids);
                })->update(['status' => 'Stopped']);
        }
    }

    public function customerId(Request $request)
    {
        $validated = $this->validate($request, [
            'uuid' => 'required|string',
        ]);

        $agent = Agent::query()
            ->select('user_id')
            ->where('uuid', $validated['uuid'])
            ->first();

        if (!$agent) {
            return "";
        }

        return $agent->user_id;
    }
}
