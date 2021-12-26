<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscountValueTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('discount_value_types', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('name', 20)->unique();
            $table->string('description', 30);
            $table->unsignedMediumInteger('creator_id')->nullable();
            $table->timestamps();
        });

        Schema::table('discount_value_types', function (Blueprint $table) {
            $table->foreign('creator_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('discount_value_types');
    }
}
