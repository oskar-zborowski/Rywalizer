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
            $table->char('number', 40)->unique(); // Kodowane natywnie
            $table->unsignedMediumInteger('total_amount');
            $table->mediumInteger('system_amount');
            $table->mediumInteger('partner_amount');
            $table->char('order_id', 64)->nullable()->unique(); // Kodowane natywnie
            $table->char('session_id', 64)->unique(); // Kodowane natywnie
            $table->unsignedMediumInteger('discount_code_id')->nullable();
            $table->unsignedSmallInteger('transaction_status_id');
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->foreign('discount_code_id')->references('id')->on('discount_codes');
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
