<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDefaultTypeAccessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('default_type_accesses', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->unsignedSmallInteger('default_type_id');
            $table->unsignedTinyInteger('role_id');
        });

        Schema::table('default_type_accesses', function (Blueprint $table) {
            $table->foreign('default_type_id')->references('id')->on('default_types')->cascadeOnDelete();
            $table->foreign('role_id')->references('id')->on('roles')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('default_type_accesses');
    }
}
