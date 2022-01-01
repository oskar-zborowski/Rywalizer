<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('commissions', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('name', 50)->unique()->nullable();
            $table->string('description', 250)->nullable();
            $table->string('signature', 30)->comment('Nazwa serii, do której należy prowizja');
            $table->unsignedTinyInteger('version')->comment('Numer porządkowy kolejnej wersji prowizji w danej serii');
            $table->unsignedFloat('value');
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->unsignedMediumInteger('creator_id')->nullable();
            $table->unsignedMediumInteger('editor_id')->nullable();
            $table->timestamps();
        });

        Schema::table('commissions', function (Blueprint $table) {
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
        Schema::dropIfExists('commissions');
    }
}
