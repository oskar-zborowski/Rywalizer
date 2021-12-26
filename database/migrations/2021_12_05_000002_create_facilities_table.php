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
            $table->string('name', 268)->nullable(); // Kodowane natywnie
            $table->string('logo', 64)->unique()->nullable(); // Kodowane natywnie
            $table->string('street', 108)->nullable(); // Kodowane natywnie
            $table->string('post_code', 9)->nullable(); // Kodowane natywnie
            $table->unsignedMediumInteger('city_id')->nullable();
            $table->string('address_coordinates', 20)->nullable(); // Kodowane natywnie
            $table->string('contact_email', 340)->unique()->nullable(); // Kodowane natywnie
            $table->string('telephone', 32)->unique()->nullable(); // Kodowane natywnie
            $table->string('facebook_profile', 340)->unique()->nullable(); // Kodowane natywnie
            $table->string('instagram_profile', 340)->unique()->nullable(); // Kodowane natywnie
            $table->string('website', 340)->unique()->nullable(); // Kodowane natywnie
            $table->unsignedSmallInteger('facility_partner_id')->nullable();
            $table->unsignedTinyInteger('facility_type_id')->nullable();
            $table->unsignedTinyInteger('places_number')->nullable();
            $table->unsignedTinyInteger('gender_id')->nullable();
            $table->unsignedTinyInteger('age_category_id')->nullable();
            $table->unsignedTinyInteger('minimal_age')->nullable();
            $table->unsignedTinyInteger('maximum_age')->nullable();
            $table->string('description', 2668)->nullable(); // Kodowane natywnie
            $table->unsignedMediumInteger('price_from')->nullable();
            $table->unsignedFloat('occupancy_level');
            $table->boolean('is_visible')->default(0);
            $table->timestamps();
        });

        Schema::table('facilities', function (Blueprint $table) {
            $table->foreign('city_id')->references('id')->on('cities');
            $table->foreign('facility_partner_id')->references('id')->on('facility_partners')->nullOnDelete();
            $table->foreign('facility_type_id')->references('id')->on('facility_types');
            $table->foreign('gender_id')->references('id')->on('genders');
            $table->foreign('age_category_id')->references('id')->on('age_categories');
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
