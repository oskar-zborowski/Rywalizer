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
            $table->morphs('discountable');
            $table->unsignedMediumInteger('discount_code_id');
            $table->unsignedMediumInteger('creator_id')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::table('discounts', function (Blueprint $table) {
            $table->foreign('discount_code_id')->references('id')->on('discount_codes')->cascadeOnDelete();
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
