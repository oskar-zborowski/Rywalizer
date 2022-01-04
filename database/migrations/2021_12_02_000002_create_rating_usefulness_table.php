<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRatingUsefulnessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('rating_usefulness', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->nullableMorphs('evaluator');
            $table->unsignedMediumInteger('rating_id');
            $table->boolean('is_usefulness')->comment('False -> -, True -> +');
            $table->timestamps();
        });

        Schema::table('rating_usefulness', function (Blueprint $table) {
            $table->foreign('rating_id')->references('id')->on('ratings')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('rating_usefulness');
    }
}
