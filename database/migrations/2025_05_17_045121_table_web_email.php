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
        Schema::create('web_email', function (Blueprint $table) {
            $table->id('id_web_email');
            $table->unsignedBigInteger('id_setting')->nullable();
            $table->string('email', 199)->nullable();
            $table->foreign('id_setting')
                ->references('id_setting')->on('settings')
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
