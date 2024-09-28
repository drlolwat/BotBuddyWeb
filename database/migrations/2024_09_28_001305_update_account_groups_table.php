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
        Schema::table('account_groups', function(Blueprint $table) {
            $table->boolean('db_debug')->default(false);
            $table->boolean('db_disable_animations')->default(false);
            $table->boolean('db_disable_models')->default(false);
            $table->boolean('db_disable_sounds')->default(false);
            $table->boolean('db_dismiss_random_events')->default(false);
            $table->boolean('db_low_detail')->default(false);
            $table->boolean('db_menu_manipulation')->default(false);
            $table->boolean('db_no_click_walk')->default(false);
            $table->boolean('db_minimized')->default(false);
            $table->boolean('db_beta')->default(false);
            $table->string('db_render')->default('all');
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
