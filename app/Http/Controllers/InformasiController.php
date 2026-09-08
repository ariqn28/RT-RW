<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\ContactSetting;

class InformasiController extends Controller
{
    public function index()
    {
        return view('warga.informasi', [
            'announcements' => Announcement::where('is_published', true)->latest()->get(),
            'contact' => ContactSetting::firstOrCreate(['id' => 1]),
        ]);
    }
}
