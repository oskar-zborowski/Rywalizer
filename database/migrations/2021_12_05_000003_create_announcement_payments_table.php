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
            $table->unsignedMediumInteger('creator_id')->nullable();
            $table->unsignedMediumInteger('editor_id')->nullable();
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });

        Schema::table('announcement_payments', function (Blueprint $table) {
            $table->foreign('announcement_id')->references('id')->on('announcements')->cascadeOnDelete();
            $table->foreign('payment_type_id')->references('id')->on('default_types');
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
        Schema::dropIfExists('announcement_payments');
    }
}
