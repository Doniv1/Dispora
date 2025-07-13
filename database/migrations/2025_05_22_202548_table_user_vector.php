<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('user_vectors', function (Blueprint $table) {
            $table->id(); // AUTO_INCREMENT
            $table->unsignedBigInteger('id_user')->nullable();
            $table->unsignedBigInteger('id_vector')->nullable();
            $table->foreign('id_user')
                    ->references('id_user')->on('users')
                    ->onUpdate('CASCADE')
                    ->onDelete('CASCADE');
            $table->foreign('id_vector')
                    ->references('id_vector')->on('vectors')
                    ->onUpdate('CASCADE')
                    ->onDelete('CASCADE');
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_vectors');
    }
};
