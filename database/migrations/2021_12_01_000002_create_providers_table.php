<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('providers', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('name', 10)->unique();
            $table->string('icon', 20)->unique();
            $table->unsignedMediumInteger('creator_id')->nullable();
            $table->boolean('is_enabled');
            $table->timestamps();
        });

        Schema::table('providers', function (Blueprint $table) {
            $table->foreign('creator_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('providers');
    }
}
