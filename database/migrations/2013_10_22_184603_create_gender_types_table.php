<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGenderTypesTable extends Migration
{
    public function up() {
        
        Schema::create('gender_types', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('name', 10)->unique();
        });
    }

    public function down() {
        Schema::dropIfExists('gender_types');
    }
}