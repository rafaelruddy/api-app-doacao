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
        Schema::create('donated_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('donation_id');
            $table->foreign('donation_id')->on('donations')->references('id')->onDelete('cascade');
            $table->unsignedBigInteger('item_id');
            $table->foreign('item_id')->on('items')->references('id')->onDelete('cascade');
            $table->integer('quantity', false, true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donated_items');
    }
};
