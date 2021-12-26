<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('transactions', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->string('number', 28); // Kodowane natywnie
            $table->unsignedMediumInteger('transaction_amount');
            $table->mediumInteger('system_amount');
            $table->mediumInteger('partner_amount');
            $table->string('transaction_order_id', 64)->nullable(); // Kodowane natywnie
            $table->string('transaction_session_id', 64); // Kodowane natywnie
            $table->unsignedSmallInteger('discount_code_id')->nullable();
            $table->unsignedTinyInteger('transaction_status_id')->default(1);
            $table->timestamp('transaction_confirmed_at')->nullable();
            $table->timestamps();
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->foreign('discount_code_id')->references('id')->on('discount_codes');
            $table->foreign('transaction_status_id')->references('id')->on('transaction_statuses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('transactions');
    }
}
