<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSportsAnnouncementPartnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('sports_announcement_partners', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('partner_id');
            $table->float('commission')->default(0.05);
            $table->timestamps();
        });

        Schema::table('sports_announcement_partners', function (Blueprint $table) {
            $table->foreign('partner_id')->references('id')->on('partners');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('sports_announcement_partners');
    }
}
