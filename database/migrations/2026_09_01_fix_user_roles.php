<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // User yang belum memiliki role akan menjadi warga.
        User::whereNull('role')->update([
            'role' => 'warga',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tidak ada rollback karena ini adalah perbaikan data role.
    }
};