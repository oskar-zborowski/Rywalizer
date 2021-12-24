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
            $table->mediumIncrements('id');
            $table->unsignedTinyInteger('discount_type_id');
            $table->unsignedMediumInteger('payer_id');
            $table->string('code', 40)->unique()->nullable(); // Kodowane natywnie
            $table->mediumInteger('value');
            $table->string('description', 100);
        });

        Schema::table('discount_codes', function (Blueprint $table) {
            $table->foreign('discount_type_id')->references('id')->on('discount_types');
            $table->foreign('payer_id')->references('id')->on('partners');
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
