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
            $table->char('submerchant_id', 9)->unique()->nullable(); // Kodowane natywnie
            $table->string('business_name', 268)->nullable(); // Kodowane natywnie
            $table->char('logo', 64)->unique()->nullable(); // Kodowane natywnie
            $table->string('contact_email', 340)->unique()->nullable(); // Kodowane natywnie
            $table->string('invoice_email', 340)->nullable(); // Kodowane natywnie
            $table->char('telephone', 32)->unique()->nullable(); // Kodowane natywnie
            $table->string('facebook_profile', 340)->nullable(); // Kodowane natywnie
            $table->string('instagram_profile', 340)->nullable(); // Kodowane natywnie
            $table->string('website', 340)->nullable(); // Kodowane natywnie
            $table->char('nip', 16)->unique()->nullable(); // Kodowane natywnie
            $table->char('street', 108)->nullable(); // Kodowane natywnie
            $table->char('post_code', 9)->nullable(); // Kodowane natywnie
            $table->unsignedMediumInteger('city_id')->nullable();
            $table->timestamp('przelewy24_verified_at')->nullable();
            $table->timestamp('contact_email_verified_at')->nullable();
            $table->timestamp('invoice_email_verified_at')->nullable();
            $table->timestamp('telephone_verified_at')->nullable();
            $table->timestamps();
        });

        Schema::table('partners', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('city_id')->references('id')->on('areas');
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
