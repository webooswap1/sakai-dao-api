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
        Schema::create('stakes', function (Blueprint $table) {
            $table->id();
            $table->string('address');
            $table->enum('type',['STAKE','UNSTAKE']);
            $table->string('amount');
            $table->string('txHash');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stakes');
    }
};
