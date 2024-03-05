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
        Schema::create('unitalities', function (Blueprint $table) {
            $table->id();
            $table->enum("type", ['unit', 'meter']);
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('unitality_fields', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("unitality_id");
            $table->string('name');
            $table->timestamps();

            $table->foreign('unitality_id')->references('id')->on('unitalities')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('property_unitalities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("property_id");
            $table->unsignedBigInteger("field_id");
            $table->string('value');
            $table->timestamps();

            $table->foreign('property_id')->references('id')->on('company_properties')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('field_id')->references('id')->on('unitality_fields')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_unitalities');
    }
};
