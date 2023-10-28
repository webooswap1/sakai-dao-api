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
        Schema::create('proposals', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('category')->nullable();
            $table->json('meta_data')->nullable();
            $table->string('owner')->nullable();
            $table->string('txHash')->nullable();
            $table->string('proposal_id')->nullable();
            $table->string('status')->default('pending');
            $table->string('admin_vote_approve')->default('0');
            $table->string('admin_vote_reject')->default('0');
            $table->string('user_vote_approve')->default('0');
            $table->string('user_vote_reject')->default('0');
            $table->string('total_vote')->default('0');
            $table->string('total_participant')->default('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proposals');
    }
};
