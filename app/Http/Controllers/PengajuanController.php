<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use App\Models\PengajuanStatusHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PengajuanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

  public function index()
{
    $user = auth()->user();

    // 1. Logika untuk WARGA (Dashboard Warga)
    if ($user->role === 'warga') {
        $counts = [
            'TUNGGU' => Pengajuan::where('user_id', $user->id)->where('status', 'baru')->count(),
            'SETUJU' => Pengajuan::where('user_id', $user->id)->where('status', 'diterima')->count(),
            'TOLAK'  => Pengajuan::where('user_id', $user->id)->where('status', 'ditolak')->count(),
        ];
        return view('warga.dashboard_warga', compact('counts'));
    }

    // 2. Logika untuk ADMIN
    if ($user->role === 'admin') {
        $counts = [
            'pending' => Pengajuan::where('status', 'baru')->count(),
            'approved' => Pengajuan::where('status', 'diterima')->count(),
            'rejected' => Pengajuan::where('status', 'ditolak')->count(),
            'total' => Pengajuan::count(),
        ];
        $roleCounts = User::select('role', DB::raw('count(*) as total'))->groupBy('role')->pluck('total', 'role')->toArray();
        return view('admin.dashboard', compact('counts', 'roleCounts'));
    }

    // 3. Logika untuk RT
    if ($user->role === 'rt') {
        $pengajuan = Pengajuan::latest()->paginate(10);
        $pending = Pengajuan::where('status', 'baru')->with('user')->latest()->take(5)->get();
        $counts = [
            'pending' => Pengajuan::where('status', 'baru')->count(),
            'approved' => Pengajuan::where('status', 'disetujui_rt')->count(),
            'rejected' => Pengajuan::where('status', 'ditolak')->count(),
            'total' => Pengajuan::count(),
        ];
        $jenisRekap = Pengajuan::select('jenis_surat', DB::raw('count(*) as total'))->groupBy('jenis_surat')->orderByDesc('total')->limit(5)->get();
        return view('admin.rt.index', compact('pengajuan', 'pending', 'counts', 'jenisRekap'));
    }

    // 4. Logika untuk RW
    if ($user->role === 'rw') {
        $pengajuan = Pengajuan::whereIn('status', ['disetujui_rt', 'diterima'])->latest()->paginate(10);
        $rtPending = Pengajuan::where('status', 'disetujui_rt')->with('user')->latest()->take(5)->get();
        $counts = [
            'rt_pending' => Pengajuan::where('status', 'disetujui_rt')->count(),
            'approved' => Pengajuan::where('status', 'diterima')->count(),
            'rejected' => Pengajuan::where('status', 'ditolak')->count(),
            'total' => Pengajuan::count(),
        ];
        return view('admin.rw.index', compact('pengajuan', 'rtPending', 'counts'));
    }

    return redirect()->route('login');
}

// TAMBAHKAN FUNGSI INI UNTUK UPDATE REALTIME
public function getStats()
{
    $user = auth()->user();
    if (!$user || $user->role !== 'warga') return response()->json(['error' => 'Unauthorized'], 403);

    return response()->json([
        'TUNGGU' => Pengajuan::where('user_id', $user->id)->where('status', 'baru')->count(),
        'SETUJU' => Pengajuan::where('user_id', $user->id)->where('status', 'diterima')->count(),
        'TOLAK'  => Pengajuan::where('user_id', $user->id)->where('status', 'ditolak')->count(),
    ]);
}

    public function create()
    {
        return view('pengajuan.create');
    }

    public function store(Request $request)
    {
        Log::info('=== STORE PENGAJUAN START ===', [
            'user_id' => auth()->id(),
            'has_file' => $request->hasFile('file'),
            'all_data' => $request->except(['_token']),
        ]);

        try {
           $validated = $request->validate([
    'jenis_surat' => 'required|string|max:255',
    'nama'        => 'required|string|max:255',
    'nik'         => 'required|string|max:20',
    'alamat'      => 'required|string|max:255',
    'alasan'      => 'required|string|min:10',
    // Ubah max:1024 menjadi max:2048
    'file'        => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
]);
            Log::info('Validation passed');

            $validated['status'] = 'baru';
            $validated['user_id'] = auth()->id();

            // Handle file upload
            if ($request->hasFile('file')) {
                Log::info('Processing file upload');
                $file = $request->file('file');

                Log::info('File info', [
                    'name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                    'mime' => $file->getMimeType(),
                    'valid' => $file->isValid(),
                    'error' => $file->getError(),
                ]);

                if ($file->getSize() > 2048 * 1024) {
                    Log::warning('File too large: ' . $file->getSize());
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Ukuran file melebihi batas 2MB. Silakan kompres file atau upload file yang lebih kecil.');
                }

                $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
                $filePath = $file->storeAs('pengajuan_files', $fileName, 'public');
                $validated['file_path'] = $filePath;
                Log::info('File stored: ' . $filePath);
            }

            Log::info('Creating pengajuan record');
            $pengajuan = Pengajuan::create($validated);
            Log::info('Pengajuan created: ' . $pengajuan->id);

            Log::info('Creating status history');
            $pengajuan->statusHistories()->create([
                'status' => 'baru',
                'changed_by' => auth()->id(),
                'note' => 'Pengajuan dibuat oleh warga.',
            ]);
            Log::info('Status history created');

            Log::info('=== STORE PENGAJUAN SUCCESS ===');

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pengajuan berhasil dikirim!',
                    'redirect' => route('status.show', $pengajuan->id),
                    'pengajuan_id' => $pengajuan->id,
                ]);
            }

            // ... kode sebelumnya ...

