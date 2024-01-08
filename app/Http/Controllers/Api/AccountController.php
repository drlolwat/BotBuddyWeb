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
}
