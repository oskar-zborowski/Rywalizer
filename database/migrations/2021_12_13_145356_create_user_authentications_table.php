<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserAuthenticationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('user_authentications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->mediumInteger('user_id')->unsigned();
            $table->bigInteger('device_id')->unsigned();
            $table->tinyInteger('authentication_type_id')->unsigned();
            $table->timestamps();
        });

        Schema::table('user_authentications', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('device_id')->references('id')->on('devices');
            $table->foreign('authentication_type_id')->references('id')->on('authentication_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('user_authentications');
    }
}
