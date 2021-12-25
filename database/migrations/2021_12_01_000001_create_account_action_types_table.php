<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountActionTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('account_action_types', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('name', 30)->unique();
            $table->string('description_simple', 30);
            $table->string('description_perfect', 40);
            $table->unsignedSmallInteger('period'); // Czas wyrażony w dniach
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('account_action_types');
    }
}
