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
            $table->mediumIncrements('id');
            $table->morphs('operationable');
            $table->unsignedSmallInteger('account_operation_type_id')->comment('Typ operacji, np. reset hasła, weryfikacja maila etc.');
            $table->char('token', 64)->unique(); // Kodowane natywnie
            $table->unsignedTinyInteger('email_sending_counter')->default(1);
            $table->unsignedMediumInteger('creator_id')->nullable();
            $table->unsignedMediumInteger('editor_id')->nullable();
            $table->timestamps();
        });

        Schema::table('account_operations', function (Blueprint $table) {
            $table->foreign('account_operation_type_id')->references('id')->on('default_types');
            $table->foreign('creator_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('editor_id')->references('id')->on('users')->nullOnDelete();
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
