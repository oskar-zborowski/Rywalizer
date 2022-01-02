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
            $table->dateTime('visible_at')->nullable()->comment('Data kiedy ogłoszenie ma się pojawić w serwisie');
            $table->unsignedMediumInteger('ticket_price')->comment('Cena wyrażona w groszach'); 
            $table->unsignedSmallInteger('game_variant_id')->comment('Wariant gry, np. podstawowy albo zaawansowany (na pozycje)');
            $table->unsignedTinyInteger('minimum_skill_level_id')->nullable();
            $table->unsignedSmallInteger('gender_id')->nullable();
            $table->unsignedSmallInteger('age_category_id')->nullable()->comment('Typ kategorii wiekowej, np. dorośli, dzieci etc.');
            $table->unsignedTinyInteger('minimal_age')->nullable();
            $table->unsignedTinyInteger('maximum_age')->nullable();
            $table->char('code', 12)->comment('Kod za pomocą którego można dołączyć do wydarzenia prywatnego'); // Kodowane natywnie
            $table->string('description', 2000)->nullable(); // Kodowane natywnie
            $table->unsignedTinyInteger('participants_counter')->default(0);
            $table->unsignedTinyInteger('maximum_participants_number');
            $table->unsignedSmallInteger('announcement_type_id')->nullable()->comment('Typ ogłoszenia, np. dla osób, dla zespołów etc.');
            $table->unsignedSmallInteger('announcement_status_id')->comment('Status ogłoszenia, np. anulowane, zakończone etc.');
            $table->unsignedMediumInteger('creator_id')->nullable();
            $table->unsignedMediumInteger('editor_id')->nullable();
            $table->boolean('is_automatically_approved')->comment('Flaga z informacją czy rezerwacje będą automatycznie zatwierdzane, czy trzeba je ręcznie zaakceptować');
            $table->boolean('is_public')->comment('Flaga z informacją czy ogłoszenie jest publiczne, czy prywatne');
            $table->timestamps();
        });

        Schema::table('announcements', function (Blueprint $table) {
            $table->foreign('announcement_partner_id')->references('id')->on('partner_settings')->nullOnDelete();
            $table->foreign('facility_id')->references('id')->on('facilities')->nullOnDelete();
            $table->foreign('sport_id')->references('id')->on('default_types');
            $table->foreign('game_variant_id')->references('id')->on('default_types');
            $table->foreign('minimum_skill_level_id')->references('id')->on('minimum_skill_levels');
            $table->foreign('gender_id')->references('id')->on('default_types');
            $table->foreign('age_category_id')->references('id')->on('default_types');
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
