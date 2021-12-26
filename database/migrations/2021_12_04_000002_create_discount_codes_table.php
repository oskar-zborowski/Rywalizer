<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscountCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('discount_codes', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('code', 40)->unique()->nullable(); // Kodowane natywnie
            $table->string('description', 2000)->nullable();
            $table->string('icon', 64)->nullable(); // Kodowane natywnie
            $table->unsignedTinyInteger('discount_type_id');
            $table->unsignedTinyInteger('discount_value_type_id');
            $table->unsignedMediumInteger('value');
            $table->unsignedSmallInteger('payer_id');
            $table->unsignedMediumInteger('creator_id');
            $table->timestamps();
        });

        Schema::table('discount_codes', function (Blueprint $table) {
            $table->foreign('discount_type_id')->references('id')->on('discount_types');
            $table->foreign('discount_value_type_id')->references('id')->on('discount_value_types');
            $table->foreign('payer_id')->references('id')->on('partners');
            $table->foreign('creator_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('discount_codes');
    }
}
