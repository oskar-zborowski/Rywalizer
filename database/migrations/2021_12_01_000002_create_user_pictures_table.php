<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserPicturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('user_pictures', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->char('filename', 64)->unique(); // Kodowane natywnie
            $table->unsignedMediumInteger('user_id');
            $table->timestamps();
        });

        Schema::table('user_pictures', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('user_pictures');
    }
}
