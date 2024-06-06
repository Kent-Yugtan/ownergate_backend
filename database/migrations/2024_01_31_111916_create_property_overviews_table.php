<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('overviews', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('property_overviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("property_id");
            $table->unsignedBigInteger("overview_id");
            $table->integer('quantity')->nullable();
            $table->tinyInteger('visible')->default(0);
            $table->timestamps();

            $table->foreign('property_id')->references('id')->on('company_properties')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('overview_id')->references('id')->on('overviews')->onUpdate('cascade')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_overview');
    }
};
