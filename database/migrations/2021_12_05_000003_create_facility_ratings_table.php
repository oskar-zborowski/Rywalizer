<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacilityRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('facility_ratings', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->unsignedTinyInteger('rating');
            $table->string('comment', 3000)->nullable();
            $table->unsignedMediumInteger('answer_to_id')->nullable();
            $table->unsignedMediumInteger('user_id')->nullable();
            $table->timestamps();
        });

        Schema::table('facility_ratings', function (Blueprint $table) {
            $table->foreign('answer_to_id')->references('id')->on('announcement_ratings')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('facility_ratings');
    }
}
