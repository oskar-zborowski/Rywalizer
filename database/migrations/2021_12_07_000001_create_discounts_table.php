<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('discounts', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->unsignedMediumInteger('discount_code_id');
            $table->unsignedSmallInteger('object_type_id');
            $table->unsignedInteger('object_id');
            $table->unsignedMediumInteger('creator_id')->nullable();
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });

        Schema::table('discounts', function (Blueprint $table) {
            $table->foreign('discount_code_id')->references('id')->on('discounts')->cascadeOnDelete();
            $table->foreign('object_type_id')->references('id')->on('default_types');
            $table->foreign('creator_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('discounts');
    }
}
