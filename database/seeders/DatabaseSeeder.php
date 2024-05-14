<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(SubscriptionSeeder::class);

        $user = User::create([
            'name' => 'demo',
            'email' => 'demo@botbuddy.net',
            'password' => Hash::make('demo'),
            'email_verified_at' => now(),
            'subscription_id' => 1,
            'subscription_expires_at' => now()->addMonth(),
        ]);

        $agent1 = $user->agents()->create([
            'name' => 'Local machine',
            'user_id' => $user->id,
            'client_type' => 'DreamBot',
            'uuid' => Str::uuid()->toString(),
            'agent_key' => trim(bin2hex(random_bytes(32))),
        ]);

        $agent2 = $user->agents()->create([
            'name' => 'OVH box',
            'user_id' => $user->id,
            'client_type' => 'DreamBot',
            'uuid' => Str::uuid()->toString(),
            'agent_key' => trim(bin2hex(random_bytes(32))),
        ]);

        $proxyGroup1 = $user->proxy_groups()->create([
            'name' => 'Residential proxies',
            'user_id' => $user->id,
        ]);

        $proxy1 = $proxyGroup1->proxies()->create([
            'host' => 'atomicproxy.io',
            'port' => 8080,
            'password' => 'test123',
            'user_id' => $user->id,
        ]);

        $proxy2 = $proxyGroup1->proxies()->create([
            'host' => 'residential.datasource.net',
            'port' => 25534,
            'password' => '3D4uy$fb9',
            'user_id' => $user->id,
        ]);

        $proxyGroup2 = $user->proxy_groups()->create([
            'name' => 'Suicide proxies',
            'user_id' => $user->id,
        ]);

        $proxy3 = $proxyGroup2->proxies()->create([
            'host' => 'shitproxy.com',
            'port' => 7777,
            'password' => 'test123',
            'user_id' => $user->id,
        ]);

        $script1 = $user->scripts()->create([
            'name' => 'GE Profit Bot',
            'user_id' => $user->id,
            'script' => 'ge_profit',
        ]);

        $script2 = $user->scripts()->create([
            'name' => 'Noob Cow Killer',
            'user_id' => $user->id,
            'script' => 'cowkiller',
        ]);

        $script3 = $user->scripts()->create([
            'name' => 'Cooking Master 2.0',
            'user_id' => $user->id,
            'script' => 'cooking_master',
        ]);

        $accountGroup1 = $user->account_groups()->create([
            'name' => '99 Cooking',
            'script_id' => $script3->id,
            'user_id' => $user->id,
        ]);

        $account1 = $accountGroup1->accounts()->create([
            'email' => 'cooking99@gmail.com',
            'password' => 'test123',
            'user_id' => $user->id,
            'proxy_id' => $proxy1->id,
//            'script_id' => $script3->id,
//            'agent_id' => $agent1->id,
        ]);

        $account2 = $accountGroup1->accounts()->create([
            'email' => 'cooking.acc@hotmail.com',
            'password' => 'mypassword23',
            'user_id' => $user->id,
            'proxy_id' => $proxy2->id,
//            'script_id' => $script3->id,
        ]);

        $accountGroup2 = $user->account_groups()->create([
            'name' => 'Freshies',
            'script_id' => $script2->id,
            'user_id' => $user->id,
        ]);

        $account3 = $accountGroup2->accounts()->create([
            'email' => 'johnsmith5554@gmail.com',
            'password' => 'lol123',
            'user_id' => $user->id,
            'proxy_id' => $proxy3->id,
//            'script_id' => $script2->id,
//            'agent_id' => $agent2->id,
        ]);
    }
}
