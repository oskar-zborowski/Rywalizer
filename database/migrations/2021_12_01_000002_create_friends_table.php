<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFriendsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('friends', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->unsignedMediumInteger('requesting_user_id');
            $table->unsignedMediumInteger('responding_user_id');
            $table->timestamp('responding_user_displayed_at')->nullable()->comment('Data kiedy użytkownik wyświetlił informację o prośbie dodania do znajomych');
            $table->timestamp('requesting_user_displayed_at')->nullable()->comment('Data kiedy użytkownik wyświetlił informację o zaakceptowaniu prośby dodania do znajomych');
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamps();
        });

        Schema::table('friends', function (Blueprint $table) {
            $table->foreign('requesting_user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('responding_user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('friends');
    }
}
