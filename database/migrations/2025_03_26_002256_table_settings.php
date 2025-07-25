<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        
        Schema::create('settings', function (Blueprint $table) {
            $table->id('id_setting');
            $table->string('logo', 199)->nullable();
            $table->string('logo_white', 199)->nullable();
            $table->string('icon', 199)->nullable();
            $table->string('icon_white', 199)->nullable();
            $table->string('meta_title', 255)->nullable(); // Title untuk halaman web
            $table->text('about')->nullable(); // Alamat lengkap
            $table->timestamps(); // created_at & updated_at otomatis
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
