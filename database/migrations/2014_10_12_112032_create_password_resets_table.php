<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePasswordResetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void {
        Schema::create('password_resets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->mediumInteger('user_id')->unsigned();
            $table->string('token', 64)->unique();
            $table->tinyInteger('email_sending_counter')->unsigned();
            $table->timestamps();
        });

        Schema::table('password_resets', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void {
        Schema::dropIfExists('password_resets');
    }
}
