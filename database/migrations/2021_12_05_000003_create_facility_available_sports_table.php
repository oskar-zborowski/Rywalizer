<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacilityAvailableSportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('facility_available_sports', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->unsignedSmallInteger('facility_id');
            $table->unsignedSmallInteger('sport_id');
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });

        Schema::table('facility_available_sports', function (Blueprint $table) {
            $table->foreign('facility_id')->references('id')->on('facilities');
            $table->foreign('sport_id')->references('id')->on('default_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('facility_available_sports');
    }
}
