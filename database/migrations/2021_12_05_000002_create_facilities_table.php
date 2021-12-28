<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('facilities', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name', 200)->nullable();
            $table->string('logo', 48)->unique()->nullable();
            $table->string('street', 80)->nullable();
            $table->string('post_code', 5)->nullable();
            $table->unsignedMediumInteger('city_id')->nullable();
            $table->point('address_coordinates')->nullable();
            $table->string('contact_email', 340)->unique()->nullable();
            $table->string('telephone', 32)->unique()->nullable();
            $table->string('facebook_profile', 340)->unique()->nullable();
            $table->string('instagram_profile', 340)->unique()->nullable();
            $table->string('website', 340)->unique()->nullable();
            $table->unsignedSmallInteger('facility_partner_id')->nullable();
            $table->unsignedSmallInteger('facility_type_id')->nullable();
            $table->unsignedTinyInteger('places_number')->nullable();
            $table->unsignedSmallInteger('gender_id')->nullable();
            $table->unsignedSmallInteger('age_category_id')->nullable();
            $table->unsignedTinyInteger('minimal_age')->nullable();
            $table->unsignedTinyInteger('maximum_age')->nullable();
            $table->string('description', 4000)->nullable();
            $table->unsignedMediumInteger('price_from')->nullable();
            $table->unsignedFloat('occupancy_level');
            $table->unsignedFloat('avarage_rating')->nullable();
            $table->smallInteger('ratings_number')->default(0);
            $table->boolean('is_visible')->default(0);
            $table->timestamps();
        });

        Schema::table('facilities', function (Blueprint $table) {
            $table->foreign('city_id')->references('id')->on('areas');
            $table->foreign('facility_partner_id')->references('id')->on('facility_partners')->nullOnDelete();
            $table->foreign('facility_type_id')->references('id')->on('default_types');
            $table->foreign('gender_id')->references('id')->on('default_types');
            $table->foreign('age_category_id')->references('id')->on('default_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('facilities');
    }
}
