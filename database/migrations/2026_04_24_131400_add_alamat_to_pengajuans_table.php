<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('pengajuans', 'alamat')) {
            Schema::table('pengajuans', function (Blueprint $table) {
                $table->string('alamat')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('pengajuans', 'alamat')) {
            Schema::table('pengajuans', function (Blueprint $table) {
                $table->dropColumn('alamat');
            });
        }
    }
};
