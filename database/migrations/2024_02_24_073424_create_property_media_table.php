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
        Schema::create('property_media', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("property_id");
            $table->string('name')->nullable();
            $table->enum('type', ['Full Video', '360 Virtual Tour', '360 Virtual Spots', 'Photos', 'Plan'])->default('Photos');
            $table->string('description')->nullable();
            $table->string('area')->nullable();
            $table->timestamps();

            $table->foreign('property_id')->references('id')->on('company_properties')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('property_media_paths', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("media_id");
            $table->string('name')->nullable();
            $table->string('path');
            $table->timestamps();

            $table->foreign('media_id')->references('id')->on('property_media')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_media');
    }
};
