<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        DB::statement("ALTER TABLE pengajuan_status_histories MODIFY COLUMN status ENUM('baru', 'disetujui_rt', 'diterima', 'ditolak') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        DB::statement("ALTER TABLE pengajuan_status_histories MODIFY COLUMN status ENUM('baru', 'diterima', 'ditolak') NOT NULL");
    }
};
