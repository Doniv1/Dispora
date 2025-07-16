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
    Schema::table('regis_trainings', function (Blueprint $table) {
        $table->enum('is_notified', ['Y', 'N'])->default('N')->after('approved');
    });
}

public function down()
{
    Schema::table('regis_trainings', function (Blueprint $table) {
        $table->dropColumn('is_notified');
    });
}
};
