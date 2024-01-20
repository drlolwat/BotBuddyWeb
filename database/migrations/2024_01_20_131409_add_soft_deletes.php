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
        Schema::table('accounts', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('account_groups', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('account_stats', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('agents', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('proxies', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('proxy_groups', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('user_scripts', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('workflows', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('workflow_actions', function (Blueprint $table) {
            $table->softDeletes();
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
