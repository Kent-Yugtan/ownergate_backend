<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('galleries', function (Blueprint $table) {
            $table->id();
            $table->string('property_id', 191); // Adjust length if needed
            $table->string('assigned_to', 191);  // Adjust length if needed
            $table->string('maintained_by', 191); // Adjust length if needed
            $table->unsignedBigInteger('company_id'); // Ensure this matches the `id` type in `companies`

            // Define foreign keys
            $table->foreign('assigned_to')->references('og_code')->on('users')->onDelete('cascade');
            $table->foreign('maintained_by')->references('og_code')->on('users')->onDelete('cascade');
            $table->foreign('property_id')->references('og_code')->on('company_properties')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('galleries');
    }
};
