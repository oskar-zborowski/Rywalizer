<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegisteredGuestActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('registered_guest_actions', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->unsignedInteger('device_id');
            $table->unsignedSmallInteger('action_type_id')->comment('Typ akcji jaką wykonał niezalogowany użytkownik i chcemy ją sobie logować w bazie danych');
            $table->timestamp('created_at');
        });

        Schema::table('registered_guest_actions', function (Blueprint $table) {
            $table->foreign('device_id')->references('id')->on('devices')->cascadeOnDelete();
            $table->foreign('action_type_id')->references('id')->on('default_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('registered_guest_actions');
    }
}
