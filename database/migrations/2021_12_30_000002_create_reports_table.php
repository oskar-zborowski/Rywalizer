<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('reports', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->unsignedMediumInteger('user_id');
            $table->unsignedMediumInteger('supervisor_id')->nullable();
            $table->unsignedTinyInteger('report_status_id')->default(0);
            $table->unsignedTinyInteger('reported_object_type_id');
            $table->unsignedMediumInteger('reported_object_id');
            $table->string('message', 2668); // Kodowane natywnie
            $table->string('fixed_at')->nullable();
            $table->timestamps();
        });

        Schema::table('reports', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('supervisor_id')->references('id')->on('users');
            $table->foreign('report_status_id')->references('id')->on('report_statuses');
            $table->foreign('reported_object_type_id')->references('id')->on('reported_object_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('reports');
    }
}
