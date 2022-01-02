<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnnouncementSeatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('announcement_seats', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->unsignedInteger('announcement_id');
            $table->unsignedTinyInteger('sports_position_id');
            $table->unsignedTinyInteger('occupied_seats_counter')->default(0);
            $table->unsignedTinyInteger('maximum_seats_number');
            $table->unsignedMediumInteger('creator_id')->nullable();
            $table->unsignedMediumInteger('editor_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::table('announcement_seats', function (Blueprint $table) {
            $table->foreign('announcement_id')->references('id')->on('announcements')->cascadeOnDelete();
            $table->foreign('sports_position_id')->references('id')->on('sports_positions');
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
        Schema::dropIfExists('announcement_seats');
    }
}
