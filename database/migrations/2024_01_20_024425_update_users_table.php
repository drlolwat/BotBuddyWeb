<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $users = User::select('dreambot_client')->get();

        foreach ($users as $user) {
            $user->agents()->update([
                'dreambot_client_path' => $user->dreambot_client,
            ]);
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('dreambot_client');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
