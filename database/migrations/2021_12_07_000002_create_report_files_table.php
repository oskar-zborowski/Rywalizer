<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('report_files', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->unsignedMediumInteger('report_id');
            $table->char('filename', 64)->unique(); // Kodowane natywnie
            $table->timestamp('created_at');
        });

        Schema::table('report_files', function (Blueprint $table) {
            $table->foreign('report_id')->references('id')->on('reports')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('report_files');
    }
}
