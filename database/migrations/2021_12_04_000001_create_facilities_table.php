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
            $table->string('street', 80)->nullable();
            $table->char('post_code', 5)->nullable();
            $table->unsignedMediumInteger('city_id')->nullable();
            $table->point('address_coordinates')->nullable();
            $table->string('contact_email', 254)->nullable();
            $table->string('telephone', 24)->nullable();
            $table->string('facebook_profile', 255)->nullable();
            $table->string('instagram_profile', 255)->nullable();
            $table->string('website', 255)->nullable();
            $table->unsignedSmallInteger('facility_partner_id')->nullable();
            $table->unsignedSmallInteger('facility_type_id')->nullable()->comment('Typ obiektu, np. hala, lodowisko etc.');
            $table->unsignedTinyInteger('places_number')->nullable()->comment('Liczba różnego typu wynajmowanych miejsc w obiekcie');
            $table->unsignedSmallInteger('gender_id')->nullable();
            $table->unsignedSmallInteger('age_category_id')->nullable()->comment('Typ kategorii wiekowej, np. dorośli, dzieci etc.');
            $table->unsignedTinyInteger('minimal_age')->nullable();
            $table->unsignedTinyInteger('maximum_age')->nullable();
            $table->text('description')->nullable();
            $table->unsignedMediumInteger('price_from')->nullable();
            $table->unsignedFloat('occupancy_level')->default(0);
            $table->unsignedFloat('avarage_rating')->nullable();
            $table->unsignedSmallInteger('ratings_counter')->default(0);
            $table->unsignedMediumInteger('creator_id')->nullable();
            $table->unsignedMediumInteger('editor_id')->nullable();
            $table->unsignedMediumInteger('supervisor_id')->nullable();
            $table->timestamp('contact_email_verified_at')->nullable();
            $table->timestamp('telephone_verified_at')->nullable();
            $table->timestamp('visible_at')->nullable();
            $table->timestamp('deleted_at')->nullable()->comment('Uzupełniane tylko w przypadku kiedy nie możemy usunąć obiektu');
            $table->timestamps();
        });

        Schema::table('facilities', function (Blueprint $table) {
            $table->foreign('city_id')->references('id')->on('areas');
            $table->foreign('facility_partner_id')->references('id')->on('partner_settings')->nullOnDelete();
            $table->foreign('facility_type_id')->references('id')->on('default_types');
            $table->foreign('gender_id')->references('id')->on('default_types');
            $table->foreign('age_category_id')->references('id')->on('default_types');
            $table->foreign('creator_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('editor_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('supervisor_id')->references('id')->on('users')->nullOnDelete();
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
