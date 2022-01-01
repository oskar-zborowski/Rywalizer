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
            $table->morphs('evaluable');
            $table->nullableMorphs('evaluator');
            $table->unsignedMediumInteger('answer_to_id')->nullable()->comment('ID komentarza do którego bieżący komentarz się odnosi');
            $table->unsignedTinyInteger('rating')->nullable();
            $table->text('comment')->nullable();
            $table->unsignedSmallInteger('positive_counter')->default(0)->comment('Liczba pozytywnych reakcji na komentarz');
            $table->unsignedSmallInteger('negative_counter')->default(0)->comment('Liczba negatywnych reakcji na komentarz');
            $table->timestamps();
        });

        Schema::table('ratings', function (Blueprint $table) {
            $table->foreign('answer_to_id')->references('id')->on('ratings')->cascadeOnDelete();
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
