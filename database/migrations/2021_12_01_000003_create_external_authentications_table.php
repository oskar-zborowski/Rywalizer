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
    public function up() {
        Schema::create('external_authentications', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->string('external_authentication_id', 340); // Kodowane natywnie
            $table->unsignedTinyInteger('provider_id');
            $table->unsignedMediumInteger('user_id');
            $table->timestamps();
        });

        Schema::table('external_authentications', function (Blueprint $table) {
            $table->foreign('provider_id')->references('id')->on('providers');
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('external_authentications');
    }
}
