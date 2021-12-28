<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('commissions', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->unsignedSmallInteger('commission_id')->unique();
            $table->string('signature', 50);
            $table->unsignedTinyInteger('version');
            $table->unsignedFloat('value');
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
        });

        Schema::table('commissions', function (Blueprint $table) {
            $table->foreign('commission_id')->references('id')->on('default_types')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('commissions');
    }
}
