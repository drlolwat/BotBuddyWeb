<?php

namespace App\Http\Controllers\Api;

use App\BotBuddy\Rule\RuleService;
use App\Http\Controllers\Controller;
use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function updateBot(Request $request, RuleService $ruleService)
    {
        // todo: improve validation (defined statuses, id exists etc.)
        $validated = $this->validate($request, [
            'Id' => 'required|numeric',
            'Status' => 'required|string'
        ]);

        // todo: handle via event system
        if ($validated['Status'] == 'Completed') {
            $scriptId = Account::where('id', 1)->value('script_id');
            $rules = $ruleService->getRules('account', $validated['Id'], 'script_complete', ['script_id' => $scriptId]);
            foreach($rules as $rule) {
                $ruleService->handle($rule);
            }
        }

        $account = Account::find($validated['Id']);
        $account->status = $validated['Status'];

        return ['success' => (bool) $account->save()];
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
            if (!$account->stats) {
                $account->stats()->create();
                $account->unsetRelation('stats');
            }
            if (isset($stats['BB_GP'])) {
                $account->stats->gp = $stats['BB_GP'];
            }
            if (isset($stats['BB_TTL'])) {
                $account->stats->ttl = $stats['BB_TTL'];
            }
            if (isset($stats['BB_QP'])) {
                $account->stats->qp = $stats['BB_QP'];
            }
            if (isset($stats['BB_WORLD'])) {
                $account->stats->world_id = $stats['BB_WORLD'];
            }
            if (isset($stats['BB_DISPLAYNAME'])) {
                $account->stats->name = $stats['BB_DISPLAYNAME'];
            }
            if (isset($stats['BB_TYPE'])) {
                $account->stats->type = $stats['BB_TYPE'];
            }
            // todo: normalize skills into separate table?
            if (isset($stats['BB_STATS'])) {
                $account->stats->skills = collect($stats['BB_STATS'])->toJson();
            }
            $updated[$id] = (bool) $account->stats->save();
        }

        return $updated;
    }
}
