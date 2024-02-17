<?php

namespace App\Http\Controllers\Api;

use App\BotBuddy\Workflow\WorkflowService;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AccountController extends Controller
{
    public function updateBot(Request $request, WorkflowService $workflowService)
    {
        // todo: improve validation (defined statuses, id exists etc.)
        $validated = $this->validate($request, [
            'Id' => 'required|numeric',
            'Status' => 'required|string'
        ]);

        $account = Account::find($validated['Id']);

        if (!$account) {
            return ['success' => false];
        }

        if (!$account->user->subscription) {
            return ['success' => false];
        }

        // todo: handle via event system
        if ($validated['Status'] == 'Completed') {
            // handle for specific account
            $workflows = $workflowService->getWorkflows('account', $account->id, 'script_complete', ['script_id' => $account->script_id]);
            foreach($workflows as $workflow) {
                $workflowService->handle($account, $workflow);
            }
            // handle for account groups instead if they are not defined for the account
            if ($workflows->count() == 0 && $account->account_group_id) {
                $workflows = $workflowService->getWorkflows('account_group', $account->account_group_id, 'script_complete', ['script_id' => $account->script_id]);
                foreach($workflows as $workflow) {
                    $workflowService->handle($account, $workflow);
                }
            }
        }

        if ($validated['Status'] == 'Banned') {
            if ($account->user->subscription->name != 'Basic' && $account->stats?->name) {
                // check if account is temp banned or perm banned via hiscores
                $res = Http::get('https://secure.runescape.com/m=hiscore_oldschool/index_lite.ws', [
                    'player' => $account->stats->name
                ]);
                if ($res->status() == 404) {
                    $account->perm_banned_at = now();
                    $event = 'perm_banned';
                } else {
                    $account->temp_banned_at = now();
                    $event = 'temp_banned';
                }
            } else {
                // we either don't know the name of the account to check for temp,
                // or the account does not have the eligible subscription
                $account->perm_banned_at = now();
                $event = 'perm_banned';
            }

            // handle for specific account
            $workflows = $workflowService->getWorkflows('account', $account->id, $event, null);
            foreach($workflows as $workflow) {
                $workflowService->handle($account, $workflow);
            }
            // handle for account groups instead if they are not defined for the account
            if ($workflows->count() == 0 && $account->account_group_id) {
                $workflows = $workflowService->getWorkflows('account_group', $account->account_group_id, $event, null);
                foreach($workflows as $workflow) {
                    $workflowService->handle($account, $workflow);
                }
            }
        }

        if ($validated['Status'] == 'Stopped' && $account->status == 'Banned') {
            return ['success' => false];
        }

        if ($validated['Status'] == 'ProxyBlocked') {
            // handle for specific account
            $workflows = $workflowService->getWorkflows('account', $account->id, 'proxy_blocked', null);
            foreach($workflows as $workflow) {
                $workflowService->handle($account, $workflow);
            }
            // handle for account groups instead if they are not defined for the account
            if ($workflows->count() == 0 && $account->account_group_id) {
                $workflows = $workflowService->getWorkflows('account_group', $account->account_group_id, 'proxy_blocked', null);
                foreach($workflows as $workflow) {
                    $workflowService->handle($account, $workflow);
                }
            }
        }

        if ($validated['Status'] != 'ProxyBlocked') {
            $account->status = $validated['Status'];
        }

        return ['success' => $account->save()];
    }

    public function wrapper(Request $request)
    {
        $validated = $this->validate($request, [
            '*.BB_OUTPUT.BB_GP' => 'filled|int',
            '*.BB_OUTPUT.BB_TTL' => 'filled|int',
            '*.BB_OUTPUT.BB_QP' => 'filled|int',
            '*.BB_OUTPUT.BB_WORLD' => 'filled|int',
            '*.BB_OUTPUT.BB_TYPE' => 'filled|string',
            '*.BB_OUTPUT.BB_DISPLAYNAME' => 'filled|string',
            '*.BB_OUTPUT.BB_STATS' => 'filled|array',
            '*.BB_OUTPUT.BB_STATS.*' => 'required|int',
        ]);

        $updated = [];

        foreach ($validated as $id => $stats) {
            $stats = $stats['BB_OUTPUT'];
            $account = Account::findOrFail($id);

            $data = [];

            if (isset($stats['BB_GP'])) {
                $data['gp'] = $stats['BB_GP'];
            }
            if (isset($stats['BB_TTL'])) {
                $data['ttl'] = $stats['BB_TTL'];
            }
            if (isset($stats['BB_QP'])) {
                $data['qp'] = $stats['BB_QP'];
            }
            if (isset($stats['BB_WORLD'])) {
                $data['world_id'] = $stats['BB_WORLD'];
            }
            if (isset($stats['BB_DISPLAYNAME'])) {
                $data['name'] = $stats['BB_DISPLAYNAME'];
            }
            if (isset($stats['BB_TYPE'])) {
                $data['type'] = $stats['BB_TYPE'];
            }
            // todo: normalize skills into separate table?
            if (isset($stats['BB_STATS'])) {
                $data['skills'] = collect($stats['BB_STATS']);
            }

            if (!$account->stats) {
                $updated[$id] = $account->stats()->create($data);
            } else {
                $updated[$id] = $account->stats->update($data);
            }
        }

        return $updated;
    }

    public function allowedClients(Request $request)
    {
        $user = User::find($request['customerId']);
        if (!$user) {
            return 0;
        }
        return $user->subscription?->max_agents ?? 0;
    }
}
