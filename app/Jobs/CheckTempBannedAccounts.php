<?php

namespace App\Jobs;

use App\Models\Account;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class CheckTempBannedAccounts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
//        $past2Days = now()->subDays(2);
//        $past52Hours = now()->subHours(52);
//
//        $accounts = Account::query()->with('stats', 'user')
//            ->whereNotNull('temp_banned_at')
//            ->whereNull('perm_banned_at')
//            ->where('temp_banned_at', '<', $past2Days)
//            ->where('temp_banned_at', '>', $past52Hours)
//            ->whereHas('user.subscription', function ($query) {
//                $query->where('name', '!=', 'Basic');
//            })
//            ->get();
//
//        foreach ($accounts as $account) {
//            $res = Http::get('https://secure.runescape.com/m=hiscore_oldschool/index_lite.ws', [
//                'player' => $account->stats->name
//            ]);
//            if ($res->status() == 200) {
//                $account->temp_banned_at = null;
//                $account->status = 'Stopped';
//                $account->save();
//
//                $account->user->notifications()->create([
//                    'message' => "$account->email is no longer temp banned",
//                    'type' => 'info'
//                ]);
//            }
//        }

        $past52Hours = now()->subHours(52);
        $accounts = Account::query()->with('stats', 'user')
            ->whereNotNull('temp_banned_at')
            ->whereNull('perm_banned_at')
            ->where('temp_banned_at', '<', $past52Hours)
            ->get();

        // been over 52h, we can safely assume no longer temp banned
        foreach ($accounts as $account) {
            $account->temp_banned_at = null;
            $account->status = 'Stopped';
            $account->save();

            $account->user->notifications()->create([
                'message' => "$account->email is no longer marked as temp banned",
                'type' => 'info'
            ]);
        }
    }
}
