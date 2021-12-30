<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('role_permissions', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('role_id');
            $table->unsignedSmallInteger('permission_id');
            $table->unsignedMediumInteger('creator_id')->nullable();
            $table->timestamps();
        });

        Schema::table('role_permissions', function (Blueprint $table) {
            $table->foreign('role_id')->references('id')->on('default_types')->cascadeOnDelete();
            $table->foreign('permission_id')->references('id')->on('default_types')->cascadeOnDelete();
            $table->foreign('creator_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('role_permissions');
    }
}
