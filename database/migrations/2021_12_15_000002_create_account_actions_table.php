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
    public function up(): void {
        Schema::create('account_actions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->mediumInteger('user_id')->unsigned();
            $table->mediumInteger('founder_id')->unsigned();
            $table->tinyInteger('account_action_type_id')->unsigned();
            $table->timestamp('expires_at');
            $table->timestamps();
        });

        Schema::table('account_actions', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('founder_id')->references('id')->on('users');
            $table->foreign('account_action_type_id')->references('id')->on('account_action_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void {
        Schema::dropIfExists('account_actions');
    }
}
