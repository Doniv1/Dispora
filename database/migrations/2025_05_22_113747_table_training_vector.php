<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Tambahin ini

return new class extends Migration {
    public function up()
    {
        Schema::create('training_vectors', function (Blueprint $table) {
            $table->id(); // AUTO_INCREMENT
            $table->unsignedBigInteger('id_training')->nullable();
            $table->unsignedBigInteger('id_vector')->nullable();
            $table->foreign('id_training')
                    ->references('id_training')->on('trainings')
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
        Schema::dropIfExists('training_vectors');
    }
};
