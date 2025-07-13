<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Tambahin ini

return new class extends Migration {
    public function up()
    {
        Schema::create('trainings', function (Blueprint $table) {
            $table->id('id_training'); // AUTO_INCREMENT
            $table->unsignedBigInteger('id_category')->nullable();
            $table->string('title', 200)->nullable();
            $table->string('image', 200)->nullable();
            $table->text('sort_description')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['Y', 'N'])->default('Y');
            $table->text('reason')->nullable();
            $table->dateTime('blocked_date')->nullable();
            $table->unsignedBigInteger('blocked_by')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->dateTime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->enum('deleted', ['Y', 'N'])->default('N');
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->foreign('created_by')
                    ->references('id_user')->on('users')
                    ->onUpdate('CASCADE')
                    ->onDelete('SET NULL');
            $table->foreign('id_category')
                    ->references('id_category')->on('categories')
                    ->onUpdate('CASCADE')
                    ->onDelete('CASCADE');
            $table->foreign('deleted_by')
                    ->references('id_user')->on('users')
                    ->onUpdate('CASCADE')
                    ->onDelete('SET NULL');
            $table->foreign('blocked_by')
                    ->references('id_user')->on('users')
                    ->onUpdate('CASCADE')
                    ->onDelete('SET NULL');
        });
    }

    public function down()
    {
        Schema::dropIfExists('trainings');
    }
};
