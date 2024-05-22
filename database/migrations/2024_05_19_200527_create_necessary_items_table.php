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
        Schema::create('necessary_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('campaign_id');
            $table->foreign('campaign_id')->on('campaigns')->references('id')->onDelete('cascade');
            $table->unsignedBigInteger('item_id');
            $table->foreign('item_id')->on('items')->references('id')->onDelete('cascade');
            $table->integer('quantity_objective', false, true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('necessary_items');
    }
};
