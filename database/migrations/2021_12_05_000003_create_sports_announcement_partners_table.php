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
            $table->float('commission_id');
            $table->unsignedTinyInteger('name_id')->nullable();
            $table->unsignedTinyInteger('picture_id')->nullable();
            $table->unsignedTinyInteger('email_id')->nullable();
            $table->unsignedTinyInteger('telephone_id')->nullable();
            $table->unsignedTinyInteger('facebook_id')->nullable();
            $table->unsignedTinyInteger('instagram_id')->nullable();
            $table->timestamps();
        });

        Schema::table('sports_announcement_partners', function (Blueprint $table) {
            $table->foreign('partner_id')->references('id')->on('partners')->cascadeOnDelete();
            $table->foreign('commission_id')->references('id')->on('commissions');
            $table->foreign('name_id')->references('id')->on('visible_fields');
            $table->foreign('picture_id')->references('id')->on('visible_fields');
            $table->foreign('email_id')->references('id')->on('visible_fields');
            $table->foreign('telephone_id')->references('id')->on('visible_fields');
            $table->foreign('facebook_id')->references('id')->on('visible_fields');
            $table->foreign('instagram_id')->references('id')->on('visible_fields');
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
