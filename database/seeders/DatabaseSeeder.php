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
    }
}
