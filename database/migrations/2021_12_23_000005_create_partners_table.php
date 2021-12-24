<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('partners', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedMediumInteger('user_id');
            $table->unsignedMediumInteger('city_id')->nullable();
            $table->string('submerchant_id', 9)->nullable();
            $table->string('business_name', 248)->nullable();
            $table->string('logo', 64)->unique()->nullable();
            $table->string('contact_email', 340)->nullable();
            $table->string('invoice_email', 340)->nullable();
            $table->string('telephone', 32)->nullable();
            $table->string('facebook_profile', 340)->nullable();
            $table->string('instagram_profile', 340)->nullable();
            $table->string('pesel', 16)->nullable();
            $table->string('nip', 16)->nullable();
            $table->string('regon', 16)->nullable();
            $table->string('street', 132)->nullable();
            $table->string('post_code', 9)->nullable();
            $table->timestamp('contact_email_verified_at')->nullable();
            $table->timestamp('invoice_email_verified_at')->nullable();
            $table->timestamp('telephone_verified_at')->nullable();
            $table->timestamp('przelewy24_verified_at')->nullable();
            $table->timestamps();
        });

        Schema::table('partners', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('city_id')->references('id')->on('cities');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('partners');
    }
}
