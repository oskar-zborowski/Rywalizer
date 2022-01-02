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
            $table->morphs('transactionable');
            $table->char('number', 40)->unique()->comment('Numer transakcji, który może być jednocześnie numerem faktury'); // Kodowane natywnie
            $table->unsignedMediumInteger('regular_price')->comment('Kwota wyrażona w groszach');
            $table->unsignedMediumInteger('total_amount')->comment('Kwota wyrażona w groszach');
            $table->mediumInteger('system_amount')->comment('Kwota wyrażona w groszach');
            $table->mediumInteger('partner_amount')->comment('Kwota wyrażona w groszach');
            $table->char('order_id', 64)->nullable()->unique(); // Kodowane natywnie
            $table->char('session_id', 64)->unique(); // Kodowane natywnie
            $table->unsignedMediumInteger('discount_id')->nullable();
            $table->unsignedSmallInteger('transaction_status_id');
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->foreign('discount_id')->references('id')->on('discounts');
            $table->foreign('transaction_status_id')->references('id')->on('default_types');
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
