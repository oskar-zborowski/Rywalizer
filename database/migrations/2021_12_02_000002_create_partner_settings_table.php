<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartnerSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('partner_settings', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('partner_id');
            $table->unsignedSmallInteger('partner_type_id');
            $table->unsignedTinyInteger('commission_id');
            $table->unsignedSmallInteger('visible_name_id');
            $table->unsignedSmallInteger('visible_picture_id')->nullable();
            $table->unsignedSmallInteger('visible_email_id')->nullable();
            $table->unsignedSmallInteger('visible_telephone_id')->nullable();
            $table->unsignedSmallInteger('visible_facebook_id')->nullable();
            $table->unsignedSmallInteger('visible_instagram_id')->nullable();
            $table->unsignedSmallInteger('visible_website_id')->nullable();
            $table->unsignedFloat('avarage_rating')->nullable();
            $table->unsignedSmallInteger('ratings_number')->default(0);
            $table->timestamps();
        });

        Schema::table('partner_settings', function (Blueprint $table) {
            $table->foreign('partner_id')->references('id')->on('partners')->cascadeOnDelete();
            $table->foreign('partner_type_id')->references('id')->on('default_types');
            $table->foreign('commission_id')->references('id')->on('commissions');
            $table->foreign('visible_name_id')->references('id')->on('default_types');
            $table->foreign('visible_picture_id')->references('id')->on('default_types');
            $table->foreign('visible_email_id')->references('id')->on('default_types');
            $table->foreign('visible_telephone_id')->references('id')->on('default_types');
            $table->foreign('visible_facebook_id')->references('id')->on('default_types');
            $table->foreign('visible_instagram_id')->references('id')->on('default_types');
            $table->foreign('visible_website_id')->references('id')->on('default_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('partner_settings');
    }
}
