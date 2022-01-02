<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacilityPlacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('facility_places', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->unsignedSmallInteger('facility_id');
            $table->unsignedSmallInteger('facility_place_type_id')->comment('Typ miejsca, np. boisko, kort etc.');
            $table->string('name', 50);
            $table->unsignedSmallInteger('unit')->comment('Czas w minutach dla pojedynczej jednostki dokonania rezerwacji');
            $table->unsignedMediumInteger('price_per_unit')->comment('Cena wyrażona w groszach');
            $table->unsignedSmallInteger('minimum_unit_booking')->nullable()->comment('Minimalna liczba jednostek dla dokonania rezerwacji');
            $table->unsignedSmallInteger('maximum_unit_booking')->nullable()->comment('Maksymalna liczba jednostek dla dokonania rezerwacji');
            $table->unsignedMediumInteger('creator_id')->nullable();
            $table->unsignedMediumInteger('editor_id')->nullable();
            $table->boolean('is_automatically_approved');
            $table->boolean('is_visible');
            $table->timestamp('deleted_at')->nullable()->comment('Uzupełniane tylko w przypadku kiedy nie możemy usunąć miejsca w obiekcie');
            $table->timestamps();
        });

        Schema::table('facility_places', function (Blueprint $table) {
            $table->foreign('facility_id')->references('id')->on('facilities')->cascadeOnDelete();
            $table->foreign('facility_place_type_id')->references('id')->on('default_types');
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
        Schema::dropIfExists('facility_places');
    }
}
