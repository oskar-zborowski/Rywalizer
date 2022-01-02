<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('account_actions', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->morphs('actionable');
            $table->unsignedTinyInteger('account_action_type_id')->comment('Typ akcji, np. usunięcie konta, zbanowanie konta etc.');
            $table->dateTime('expires_at')->nullable();
            $table->unsignedMediumInteger('creator_id')->nullable();
            $table->unsignedMediumInteger('editor_id')->nullable();
            $table->timestamps();
        });

        Schema::table('account_actions', function (Blueprint $table) {
            $table->foreign('account_action_type_id')->references('id')->on('account_action_types');
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
        Schema::dropIfExists('account_actions');
    }
}
