<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVoivodeshipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('voivodeships', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->unsignedTinyInteger('country_id');
            $table->unsignedMediumInteger('creator_id');
            $table->unsignedMediumInteger('supervisor_id');
            $table->string('name', 20);
            $table->polygon('boundary')->nullable();
            $table->boolean('is_visible')->default(0);
            $table->timestamps();
        });

        Schema::table('voivodeships', function (Blueprint $table) {
            $table->foreign('country_id')->references('id')->on('countries');
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
        Schema::dropIfExists('voivodeships');
    }
}
