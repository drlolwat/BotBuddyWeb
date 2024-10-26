<?php

namespace App\Http\Controllers\Api;

use App\BotBuddy\Status;
use App\BotBuddy\Workflow\WorkflowService;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\AccountStat;
use App\Models\User;
use App\Models\Workflow;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class AccountController extends Controller
{
    /**
     * @return RedirectResponse|array<string, bool>
     * @throws ValidationException
     */
    public function updateBot(Request $request, WorkflowService $workflowService): RedirectResponse|array
    {
        // todo: improve validation (defined statuses, id exists etc.)
        $validated = $this->validate($request, [
            'Id' => 'required|numeric',
            'Status' => 'required|string'
        ]);

        $account = Account::where('id', $validated['Id'])->first();

        if (!$account) {
            return ['success' => false];
        }

        if (!$account->user->subscription) {
            return ['success' => false];
        }

        // todo: handle via event system
        if ($validated['Status'] == Status::COMPLETED->value) {
            // handle for specific account
            $workflows = $workflowService->getWorkflows('account', $account->id, 'script_complete', ['script_id' => $account->script_id]);
            foreach ($workflows as $workflow) {
                $workflowService->handle($account, $workflow);
            }
            // handle for account groups instead if they are not defined for the account
            if ($workflows->count() == 0 && $account->account_group_id) {
                $workflows = $workflowService->getWorkflows('account_group', $account->account_group_id, 'script_complete', ['script_id' => $account->script_id]);
                foreach ($workflows as $workflow) {
                    $workflowService->handle($account, $workflow);
                }
            }
        }

        if ($validated['Status'] == Status::BANNED->value) {
            if ($account->user->subscription && $account->user->subscription->name != 'Basic' && $account->stats?->name) {
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
            foreach ($workflows as $workflow) {
                $workflowService->handle($account, $workflow);
            }
            // handle for account groups instead if they are not defined for the account
            if ($workflows->count() == 0 && $account->account_group_id) {
                $workflows = $workflowService->getWorkflows('account_group', $account->account_group_id, $event, null);
                foreach ($workflows as $workflow) {
                    $workflowService->handle($account, $workflow);
                }
            }
        }

        if ($validated['Status'] == Status::STOPPED->value && $account->status == Status::BANNED->value) {
            return ['success' => false];
        }

        if ($validated['Status'] == Status::PROXY_BLOCKED->value) {
            // handle for specific account
            $workflows = $workflowService->getWorkflows('account', $account->id, 'proxy_blocked', null);
            foreach ($workflows as $workflow) {
                $workflowService->handle($account, $workflow);
            }
            // handle for account groups instead if they are not defined for the account
            if ($workflows->count() == 0 && $account->account_group_id) {
                $workflows = $workflowService->getWorkflows('account_group', $account->account_group_id, 'proxy_blocked', null);
                foreach ($workflows as $workflow) {
                    $workflowService->handle($account, $workflow);
                }
            }
        }

        if ($validated['Status'] == Status::LOCKED->value) {
            // specific account not necessary, as this was removed from workflow creation prior
            if ($account->account_group_id) {
                $workflows = $workflowService->getWorkflows('account_group', $account->account_group_id, 'locked', null);
                foreach ($workflows as $workflow) {
                    $workflowService->handle($account, $workflow);
                }
            }
        }

        if ($validated['Status'] != Status::PROXY_BLOCKED->value) {
            $account->status = $validated['Status'];
        }

        return ['success' => $account->save()];
    }

    /**
     * @return RedirectResponse|array<string, AccountStat|bool>
     * @throws ValidationException
     */
    public function wrapper(Request $request, WorkflowService $workflowService): RedirectResponse|array
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
            $account = Account::query()->with('stats')->findOrFail($id);

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
                $data['skills'] = new Collection($stats['BB_STATS']);
            }
            // todo: store this
            if (isset($stats['BB_MEM_DAYS_LEFT'])) {
                $data['membership_days_left'] = $stats['BB_MEM_DAYS_LEFT'];
            }

            if (!$account->stats) {
                $updated[$id] = $account->stats()->create($data);
            } else {
                $updated[$id] = $account->stats->update($data);
            }

            if (!$account->user->subscription || $account->user->subscription->name == 'Basic') {
                return back()->withErrors('You need Essential or higher subscription to use this feature');
            }

            // create structure to check for stat goals
            unset($data['world_id']);
            unset($data['name']);
            unset($data['type']);

            // todo: unset this when it is being stored
            //unset($data['membership_days_left']);

            if (isset($data['skills'])) {
                foreach($data['skills'] as $key => $value) {
                    $data[strtolower($key)] = $value;
                }
                unset($data['skills']);
            }
            $keys = array_keys($data);

            if (count($keys) > 0) {
                $workflows = Workflow::query()
                    ->with('model', 'actions')
                    ->where('model_type', 'account')
                    ->where('model_id', $account->id)
                    ->where('event', 'stat_goal')
                    ->get()->filter(function ($workflow) use ($data, $keys) {
                        $match = 0;
                        foreach ($keys as $key) {
                            if (isset($workflow->data[$key]) && !isset($data[$key])) {continue;}
                            if (isset($data[$key]) && isset($workflow->data[$key]) && $workflow->data[$key] <= $data[$key]) {$match++;}
                        }
                        return count($workflow->data) == $match;
                    });

                // handle for specific account
                foreach ($workflows as $workflow) {
                    $workflowService->handle($account, $workflow);
                }

                // handle for account groups instead if they are not defined for the account
                if ($workflows->count() == 0 && $account->account_group_id) {
                    $workflows = Workflow::query()
                        ->with('model', 'actions')
                        ->where('model_type', 'account_group')
                        ->where('model_id', $account->account_group_id)
                        ->where('event', 'stat_goal')
                        ->get()
                        ->filter(function ($workflow) use ($data, $keys) {
                            $match = 0;
                            foreach ($keys as $key) {
                                if (isset($workflow->data[$key]) && !isset($data[$key])) {
                                    // it is a requirement and not present
                                    continue;
                                }
                                if (isset($data[$key]) && isset($workflow->data[$key]) && $workflow->data[$key] <= $data[$key]) {
                                    // it matches or exceeds the required value
                                    $match++;
                                }
                            }
                            return count($workflow->data) == $match;
                        });

                    foreach ($workflows as $workflow) {
                        $workflowService->handle($account, $workflow);
                    }
                }
            }
        }

        return $updated;
    }

    public function allowedClients(Request $request): int
    {
        $user = User::find($request['customerId']);
        if (!$user) {
            return 0;
        }
        return $user->subscription?->max_agents ?? 0;
    }
}
