<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountOperationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('account_operations', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->unsignedMediumInteger('user_id');
            $table->unsignedTinyInteger('account_operation_type_id');
            $table->string('token', 64)->unique(); // Kodowane natywnie
            $table->unsignedTinyInteger('email_sending_counter');
            $table->timestamps();
        });

        Schema::table('account_operations', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('account_operation_type_id')->references('id')->on('account_operation_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('account_operations');
    }
}
