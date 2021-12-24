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
            $table->unsignedMediumInteger('creator_id');
            $table->unsignedMediumInteger('supervisor_id');
            $table->string('name', 40);
            $table->polygon('boundary')->nullable();
            $table->boolean('is_visible')->default(0);
            $table->timestamps();
        });

        Schema::table('cities', function (Blueprint $table) {
            $table->foreign('commune_id')->references('id')->on('communies');
            $table->foreign('creator_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('supervisor_id')->references('id')->on('users')->nullOnDelete();
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