Log::info('=== STORE PENGAJUAN SUCCESS ===');

// Ubah bagian ini agar diarahkan ke 'warga.landing'
return redirect()->route('warga.dashboard')
                 ->with('success', 'Pengajuan berhasil dikirim!');

// ... kode catch ...

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Validation failed', ['errors' => $e->errors()]);
            throw $e;
        } catch (\Exception $e) {
            Log::error('=== STORE PENGAJUAN FAILED ===', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                ], 500);
            }

            return redirect()->back()
                             ->withInput()
                             ->with('error', 'Terjadi kesalahan saat menyimpan pengajuan: ' . $e->getMessage());
        }
    }

    public function show(Pengajuan $pengajuan)
    {
        $user = auth()->user();

        if ($user->role === 'warga' && $pengajuan->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke pengajuan ini.');
        }

        $pengajuan->load('statusHistories.changedBy');
        $isAdmin = $user->role === 'admin';

        return view('pengajuan.show', compact('pengajuan', 'isAdmin'));
    }

    public function history()
    {
        $user = auth()->user();

        if ($user->role === 'warga') {
            $histories = PengajuanStatusHistory::whereHas('pengajuan', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->latest()->paginate(15);
        } else {
            $histories = PengajuanStatusHistory::latest()->paginate(15);
        }

        $histories->load(['pengajuan.user', 'changedBy']);

        return view('pengajuan.history', compact('histories'));
    }

    public function edit(Pengajuan $pengajuan)
    {
        return view('pengajuan.edit', compact('pengajuan'));
    }

    public function update(Request $request, Pengajuan $pengajuan)
    {
        $validated = $request->validate([
            'status' => 'required|in:baru,disetujui_rt,diterima,ditolak',
        ]);

        if ($pengajuan->status !== $validated['status']) {
            $role = auth()->user()->role;
            $statusLabel = [
                'disetujui_rt' => 'disetujui oleh RT.',
                'diterima'     => 'disetujui oleh RW (Selesai).',
                'ditolak'      => 'ditolak oleh ' . strtoupper($role) . '.',
                'baru'         => 'dikembalikan ke status baru.',
            ];

            $note = "Pengajuan " . ($statusLabel[$validated['status']] ?? 'diperbarui.');

            $pengajuan->update($validated);
            $pengajuan->statusHistories()->create([
                'status' => $validated['status'],
                'changed_by' => auth()->id(),
                'note' => $note,
            ]);
        }

        return redirect()->route('status.show', $pengajuan->id)
                         ->with('success', 'Status berhasil diperbarui!');
    }

    public function approve(Pengajuan $pengajuan)
    {
        $role = auth()->user()->role;

        // RT: hanya boleh approve saat status masih 'baru'
        if ($role === 'rt') {
            if ($pengajuan->status !== 'baru') {
                return redirect()->route('status.show', $pengajuan->id)
                    ->with('error', 'Pengajuan tidak dapat disetujui RT. Status saat ini: ' . $pengajuan->status);
            }

            $pengajuan->update(['status' => 'disetujui_rt']);
            $pengajuan->statusHistories()->create([
                'status' => 'disetujui_rt',
                'changed_by' => auth()->id(),
                'note' => 'Pengajuan disetujui oleh RT (' . auth()->user()->name . '). Menunggu verifikasi RW.',
            ]);

            return redirect()->route('status.show', $pengajuan->id)
                ->with('success', 'Pengajuan berhasil disetujui RT! Menunggu verifikasi RW.');
        }

        // RW: hanya boleh approve saat status sudah 'disetujui_rt'
        if ($role === 'rw') {
            if ($pengajuan->status !== 'disetujui_rt') {
                return redirect()->route('status.show', $pengajuan->id)
                    ->with('error', 'Pengajuan harus disetujui RT terlebih dahulu. Status saat ini: ' . $pengajuan->status);
            }

            $pengajuan->update(['status' => 'diterima']);
            $pengajuan->statusHistories()->create([
                'status' => 'diterima',
                'changed_by' => auth()->id(),
                'note' => 'Pengajuan disetujui oleh RW (' . auth()->user()->name . '). Surat selesai.',
            ]);

            return redirect()->route('status.show', $pengajuan->id)
                ->with('success', 'Pengajuan berhasil disetujui RW! Surat selesai.');
        }

        abort(403, 'Aksi tidak diizinkan.');
    }

    public function reject(Pengajuan $pengajuan)
    {
        if ($pengajuan->status === 'ditolak') {
            return redirect()->route('status.show', $pengajuan->id)
                             ->with('info', 'Pengajuan sudah ditolak sebelumnya.');
        }

        $role = auth()->user()->role;

        // hanya RT/RW yang boleh menolak (route sudah membatasi, tapi tetap amankan)
        if (!in_array($role, ['rt', 'rw'])) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        $pengajuan->update(['status' => 'ditolak']);
        $pengajuan->statusHistories()->create([
            'status' => 'ditolak',
            'changed_by' => auth()->id(),
            'note' => 'Pengajuan ditolak oleh ' . strtoupper($role) . ' (' . auth()->user()->name . ').',
        ]);

        return redirect()->route('status.show', $pengajuan->id)
                         ->with('success', 'Pengajuan berhasil ditolak.');
    }

    public function destroy(Pengajuan $pengajuan)
    {
        $pengajuan->delete();
        return redirect()->route('dashboard')
                         ->with('success', 'Pengajuan berhasil dihapus!');
    }
}
