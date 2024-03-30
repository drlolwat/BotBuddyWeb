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
        Schema::create('schedule_events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_group_id');
            $table->string('name');
            $table->string('color');
            $table->unsignedTinyInteger('day'); // 1=monday, 7=sunday
            $table->string('action');
            $table->json('data')->nullable();
            $table->time('start_at');
            $table->time('finish_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedule_events');
    }
};
