<?php

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
        Schema::table('proxies', function (Blueprint $table) {
            $table->index(['user_id', 'id']);
        });

        Schema::table('user_scripts', function (Blueprint $table) {
            $table->index(['user_id', 'id']);
        });

        Schema::table('account_groups', function (Blueprint $table) {
            $table->index(['user_id', 'id']);
        });

        Schema::table('agents', function (Blueprint $table) {
            $table->index(['user_id', 'id']);
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
