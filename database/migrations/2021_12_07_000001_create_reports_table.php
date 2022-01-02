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
            $table->morphs('reportable');
            $table->unsignedMediumInteger('user_id')->nullable();
            $table->unsignedMediumInteger('supervisor_id')->nullable();
            $table->string('email', 340)->nullable(); // Kodowane natywnie
            $table->string('message', 6000); // Kodowane natywnie
            $table->unsignedSmallInteger('report_status_id');
            $table->dateTime('deadline_at')->nullable();
            $table->timestamp('fixed_at')->nullable();
            $table->timestamps();
        });

        Schema::table('reports', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('supervisor_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('report_status_id')->references('id')->on('default_types');
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
