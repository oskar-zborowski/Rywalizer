<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSportsAnnouncementParticipantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('sports_announcement_participants', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->unsignedMediumInteger('user_id');
            $table->unsignedInteger('sports_announcement_seat_id');
            $table->unsignedInteger('sports_announcement_payment_id')->nullable();
            $table->unsignedInteger('transaction_id')->nullable();
            $table->timestamps();
        });

        Schema::table('sports_announcement_participants', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('sports_announcement_seat_id')->references('id')->on('sports_announcement_seats');
            $table->foreign('sports_announcement_payment_id')->references('id')->on('sports_announcement_payments');
            $table->foreign('transaction_id')->references('id')->on('transactions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('sports_announcement_participants');
    }
}
