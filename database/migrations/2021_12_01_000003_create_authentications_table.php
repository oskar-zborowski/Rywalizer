<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuthenticationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('authentications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedTinyInteger('authentication_type_id');
            $table->unsignedMediumInteger('user_id');
            $table->unsignedInteger('device_id')->nullable();
            $table->timestamps();
        });

        Schema::table('authentications', function (Blueprint $table) {
            $table->foreign('authentication_type_id')->references('id')->on('authentication_types');
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('device_id')->references('id')->on('devices')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('authentications');
    }
}
