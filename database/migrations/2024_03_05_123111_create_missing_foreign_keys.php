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
            $table->unsignedBigInteger('user_id')->change();
            $table->unsignedBigInteger('account_group_id')->change();
            $table->unsignedBigInteger('proxy_id')->nullable()->change();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('account_group_id')->references('id')->on('account_groups');
            $table->foreign('proxy_id')->references('id')->on('proxies');
        });

        Schema::table('account_groups', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->change();
            $table->unsignedBigInteger('script_id')->change();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('script_id')->references('id')->on('user_scripts');
        });

        Schema::table('account_stats', function (Blueprint $table) {
            $table->unsignedBigInteger('account_id')->change();
            $table->foreign('account_id')->references('id')->on('accounts');
        });

        Schema::table('agents', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->change();
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->change();
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::table('proxies', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->change();
            $table->unsignedBigInteger('proxy_group_id')->nullable()->change();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('proxy_group_id')->references('id')->on('proxy_groups');
        });

        Schema::table('proxy_groups', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->change();
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('subscription_id')->nullable()->change();
            $table->foreign('subscription_id')->references('id')->on('subscriptions');
        });

        Schema::table('user_scripts', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->change();
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::table('workflows', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->change();
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::table('workflow_actions', function (Blueprint $table) {
            $table->unsignedBigInteger('workflow_id')->change();
            $table->foreign('workflow_id')->references('id')->on('workflows');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

    }
};
