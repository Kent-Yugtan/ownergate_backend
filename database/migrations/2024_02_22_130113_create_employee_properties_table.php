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
        Schema::create('employee_properties', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('property_id');
            $table->string('access_code')->nullable();

            $table->unique(['employee_id','property_id']);
            $table->foreign('employee_id')->references('id')->on('company_employees')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('property_id')->references('id')->on('company_properties')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_properties');
    }
};
