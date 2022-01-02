<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacilityPlaceBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('facility_place_bookings', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->unsignedMediumInteger('user_id')->nullable();
            $table->unsignedMediumInteger('facility_place_id')->nullable();
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->unsignedSmallInteger('booking_status_id')->comment('Status rezerwacji, np. zaakceptowana, odrzucona etc.');
            $table->timestamps();
        });

        Schema::table('facility_place_bookings', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('facility_place_id')->references('id')->on('facility_places')->nullOnDelete();
            $table->foreign('booking_status_id')->references('id')->on('default_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('facility_place_bookings');
    }
}
