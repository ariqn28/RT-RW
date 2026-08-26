<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IuranController extends Controller
{
    public function index()
    {
        // Untuk sementara, kita tampilkan view kosong agar tidak error
        // Pastikan Anda nanti membuat file di resources/views/warga/iuran.blade.php
        return view('warga.iuran');
    }
}
