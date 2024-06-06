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
        Schema::create('property_address_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("property_id");
            $table->string('name');
            $table->string('value');
            $table->timestamps();

            $table->foreign('property_id')->references('id')->on('company_properties')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_address_details');
    }
};
