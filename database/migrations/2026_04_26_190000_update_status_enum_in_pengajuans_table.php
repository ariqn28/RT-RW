<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Skip for SQLite - enum modification not supported
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        DB::statement("ALTER TABLE pengajuans MODIFY COLUMN status ENUM('baru', 'disetujui_rt', 'diterima', 'ditolak') NOT NULL DEFAULT 'baru'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        DB::statement("ALTER TABLE pengajuans MODIFY COLUMN status ENUM('baru', 'diterima', 'ditolak') NOT NULL DEFAULT 'baru'");
    }
};
