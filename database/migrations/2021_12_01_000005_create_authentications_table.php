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
            $table->integerIncrements('id');
            $table->unsignedMediumInteger('user_id');
            $table->unsignedSmallInteger('authentication_type_id');
            $table->unsignedInteger('device_id')->nullable();
            $table->timestamps();
        });

        Schema::table('authentications', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users'); //->cascadeOnDelete();
            $table->foreign('authentication_type_id')->references('id')->on('default_types');
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
