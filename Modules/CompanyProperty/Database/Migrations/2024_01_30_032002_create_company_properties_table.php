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
        Schema::create('company_properties', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('type_id')->nullable();
            $table->unsignedBigInteger('target_type_id')->nullable();
            $table->unsignedBigInteger('source_property_id')->nullable();
            $table->string('og_code')->nullable();
            $table->string('addmail')->nullable();
            $table->string('name')->nullable();
            $table->string('logo')->nullable();
            $table->string('poster')->nullable();
            $table->string('currency')->nullable();
            $table->decimal('value', 15, 2)->nullable();
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('area_sector_desctrict')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->text('description')->nullable();
            $table->string('full_video')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->text('notes')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('target_type_id')->references('id')->on('category_target_types')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('type_id')->references('id')->on('property_types')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('source_property_id')->references('id')->on('company_properties')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_properties');
    }
};
