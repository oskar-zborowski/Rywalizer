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
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('partner_id')->unique();
            $table->float('commission_id')->default(1);
            $table->timestamps();
        });

        Schema::table('sports_announcement_partners', function (Blueprint $table) {
            $table->foreign('partner_id')->references('id')->on('partners')->cascadeOnDelete();
            $table->foreign('commission_id')->references('id')->on('commissions');
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
