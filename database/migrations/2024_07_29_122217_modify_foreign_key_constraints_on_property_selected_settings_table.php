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
        Schema::table('property_selected_settings', function (Blueprint $table) {

            $table->dropForeign(['property_id']);
            $table->foreign('property_id')
                  ->references('id')
                  ->on('company_properties')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('property_selected_settings', function (Blueprint $table) {

            $table->dropForeign(['property_id']);
            $table->foreign('property_id')
                  ->references('id')
                  ->on('company_properties');
        });
    }
};
