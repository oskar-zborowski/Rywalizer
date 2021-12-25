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
            $table->integerIncrements('id');
            $table->string('ip', 20); // Kodowane natywnie
            $table->string('uuid', 64); // Kodowane natywnie
            $table->string('os_name', 28)->nullable(); // Kodowane natywnie
            $table->string('os_version', 28)->nullable(); // Kodowane natywnie
            $table->string('browser_name', 28)->nullable(); // Kodowane natywnie
            $table->string('browser_version', 28)->nullable(); // Kodowane natywnie
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
