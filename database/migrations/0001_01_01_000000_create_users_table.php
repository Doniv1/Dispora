<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Tambahin ini

return new class extends Migration {
  public function up()
  {
    Schema::create('users', function (Blueprint $table) {
      $table->id('id_user'); // AUTO_INCREMENT
      $table->integer('role')->default(2)->comment('1 = admin,2 = user');
      $table->string('email', 199)->unique()->nullable();
      $table->string('phone', 199)->unique()->nullable();
      $table->string('name', 200)->nullable();
      $table->date('born_date')->nullable();
      $table->enum('gender', ['Laki-laki', 'Perempuan'])->nullable();
      $table->enum('education_status', ['SMA', 'SMK', 'Mahasiswa'])->nullable();
      $table->string('image', 200)->nullable();
      $table->string('password', 200)->nullable();
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
    Schema::dropIfExists('users');
  }
};
