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
        Schema::create('company_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('property_id');
            $table->string('request_id_code');
            $table->string('request_name');
            $table->string('request_number');
            $table->string('status');
            $table->string('commission_percentage');
            $table->string('commission_notes');
            $table->string('category');
            $table->date('request_date');
            $table->time('request_time_from');
            $table->time('request_time_to');
            $table->timestamp('action_date')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();

            // Composite unique index
            $table->unique(['company_id', 'employee_id', 'property_id']);

            $table->foreign('company_id')->references('id')->on('companies')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('property_id')->references('id')->on('company_properties')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_requests');
    }
};
