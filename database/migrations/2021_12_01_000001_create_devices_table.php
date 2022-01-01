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
            $table->char('uuid', 64)->comment('Token zapisywany w ciasteczkach'); // Kodowane natywnie
            $table->char('ip', 20)->nullable(); // Kodowane natywnie
            $table->char('os_name', 40)->nullable(); // Kodowane natywnie
            $table->char('os_version', 40)->nullable(); // Kodowane natywnie
            $table->char('browser_name', 40)->nullable(); // Kodowane natywnie
            $table->char('browser_version', 40)->nullable(); // Kodowane natywnie
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
