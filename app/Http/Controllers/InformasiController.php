<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InformasiController extends Controller
{
    public function index()
    {
        // Untuk sementara, mengarah ke view informasi.index
        return view('warga.informasi');
    }
}
