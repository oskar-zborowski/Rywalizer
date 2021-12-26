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
            $table->string('name', 40)->unique();
            $table->string('description', 50);
            $table->float('commission');
            $table->unsignedMediumInteger('creator_id')->nullable();
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->timestamps();
        });

        Schema::table('commissions', function (Blueprint $table) {
            $table->foreign('creator_id')->references('id')->on('users')->nullOnDelete();
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
