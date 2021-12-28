<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacilityEquipmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('facility_equipments', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->unsignedSmallInteger('facility_id');
            $table->unsignedSmallInteger('equipment_id');
            $table->unsignedMediumInteger('creator_id')->nullable();
            $table->boolean('is_visible')->default(1);
            $table->timestamps();
        });

        Schema::table('facility_equipments', function (Blueprint $table) {
            $table->foreign('facility_id')->references('id')->on('facilities')->cascadeOnDelete();
            $table->foreign('equipment_id')->references('id')->on('default_types');
            $table->foreign('creator_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('facility_equipments');
    }
}
