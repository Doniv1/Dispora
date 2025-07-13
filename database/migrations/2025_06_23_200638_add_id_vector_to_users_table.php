<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::table('users', function (Blueprint $table) {
      $table->unsignedBigInteger('id_vector')->nullable()->after('id_user'); // tambahkan kolom
      $table->foreign('id_vector')->references('id_vector')->on('vectors')->onDelete('set null')->onUpdate('cascade');

      $table->unsignedBigInteger('id_riwayat_pelatihan')->nullable()->after('id_vector'); // tambahkan kolom
      $table->foreign('id_riwayat_pelatihan')->references('id_training')->on('trainings')->onDelete('set null')->onUpdate('cascade');

      $table->unsignedBigInteger('id_category')->nullable()->after('id_vector');
      $table->foreign('id_category')->references('id_category')->on('categories')->onDelete('set null')->onUpdate('cascade');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('users', function (Blueprint $table) {
      //
    });
  }
};
