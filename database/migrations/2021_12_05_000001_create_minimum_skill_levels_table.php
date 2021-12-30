<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMinimumSkillLevelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('minimum_skill_levels', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->unsignedSmallInteger('sport_id');
            $table->string('name', 50);
            $table->text('description')->nullable();
            $table->unsignedMediumInteger('creator_id')->nullable();
            $table->unsignedMediumInteger('editor_id')->nullable();
            $table->unsignedMediumInteger('supervisor_id')->nullable();
            $table->boolean('is_visible')->default(false);
            $table->timestamps();
        });

        Schema::table('minimum_skill_levels', function (Blueprint $table) {
            $table->foreign('sport_id')->references('id')->on('default_types');
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
        Schema::dropIfExists('minimum_skill_levels');
    }
}
