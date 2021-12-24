<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePoviatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('poviats', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedTinyInteger('voivodeship_id');
            $table->string('name', 25);
            $table->polygon('boundary')->nullable();
            $table->boolean('is_active')->default(0);
            $table->timestamps();
        });

        Schema::table('poviats', function (Blueprint $table) {
            $table->foreign('voivodeship_id')->references('id')->on('voivodeships');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('poviats');
    }
}
