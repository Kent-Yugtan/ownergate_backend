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
        Schema::create('amenity_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('amenities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("amenity_type_id");
            $table->string('name');
            $table->timestamps();

            $table->foreign('amenity_type_id')->references('id')->on('amenity_types')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('property_amenities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("property_id");
            $table->unsignedBigInteger("amenity_id");
            $table->timestamps();

            $table->foreign('property_id')->references('id')->on('company_properties')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('amenity_id')->references('id')->on('amenities')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amenity_types');
        Schema::dropIfExists('amenities');
        Schema::dropIfExists('property_amenities');
    }
};
