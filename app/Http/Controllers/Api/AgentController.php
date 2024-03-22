<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Agent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use function Sentry\captureException;

class AgentController extends Controller
{
    public function agentKey(Request $request): RedirectResponse|string
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

    public function agentData(Request $request): void
    {
        $uuids = array_keys($request->all());

        $user = null;

        foreach ($request->all() as $agentUUid => $accounts) {

            $encoded = json_encode($accounts);
            $decodedArray = $encoded
                ? json_decode($encoded, true)
                : [];
            $accountStatusById = [];

            foreach ($decodedArray as $item) {
                foreach ($item as $key => $value) {
                    $accountStatusById[$key] = $value;
                }
            }

            $agent = Agent::query()
                ->with('user')
                ->where('uuid', $agentUUid)
                ->first();

            if (!$agent) {
                continue;
            }

            if (!$user) {
                $user = $agent->user;
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
                    if (!isset($accountModelsById[$accountId])) {
                        captureException(new \Exception("Account ID $accountId received via agentData, not found (was it deleted?)"));
                        continue;
                    }
                    $account = $accountModelsById[$accountId];
                    if ($account->status != $accountStatus) {
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
            ->whereNotIn('agent_id', Agent::query()
                ->where('last_agentdata_at', '>', now()->subMinutes(5))
                ->pluck('id'))
            ->where('status', '!=', 'Stopped')
            ->where('user_id', $user->id)
            ->update(['status' => 'Stopped']);
    }

    public function customerId(Request $request): RedirectResponse|int|string
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
