<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pengajuan;

class PengajuanSeeder extends Seeder
{
    /**
     * Jalankan seeder.
     */
    public function run(): void
    {
        // Generate 20 data dummy menggunakan factory
        Pengajuan::factory()->count(20)->create();

        // Tambahkan contoh data manual
        Pengajuan::create([
            'jenis_surat' => 'Surat Keterangan',
            'nama'        => 'Budi Santoso',
            'nim'         => '12345678',
            'alasan'      => 'Keperluan administrasi RT/RW',
            'status'      => 'baru',
        ]);
    }
}
