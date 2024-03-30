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
        Schema::create('company_employees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id');
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('profile_id');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->string('type');
            $table->string('department')->nullable();
            $table->string('building_name')->nullable();
            $table->string('unit_number')->nullable();
            $table->date('join_date');
            $table->string('position');
            $table->float("salary")->nullable();
            $table->string("mobile_number_1");
            $table->string('emergency_contact_name');
            $table->string('emergency_contact_number');
            $table->json("official_contract");
            $table->json("company_contract");
            $table->json("more_details")->nullable();
            $table->json("password_period")->nullable();
            $table->timestamps();

            $table->foreign('admin_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('profile_id')->references('id')->on('user_profiles')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_employees');
    }
};
