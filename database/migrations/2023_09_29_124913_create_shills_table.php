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
        Schema::create('shills', function (Blueprint $table) {
            $table->id();
            $table->string('address');
            $table->string('url');
            $table->integer('epoch');
            $table->string('claim_reward_hash')->nullable();
            $table->string('claim_gas_hash')->nullable();
            $table->timestamps();
            $table->dateTime('claim_reward_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shills');
    }
};
