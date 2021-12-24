<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSportsAnnouncementSeatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('sports_announcement_seats', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->unsignedInteger('sports_announcement_id');
            $table->unsignedTinyInteger('sports_position_id');
            $table->unsignedTinyInteger('occupied_seats_number')->default(0);
            $table->unsignedTinyInteger('maximum_seats_number');
            $table->timestamps();
        });

        Schema::table('sports_announcement_seats', function (Blueprint $table) {
            $table->foreign('sports_announcement_id')->references('id')->on('sports_announcements')->cascadeOnDelete();
            $table->foreign('sports_position_id')->references('id')->on('sports_positions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('sports_announcement_seats');
    }
}
