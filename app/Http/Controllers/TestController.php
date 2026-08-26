<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    public function __invoke(Request $request)
    {
        // Proteksi: Jangan biarkan berjalan di Production
        if (!App::environment('local')) {
            abort(404);
        }

        // Test 1: Cek apakah request sampai
        if ($request->isMethod('post')) {
            try {
                // Cek Koneksi Database sebelum proses
                $db_status = DB::connection()->getPdo() ? 'Terkoneksi' : 'Gagal';
                
                return response()->json([
                    'status' => 'POST sampai ke controller',
                    'database' => $db_status,
                    'driver' => DB::connection()->getDriverName(),
                    'all_data' => $request->all(),
                    'has_file' => $request->hasFile('file'),
                    'file_info' => $request->hasFile('file') ? [
                        'name' => $request->file('file')->getClientOriginalName(),
                        'size' => $request->file('file')->getSize(),
                    ] : null,
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 'Gagal koneksi database',
                    'error' => $e->getMessage()
                ], 500);
            }
        }

        // Test 2: Tampilkan form test
        return response('
        <!DOCTYPE html>
        <html>
        <head><title>Test Form POST</title></head>
        <body>
            <h2>Test Submit Form (tanpa auth)</h2>
            <form action="/test-ajukan" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="' . csrf_token() . '">
                <p>Jenis Surat: <input type="text" name="jenis_surat" value="Surat Test" required></p>
                <p>Nama: <input type="text" name="nama" value="Test Nama" required></p>
                <p>NIK: <input type="text" name="nik" value="1234567890" required></p>
                <p>Alamat: <input type="text" name="alamat" value="Jl. Test" required></p>
                <p>Alasan: <textarea name="alasan" required>Alasan pengajuan surat untuk testing aplikasi RT/RW.</textarea></p>
                <p>File: <input type="file" name="file"></p>
                <button type="submit">Kirim Test</button>
            </form>
            <hr>
            <p><b>Hasil:</b></p>
            <ul>
                <li>Jika muncul JSON → POST berhasil, masalah ada di PengajuanController</li>
                <li>Jika "can\'t reach page" → Server crash saat POST, cek terminal server</li>
            </ul>
        </body>
        </html>
        ');
    }
}
