<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnnouncementPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('announcement_payments', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->unsignedInteger('announcement_id');
            $table->unsignedSmallInteger('payment_type_id');
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });

        Schema::table('announcement_payments', function (Blueprint $table) {
            $table->foreign('announcement_id')->references('id')->on('announcements')->cascadeOnDelete();
            $table->foreign('payment_type_id')->references('id')->on('default_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('announcement_payments');
    }
}
