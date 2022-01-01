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
            $table->unsignedSmallInteger('account_action_type_id')->unique();
            $table->unsignedMediumInteger('period')->nullable()->comment('Czas wyrażony w sekundach');
        });

        Schema::table('account_action_types', function (Blueprint $table) {
            $table->foreign('account_action_type_id')->references('id')->on('default_types')->cascadeOnDelete();
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
