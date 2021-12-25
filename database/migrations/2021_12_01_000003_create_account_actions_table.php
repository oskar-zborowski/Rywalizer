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
            $table->unsignedMediumInteger('user_id');
            $table->unsignedMediumInteger('founder_id')->nullable();
            $table->unsignedTinyInteger('account_action_type_id');
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });

        Schema::table('account_actions', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('founder_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('account_action_type_id')->references('id')->on('account_action_types');
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
