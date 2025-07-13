<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Tambahin ini

return new class extends Migration {
    public function up()
    {
        Schema::create('regis_trainings', function (Blueprint $table) {
            $table->id('id_regis_training'); // AUTO_INCREMENT
            $table->unsignedBigInteger('id_training')->nullable();
            $table->unsignedBigInteger('id_user')->nullable();
            $table->enum('approved', ['Y', 'N','P'])->default('P');
            $table->dateTime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->enum('deleted', ['Y', 'N'])->default('N');
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->foreign('id_training')
                    ->references('id_training')->on('trainings')
                    ->onUpdate('CASCADE')
                    ->onDelete('CASCADE');
            $table->foreign('id_user')
                    ->references('id_user')->on('users')
                    ->onUpdate('CASCADE')
                    ->onDelete('CASCADE');
            $table->foreign('deleted_by')
                    ->references('id_user')->on('users')
                    ->onUpdate('CASCADE')
                    ->onDelete('SET NULL');
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};
