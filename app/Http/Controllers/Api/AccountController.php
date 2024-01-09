<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function updateBot(Request $request)
    {
        // todo: improve validation (defined statuses, id exists etc.)
        $validated = $this->validate($request, [
            'Id' => 'required|numeric',
            'Status' => 'required|string'
        ]);

        $account = Account::find($validated['Id']);
        $account->status = $validated['Status'];

        return ['success' => (bool) $account->save()];
    }

    public function wrapper(Request $request)
    {
        $validated = $this->validate($request, [
            '*.BB_GP' => 'filled|int',
            '*.BB_TTL' => 'filled|int',
            '*.BB_QP' => 'filled|int',
            '*.BB_DISPLAYNAME' => 'filled|string',
            '*.BB_STATS' => 'filled|array',
            '*.BB_STATS.*' => 'required|int',
        ]);

        $updated = [];

        foreach ($validated as $id => $stats) {
            $account = Account::findOrFail($id);
            if (!$account->stats) {
                $account->stats()->create();
                $account->unsetRelation('stats');
            }
            if ($stats['BB_GP']) {
                $account->stats->gp = $stats['BB_GP'];
            }
            if ($stats['BB_TTL']) {
                $account->stats->ttl = $stats['BB_TTL'];
            }
            if ($stats['BB_QP']) {
                $account->stats->qp = $stats['BB_QP'];
            }
            // todo: normalize skills into separate table?
            if ($stats['BB_STATS']) {
                $account->stats->skills = collect($stats['BB_STATS'])->toJson();
            }
            $updated[$id] = (bool) $account->stats->save();
        }

        return $updated;
    }
}
