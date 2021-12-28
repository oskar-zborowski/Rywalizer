<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('ratings', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->morphs('rateable');
            $table->unsignedTinyInteger('rating')->nullable();
            $table->text('comment')->nullable();
            $table->unsignedSmallInteger('usefulness')->default(0);
            $table->unsignedMediumInteger('answer_to_id')->nullable();
            $table->unsignedMediumInteger('user_id')->nullable();
            $table->timestamps();
        });

        Schema::table('ratings', function (Blueprint $table) {
            $table->foreign('answer_to_id')->references('id')->on('ratings')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('ratings');
    }
}
