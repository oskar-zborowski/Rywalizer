<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacilityPlacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('facility_places', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->unsignedSmallInteger('facility_id');
            $table->unsignedSmallInteger('facility_type_id');
            $table->string('name', 30);
            $table->unsignedMediumInteger('unit'); // Czas w minutach
            $table->unsignedMediumInteger('price_per_unit'); // Cena w groszach
            $table->unsignedMediumInteger('minimum_unit_booking')->nullable();
            $table->unsignedMediumInteger('maximum_unit_booking')->nullable();
            $table->unsignedMediumInteger('creator_id')->nullable();
            $table->boolean('is_visible')->default(0);
            $table->timestamps();
        });

        Schema::table('facility_places', function (Blueprint $table) {
            $table->foreign('facility_id')->references('id')->on('facilities')->cascadeOnDelete();
            $table->foreign('facility_type_id')->references('id')->on('default_types');
            $table->foreign('creator_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('facility_places');
    }
}
