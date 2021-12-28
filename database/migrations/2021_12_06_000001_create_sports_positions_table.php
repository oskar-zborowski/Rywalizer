<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSportsPositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('sports_positions', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('name', 20)->unique();
            $table->string('description', 30);
            $table->string('icon', 30)->nullable();
            $table->unsignedSmallInteger('sport_id');
            $table->unsignedMediumInteger('creator_id')->nullable();
            $table->timestamps();
        });

        Schema::table('sports_positions', function (Blueprint $table) {
            $table->foreign('sport_id')->references('id')->on('default_types');
            $table->foreign('creator_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('sports_positions');
    }
}
