<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonalAccessTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('personal_access_tokens', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->morphs('tokenable'); // tokenable_type kodowany podczas przetwarzania (spróbować czy zadziała)
            $table->string('name', 40); // Kodowane podczas przetwarzania
            $table->string('token', 64)->unique(); // Kodowane przez dostawcę
            $table->string('refresh_token', 64)->unique()->nullable(); // Kodowane podczas przetwarzania
            $table->text('abilities')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamps();
        });

        Schema::table('personal_access_tokens', function (Blueprint $table) {
            $table->foreign('tokenable_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('personal_access_tokens');
    }
}
