<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('areas', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->string('name', 50);
            $table->polygon('boundary')->nullable();
            $table->unsignedSmallInteger('area_type_id')->comment('Typ obszaru, np. województwo, powiat etc.');
            $table->unsignedMediumInteger('parent_id')->nullable()->comment('ID obszaru o poziom wyżej, do którego należy bieżący obszar');
            $table->unsignedMediumInteger('creator_id')->nullable();
            $table->unsignedMediumInteger('editor_id')->nullable();
            $table->unsignedMediumInteger('supervisor_id')->nullable();
            $table->timestamp('visible_at')->nullable();
            $table->timestamps();
        });

        Schema::table('areas', function (Blueprint $table) {
            $table->foreign('area_type_id')->references('id')->on('default_types');
            $table->foreign('parent_id')->references('id')->on('areas');
            $table->foreign('creator_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('editor_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('supervisor_id')->references('id')->on('users')->nullOnDelete();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreign('gender_id')->references('id')->on('default_types');
            $table->foreign('role_id')->references('id')->on('default_types');
            $table->foreign('city_id')->references('id')->on('areas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('areas');
    }
}
