<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Tambahin ini

return new class extends Migration {
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id('id_contact'); // AUTO_INCREMENT
            $table->string('name', 199)->nullable();
            $table->string('email', 200)->nullable();
            $table->text('message')->nullable();
            $table->enum('status', ['Y', 'N'])->default('N');
            $table->text('reason')->nullable();
            $table->dateTime('blocked_date')->nullable();
            $table->unsignedBigInteger('blocked_by')->nullable();
            $table->dateTime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->foreign('blocked_by')
                    ->references('id_user')->on('users')
                    ->onUpdate('CASCADE')
                    ->onDelete('SET NULL');
        });
    }

    public function down()
    {
        Schema::dropIfExists('contacts');
    }
};
