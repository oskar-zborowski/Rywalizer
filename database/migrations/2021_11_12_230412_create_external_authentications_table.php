<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExternalAuthenticationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('external_authentications', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->string('authentication_id');
            $table->mediumInteger('user_id')->unsigned();
            $table->tinyInteger('provider_type_id')->unsigned();
        });

        Schema::table('external_authentications', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('provider_type_id')->references('id')->on('provider_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('providers');
    }
}
