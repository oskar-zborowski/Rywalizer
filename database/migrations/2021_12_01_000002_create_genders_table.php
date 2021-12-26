<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGendersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('genders', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('name', 10)->unique();
            $table->string('description', 20);
            $table->string('icon', 20)->unique();
            $table->unsignedMediumInteger('creator_id')->nullable();
            $table->timestamps();
        });

        Schema::table('genders', function (Blueprint $table) {
            $table->foreign('creator_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('genders');
    }
}
