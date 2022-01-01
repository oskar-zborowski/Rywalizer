<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePicturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('pictures', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->morphs('imageable');
            $table->char('filename', 64)->unique(); // Kodowane natywnie
            $table->unsignedMediumInteger('creator_id')->nullable();
            $table->unsignedMediumInteger('supervisor_id')->nullable();
            $table->timestamp('visible_at')->nullable();
            $table->timestamps();
        });

        Schema::table('pictures', function (Blueprint $table) {
            $table->foreign('creator_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('supervisor_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('pictures');
    }
}
