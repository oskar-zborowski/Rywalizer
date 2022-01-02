<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImageAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('image_assignments', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->morphs('imageable');
            $table->unsignedSmallInteger('image_type_id')->comment('Typ zdjęcia, np. avatar, zdjęcia obiektu etc.');
            $table->unsignedInteger('image_id');
            $table->unsignedTinyInteger('number')->comment('Numer porządkowy zdjęcia w agregowanej grupie');
            $table->unsignedMediumInteger('creator_id')->nullable();
            $table->unsignedMediumInteger('editor_id')->nullable();
            $table->timestamps();
        });

        Schema::table('image_assignments', function (Blueprint $table) {
            $table->foreign('image_type_id')->references('id')->on('default_types');
            $table->foreign('image_id')->references('id')->on('images')->cascadeOnDelete();
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
        Schema::dropIfExists('image_assignments');
    }
}
