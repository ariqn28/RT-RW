<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use App\Models\PengajuanStatusHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MobilePengajuanController extends Controller
{
    public function index(Request $request)
    {
        $pengajuans = Pengajuan::where('user_id', $request->user()->id)
            ->latest()
            ->with(['statusHistories.changedBy'])
            ->paginate(15);

        return response()->json([
            'data' => $pengajuans->items(),
            'pagination' => [
                'total' => $pengajuans->total(),
                'per_page' => $pengajuans->perPage(),
                'current_page' => $pengajuans->currentPage(),
                'last_page' => $pengajuans->lastPage(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_surat' => 'required|string|max:255',
            'nama' => 'required|string|max:255',
            'nik' => 'required|string|max:20',
            'alamat' => 'required|string|max:255',
            'alasan' => 'required|string|min:10',
            'file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
        ]);

        $validated['status'] = 'baru';
        $validated['user_id'] = $request->user()->id;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
            $filePath = $file->storeAs('pengajuan_files', $fileName, 'public');
            $validated['file_path'] = $filePath;
        }

        $pengajuan = Pengajuan::create($validated);

        $pengajuan->statusHistories()->create([
            'status' => 'baru',
            'changed_by' => $request->user()->id,
            'note' => 'Pengajuan dibuat oleh warga (mobile).',
        ]);

        return response()->json([
            'message' => 'Pengajuan berhasil dikirim!',
            'data' => $pengajuan,
        ], 201);
    }

    public function show(Request $request, Pengajuan $pengajuan)
    {
        if ($pengajuan->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Akses ditolak.'], 403);
        }

        $pengajuan->load(['statusHistories.changedBy']);

        return response()->json([
            'data' => $pengajuan,
        ]);
    }
}

