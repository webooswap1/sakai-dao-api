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
        Schema::create('stake_reward_histories', function (Blueprint $table) {
            $table->id();
            $table->string('address');
            $table->string('amount');
            $table->string('accumulated_amount');
            $table->bigInteger('last_timestamp',0)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stake_reward_histories');
    }
};
