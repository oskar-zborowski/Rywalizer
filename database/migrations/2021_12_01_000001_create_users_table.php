<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('users', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->char('first_name', 40); // Kodowane natywnie
            $table->char('last_name', 40); // Kodowane natywnie
            $table->string('email', 340)->unique()->nullable(); // Kodowane natywnie
            $table->char('telephone', 32)->unique()->nullable(); // Kodowane natywnie
            $table->char('password', 60)->nullable(); // Kodowane natywnie
            $table->char('birth_date', 16)->nullable(); // Kodowane natywnie
            $table->unsignedSmallInteger('gender_id')->nullable();
            $table->unsignedSmallInteger('role_id');
            $table->unsignedMediumInteger('city_id')->nullable();
            $table->char('address_coordinates', 28)->nullable()->comment('Struktura: 12.3456789;12.3456789'); // Kodowane natywnie
            $table->string('facebook_profile', 340)->nullable(); // Kodowane natywnie
            $table->string('instagram_profile', 340)->nullable(); // Kodowane natywnie
            $table->string('website', 340)->nullable(); // Kodowane natywnie
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('telephone_verified_at')->nullable();
            $table->timestamp('last_time_name_changed_at')->nullable()->comment('Data ostatniej zmiany imienia, bądź nazwiska');
            $table->timestamp('last_time_password_changed_at')->nullable();
            $table->timestamp('verified_at')->nullable()->comment('Zweryfikowanie użytkownika jako aktywnego, a nie potencjalne fake konto');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('users');
    }
}
