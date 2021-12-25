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
            $table->unsignedMediumInteger('user_id')->nullable();
            $table->unsignedMediumInteger('supervisor_id')->nullable();
            $table->unsignedTinyInteger('report_status_id')->default(0);
            $table->unsignedTinyInteger('reported_object_type_id');
            $table->unsignedInteger('reported_object_id')->nullable();
            $table->string('message', 2668); // Kodowane natywnie
            $table->timestamp('fixed_at')->nullable();
            $table->timestamp('deadline_at')->nullable();
            $table->timestamps();
        });

        Schema::table('reports', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('supervisor_id')->references('id')->on('users')->nullOnDelete();
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
