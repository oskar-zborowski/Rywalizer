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
            $table->morphs('contractable');
            $table->char('filename', 64); // Kodowane natywnie
            $table->char('description', 136); // Kodowane natywnie
            $table->char('signature', 40); // Kodowane natywnie
            $table->unsignedTinyInteger('version');
            $table->dateTime('effective_date');
            $table->unsignedSmallInteger('agreement_type_id')->nullable();
            $table->unsignedMediumInteger('creator_id')->nullable();
            $table->unsignedMediumInteger('editor_id')->nullable();
            $table->boolean('is_required');
            $table->boolean('is_visible');
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });

        Schema::table('agreements', function (Blueprint $table) {
            $table->foreign('agreement_type_id')->references('id')->on('default_types');
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
        Schema::dropIfExists('agreements');
    }
}
