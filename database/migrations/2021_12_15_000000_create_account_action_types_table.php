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
    public function up(): void {
        Schema::create('account_action_types', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('name', 36)->unique();
            $table->string('description', 52);
            $table->string('description_admin', 36);
            $table->smallInteger('period')->unsigned(); // Czas wyrażony w dniach
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void {
        Schema::dropIfExists('account_action_types');
    }
}
