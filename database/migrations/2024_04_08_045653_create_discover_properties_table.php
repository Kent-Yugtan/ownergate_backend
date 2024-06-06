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
        Schema::create('discover_properties', function (Blueprint $table) {
            $table->id();
            $table->string('city');
            $table->string('country');
            $table->string('image')->nullable();
            $table->timestamps();
        });

        Schema::create('discover_property_listings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("discover_property_id");
            $table->string('property_type')->nullable();
            $table->string('target_type')->nullable();
            $table->string('price')->nullable();
            $table->string('beds_bath_kitchen')->nullable();
            $table->string('size')->nullable();
            $table->string('amenities')->nullable();
            $table->string('features')->nullable();
            $table->string('property_nearby')->nullable();
            $table->string('listed_by')->nullable();
            $table->string('property_by')->nullable();
            $table->string('vendor')->nullable();
            $table->timestamps();

            $table->foreign('discover_property_id')->references('id')->on('discover_properties')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discover_properties');
        Schema::dropIfExists('discover_property_listings');
    }
};
