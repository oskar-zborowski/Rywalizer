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
    public function up(): void {
        Schema::create('users', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->string('first_name', 40);
            $table->string('last_name', 40);
            $table->string('email', 340)->unique()->nullable();
            $table->string('password', 60)->nullable();
            $table->string('avatar', 64)->unique()->nullable();
            $table->string('birth_date', 16)->nullable();
            $table->string('address_coordinates', 20)->nullable();
            $table->string('telephone', 32)->unique()->nullable();
            $table->string('facebook_profile', 340)->unique()->nullable();
            $table->string('instagram_profile', 340)->unique()->nullable();
            $table->tinyInteger('gender_type_id')->unsigned()->nullable();
            $table->tinyInteger('role_type_id')->unsigned()->default(1);
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('account_deleted_at')->nullable();
            $table->timestamp('account_blocked_at')->nullable();
            $table->timestamp('last_logged_in')->nullable();
            $table->timestamp('last_time_name_changed')->nullable();
            $table->timestamp('last_time_password_changed')->nullable();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreign('gender_type_id')->references('id')->on('gender_types');
            $table->foreign('role_type_id')->references('id')->on('role_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void {
        Schema::dropIfExists('users');
    }
}
