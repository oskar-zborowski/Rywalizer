<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgreementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('agreements', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->string('filename', 64); // Kodowane natywnie
            $table->string('description', 136); // Kodowane natywnie
            $table->string('signature', 40); // Kodowane natywnie
            $table->unsignedTinyInteger('version');
            $table->unsignedTinyInteger('object_type_id');
            $table->unsignedInteger('object_id')->nullable();
            $table->unsignedTinyInteger('agreement_type_id')->nullable();
            $table->unsignedMediumInteger('creator_id')->nullable();
            $table->boolean('is_required');
            $table->timestamp('effective_date')->nullable();
            $table->timestamps();
        });

        Schema::table('agreements', function (Blueprint $table) {
            $table->foreign('object_type_id')->references('id')->on('object_types');
            $table->foreign('agreement_type_id')->references('id')->on('agreement_types');
            $table->foreign('creator_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('agreements');
    }
}
