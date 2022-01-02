<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacilityOpeningHoursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('facility_opening_hours', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('facility_id')->unique();
            $table->time('monday_from')->nullable();
            $table->time('monday_to')->nullable();
            $table->time('tuesday_from')->nullable();
            $table->time('tuesday_to')->nullable();
            $table->time('wednesday_from')->nullable();
            $table->time('wednesday_to')->nullable();
            $table->time('thursday_from')->nullable();
            $table->time('thursday_to')->nullable();
            $table->time('friday_from')->nullable();
            $table->time('friday_to')->nullable();
            $table->time('saturday_from')->nullable();
            $table->time('saturday_to')->nullable();
            $table->time('sunday_from')->nullable();
            $table->time('sunday_to')->nullable();
            $table->unsignedMediumInteger('creator_id')->nullable();
            $table->unsignedMediumInteger('editor_id')->nullable();
            $table->unsignedMediumInteger('supervisor_id')->nullable();
            $table->timestamp('visible_at')->nullable();
            $table->timestamps();
        });

        Schema::table('facility_opening_hours', function (Blueprint $table) {
            $table->foreign('facility_id')->references('id')->on('facilities')->cascadeOnDelete();
            $table->foreign('creator_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('editor_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('supervisor_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('facility_opening_hours');
    }
}
