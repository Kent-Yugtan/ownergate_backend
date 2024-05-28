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
        Schema::create('main_screen_ads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('main_screen_id');
            $table->unsignedBigInteger('ads_id');
            $table->enum('type', ['main', 'sub']);
            $table->enum('status', ['active', 'inactive']);
            $table->timestamps();

            $table->foreign('main_screen_id')->references('id')->on('main_screen')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('ads_id')->references('id')->on('inventories')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('main_screen_ads');
    }
};
