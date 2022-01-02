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
            $table->char('code', 40)->nullable()->comment('Kod zniżkowy, który będzie musiał wprowadzić klient'); // Kodowane natywnie
            $table->text('description')->nullable();
            $table->unsignedSmallInteger('discount_type_id')->comment('Typ zniżki, np. zniżka na każdą drugą godzinę');
            $table->unsignedSmallInteger('discount_value_type_id')->comment('Typ wartości zniżki, np. zniżka procentowa albo zniżka kwotowa');
            $table->unsignedMediumInteger('value')->comment('Wartość wyrażona w groszach lub procentach');
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->unsignedSmallInteger('payer_id')->comment('ID partnera, który pokrywa różnicę');
            $table->unsignedMediumInteger('creator_id')->nullable();
            $table->unsignedMediumInteger('editor_id')->nullable();
            $table->boolean('is_active');
            $table->boolean('is_visible');
            $table->timestamps();
        });

        Schema::table('discount_codes', function (Blueprint $table) {
            $table->foreign('discount_type_id')->references('id')->on('default_types');
            $table->foreign('discount_value_type_id')->references('id')->on('default_types');
            $table->foreign('payer_id')->references('id')->on('partners')->cascadeOnDelete();
            $table->foreign('creator_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('editor_id')->references('id')->on('users')->nullOnDelete();
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
