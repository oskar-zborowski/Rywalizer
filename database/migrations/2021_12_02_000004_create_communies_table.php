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
            $table->unsignedMediumInteger('creator_id')->nullable();
            $table->unsignedMediumInteger('supervisor_id')->nullable();
            $table->string('name', 30);
            $table->polygon('boundary')->nullable();
            $table->boolean('is_visible')->default(0);
            $table->timestamps();
        });

        Schema::table('communies', function (Blueprint $table) {
            $table->foreign('poviat_id')->references('id')->on('poviats');
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
        Schema::dropIfExists('communies');
    }
}
