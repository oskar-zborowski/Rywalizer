<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoleTypesTable extends Migration
{
    public function up() {
        
        Schema::create('role_types', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('name', 20)->unique();
        });
    }

    public function down() {
        Schema::dropIfExists('role_types');
    }
}