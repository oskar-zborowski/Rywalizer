<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnnouncementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('announcements', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->unsignedSmallInteger('announcement_partner_id')->nullable();
            $table->unsignedSmallInteger('facility_id')->nullable();
            $table->unsignedSmallInteger('sport_id');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->unsignedTinyInteger('minimum_skill_level_id')->nullable();
            $table->unsignedSmallInteger('gender_id')->nullable();
            $table->unsignedSmallInteger('age_category_id')->nullable();
            $table->unsignedTinyInteger('minimal_age')->nullable();
            $table->unsignedTinyInteger('maximum_age')->nullable();
            $table->unsignedSmallInteger('game_variant_id');
            $table->unsignedMediumInteger('ticket_price'); // Cena w groszach
            $table->unsignedMediumInteger('front_picture_id')->nullable();
            $table->unsignedMediumInteger('background_picture_id')->nullable();
            $table->char('code', 12); // Kodowane natywnie
            $table->string('description', 2000)->nullable(); // Kodowane natywnie
            $table->unsignedTinyInteger('current_participants_number')->default(0);
            $table->unsignedTinyInteger('maximum_participants_number');
            $table->dateTime('visible_at')->nullable();
            $table->unsignedSmallInteger('announcement_type_id')->nullable();
            $table->unsignedSmallInteger('announcement_status_id');
            $table->unsignedMediumInteger('creator_id')->nullable();
            $table->unsignedMediumInteger('editor_id')->nullable();
            $table->boolean('is_automatically_approved');
            $table->boolean('is_public');
            $table->timestamps();
        });

        Schema::table('announcements', function (Blueprint $table) {
            $table->foreign('announcement_partner_id')->references('id')->on('partner_settings')->nullOnDelete();
            $table->foreign('facility_id')->references('id')->on('facilities')->nullOnDelete();
            $table->foreign('sport_id')->references('id')->on('default_types');
            $table->foreign('minimum_skill_level_id')->references('id')->on('minimum_skill_levels');
            $table->foreign('gender_id')->references('id')->on('default_types');
            $table->foreign('age_category_id')->references('id')->on('default_types');
            $table->foreign('game_variant_id')->references('id')->on('default_types');
            $table->foreign('front_picture_id')->references('id')->on('pictures')->nullOnDelete();
            $table->foreign('background_picture_id')->references('id')->on('pictures')->nullOnDelete();
            $table->foreign('announcement_type_id')->references('id')->on('default_types');
            $table->foreign('announcement_status_id')->references('id')->on('default_types');
            $table->foreign('creator_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('editor_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('announcements');
    }
}
