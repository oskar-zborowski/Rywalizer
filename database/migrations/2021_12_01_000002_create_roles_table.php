<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('roles', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('name', 30)->unique();
            $table->string('description', 30);
            $table->string('access_level', 4);
            $table->unsignedMediumInteger('creator_id')->nullable();
            $table->timestamps();
        });

        Schema::table('roles', function (Blueprint $table) {
            $table->foreign('creator_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('roles');
    }
}
