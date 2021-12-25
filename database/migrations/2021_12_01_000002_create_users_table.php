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
            $table->string('first_name', 40); // Kodowane natywnie
            $table->string('last_name', 40); // Kodowane natywnie
            $table->string('email', 340)->unique()->nullable(); // Kodowane natywnie
            $table->string('password', 60)->nullable(); // Kodowane natywnie
            $table->string('avatar', 64)->unique()->nullable(); // Kodowane natywnie
            $table->string('birth_date', 16)->nullable(); // Kodowane natywnie
            $table->unsignedMediumInteger('city_id')->nullable();
            $table->string('address_coordinates', 20)->nullable(); // Kodowane natywnie
            $table->string('telephone', 32)->unique()->nullable(); // Kodowane natywnie
            $table->string('facebook_profile', 340)->unique()->nullable(); // Kodowane natywnie
            $table->string('instagram_profile', 340)->unique()->nullable(); // Kodowane natywnie
            $table->unsignedTinyInteger('gender_id')->nullable();
            $table->unsignedTinyInteger('role_id')->default(1);
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('telephone_verified_at')->nullable();
            $table->timestamp('last_time_name_changed')->nullable();
            $table->timestamp('last_time_password_changed')->nullable();
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
