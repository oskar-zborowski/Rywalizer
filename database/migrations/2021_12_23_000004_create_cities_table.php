<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('cities', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->unsignedSmallInteger('commune_id');
            $table->string('name', 32);
            $table->polygon('boundary')->nullable();
            $table->boolean('is_active')->default(0);
            $table->timestamps();
        });

        Schema::table('cities', function (Blueprint $table) {
            $table->foreign('commune_id')->references('id')->on('communies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('cities');
    }
}
