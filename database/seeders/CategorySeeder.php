<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;


class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Daftar kategori (sesuaikan kebutuhan menu pengajuan di web & mobile)
        Category::insert([
            ['name' => 'Pengajuan KTP'],
            ['name' => 'Pengajuan Surat Pengantar'],
            ['name' => 'Pengajuan Surat Domisili'],
            ['name' => 'Pengajuan Kartu Keluarga (KK)'],
            ['name' => 'Surat Keterangan Tidak Mampu (SKTM)'],
            ['name' => 'Surat Keterangan Usaha (SKU)'],
            ['name' => 'Surat Keterangan Pindah / Datang'],
            ['name' => 'Surat Keterangan Kematian'],
        ]);

    }
}
