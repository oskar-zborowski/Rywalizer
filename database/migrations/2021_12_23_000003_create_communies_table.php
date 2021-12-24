<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommuniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('communies', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('poviat_id');
            $table->string('name', 27);
            $table->polygon('boundary')->nullable();
            $table->boolean('is_active')->default(0);
            $table->timestamps();
        });

        Schema::table('communies', function (Blueprint $table) {
            $table->foreign('poviat_id')->references('id')->on('poviats');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('communies');
    }
}
