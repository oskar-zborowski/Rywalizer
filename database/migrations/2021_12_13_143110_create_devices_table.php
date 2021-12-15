<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('devices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('ip', 20)->nullable();
            $table->string('uuid', 64);
            $table->string('os_name', 20)->nullable();
            $table->string('os_version', 32)->nullable();
            $table->string('browser_name', 24)->nullable();
            $table->string('browser_version', 32)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('devices');
    }
}
