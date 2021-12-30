<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartnerPicturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('partner_pictures', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->char('filename', 64)->unique(); // Kodowane natywnie
            $table->unsignedSmallInteger('partner_id');
            $table->unsignedMediumInteger('creator_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('partner_pictures');
    }
}
