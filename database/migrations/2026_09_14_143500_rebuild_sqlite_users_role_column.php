<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            return;
        }

        // SQLite tidak dapat mengubah CHECK constraint dari kolom enum.
        // Tabel dibuat ulang dengan kolom role berupa string agar admin/rt/rw/warga valid.
        DB::statement('PRAGMA foreign_keys = OFF');

        try {
            Schema::create('users_rebuilt', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('role')->default('warga');
                $table->string('nik', 20)->nullable();
                $table->string('alamat', 500)->nullable();
                $table->string('password');
                $table->rememberToken();
                $table->timestamps();
            });

            DB::table('users_rebuilt')->insertUsing(
                ['id', 'name', 'email', 'email_verified_at', 'role', 'nik', 'alamat', 'password', 'remember_token', 'created_at', 'updated_at'],
                DB::table('users')->select(['id', 'name', 'email', 'email_verified_at', 'role', 'nik', 'alamat', 'password', 'remember_token', 'created_at', 'updated_at'])
            );

            Schema::drop('users');
            Schema::rename('users_rebuilt', 'users');
        } finally {
            DB::statement('PRAGMA foreign_keys = ON');
        }
    }

    public function down(): void
    {
        // Tidak ada rollback otomatis untuk menghindari pembatasan role kembali ke data yang sudah valid.
    }
};
