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
        Schema::table('companies', function (Blueprint $table) {
            $table->text('mission')->nullable()->change();
            $table->text('vission')->nullable()->change();
            $table->text('values')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('mission')->nullable()->change();
            $table->string('vission')->nullable()->change();
            $table->string('values')->nullable()->change();
        });
    }
};
