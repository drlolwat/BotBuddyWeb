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

            $decodedArray = json_decode(json_encode($accounts), true);
            $accountStatusById = [];

            foreach ($decodedArray as $item) {
                foreach ($item as $key => $value) {
                    $accountStatusById[$key] = $value;
                }
            }

            $agent = Agent::query()
                ->where('uuid', $agentUUid)
                ->first();

            if (!$agent) {
                continue;
            }

            if (!$userId) {
                $userId = $agent->user_id;
            }

            $accountIds = array_keys($accountStatusById);

            $deadAccounts = Account::query()
                ->with('agent')
                ->whereNotIn('id', $accountIds)
                ->whereIn('status', ['Running', 'Stopping'])
                ->where('user_id', $agent->user_id)
                ->where('agent_id', $agent->id)
                ->get();

            foreach ($deadAccounts as $deadAccount) {
                $deadAccount->status = 'Stopped';
                $deadAccount->save();
            }

            $accountModels = Account::query()
                ->whereIn('id', $accountIds)
                ->get();

            $accountModelsById = $accountModels->keyBy('id');

            foreach ($accounts as $accountData) {
                foreach ($accountData as $accountId => $accountStatus) {
                    $account = $accountModelsById[$accountId];
                    if ($account && $account->status != $accountStatus) {
                        $account->status = $accountStatus;
                        $account->save();
                    }
                }
            }
        }

        // mark agentdata as received
        Agent::query()
            ->whereIn('uuid', $uuids)
            ->update(['last_agentdata_at' => now()]);

        // update accounts on agents not running
        Account::query()
            ->with('agent')
            ->whereNotIn('agent_id', Agent::query()->whereIn('uuid', $uuids)
                ->where('last_agentdata_at', '>', now()->subMinutes(5))
                ->pluck('id'))
            ->where('status', '!=', 'Stopped')
            ->where('user_id', $userId)
            ->update(['status' => 'Stopped']);
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
