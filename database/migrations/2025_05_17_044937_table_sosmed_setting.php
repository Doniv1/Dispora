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
         Schema::create('sosmed_setting', function (Blueprint $table) {
            $table->id('id_sosmed_setting');
            $table->unsignedBigInteger('id_setting')->nullable();
            $table->unsignedBigInteger('id_sosmed')->nullable();
            $table->string('name', 199)->nullable();
            $table->string('url', 199)->nullable();
            $table->foreign('id_setting')
                    ->references('id_setting')->on('settings')
                    ->onUpdate('CASCADE')
                    ->onDelete('CASCADE');
            $table->foreign('id_sosmed')
                    ->references('id_sosmed')->on('sosmeds')
                    ->onUpdate('CASCADE')
                    ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
