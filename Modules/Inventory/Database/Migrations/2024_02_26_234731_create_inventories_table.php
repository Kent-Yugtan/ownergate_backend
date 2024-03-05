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
        Schema::create('inventory_category_types', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->timestamps();
        });

        Schema::create('inventory_types', function (Blueprint $table) {
            $table->id();
            $table->string("item_name");
            $table->timestamps();
        });

        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id");
            $table->unsignedBigInteger("inventory_category_id");
            $table->unsignedBigInteger("inventory_type_id");
            $table->string("item_id");
            $table->string("description_name");
            $table->string("level");
            $table->string("full_description");
            $table->string("country");
            $table->string("city");
            $table->string("area");
            $table->string("discount");
            $table->string("current_date");
            $table->string("account_id");
            $table->string("opening_balance");
            $table->string("vendor_id");
            $table->string("start_date");
            $table->string("end_date");
            $table->text("item_id_details");
            $table->tinyInteger("is_active")->default(0);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('inventory_category_id')->references('id')->on('inventory_category_types')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('inventory_type_id')->references('id')->on('inventory_types')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('inventory_attachments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("inventory_id");
            $table->string("title");
            $table->string("path");
            $table->timestamps();

            $table->foreign('inventory_id')->references('id')->on('inventories')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_category_types');
        Schema::dropIfExists('inventory_types');
        Schema::dropIfExists('inventories');
        Schema::dropIfExists('inventory_attachments');
    }
};
