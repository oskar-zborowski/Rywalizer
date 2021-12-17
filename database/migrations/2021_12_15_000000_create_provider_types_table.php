<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProviderTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void {
        Schema::create('provider_types', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('name', 12)->unique();
            $table->string('icon', 24)->unique();
            $table->boolean('is_enabled')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void {
        Schema::dropIfExists('provider_types');
    }
}
