<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSportsAnnouncementPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('sports_announcement_payments', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->unsignedInteger('sports_announcement_id');
            $table->unsignedTinyInteger('payment_type_id');
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });

        Schema::table('sports_announcement_payments', function (Blueprint $table) {
            $table->foreign('sports_announcement_id')->references('id')->on('sports_announcements')->cascadeOnDelete();
            $table->foreign('payment_type_id')->references('id')->on('payment_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('sports_announcement_payments');
    }
}
