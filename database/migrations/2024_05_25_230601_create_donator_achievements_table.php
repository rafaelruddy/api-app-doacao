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
        Schema::create('donator_achievements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('donator_id');
            $table->foreign('donator_id')->on('donators')->references('id')->onDelete('cascade');
            $table->unsignedBigInteger('achievement_id');
            $table->foreign('achievement_id')->on('achievements')->references('id')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donator_achievements');
    }
};
