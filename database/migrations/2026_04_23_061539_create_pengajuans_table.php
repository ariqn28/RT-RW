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
        Schema::create('pengajuans', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_surat');
            $table->string('nama');
            $table->string('nik', 20);
            $table->string('alamat');
            $table->text('alasan');
            $table->enum('status', ['baru', 'disetujui_rt', 'diterima', 'ditolak'])->default('baru');
            $table->unsignedBigInteger('user_id')->nullable(); // opsional relasi ke users
            $table->timestamps();

            // Relasi ke tabel users (opsional)
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuans');
    }
};
