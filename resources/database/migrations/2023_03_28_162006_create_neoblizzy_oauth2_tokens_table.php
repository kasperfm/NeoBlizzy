<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('neoblizzy_oauth2_tokens', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->index()->nullable();
            $table->string('game', 32)->index();
            $table->string('region', 2);
            $table->integer('realm');
            $table->integer('profile_id')->index();
            $table->string('token');
            $table->timestamp('expires_at')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('neoblizzy_oauth2_tokens');
    }
};
