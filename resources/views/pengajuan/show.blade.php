@extends('layouts.warga_app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-emerald-50 to-white pb-24">
    <!-- Header -->
    <div class="sticky top-0 z-10 bg-white border-b border-gray-100">
        <div class="flex items-center justify-between p-4 max-w-2xl mx-auto">
            <a href="{{ route('riwayat') }}" class="inline-flex items-center text-emerald-600 hover:text-emerald-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h1 class="text-lg font-bold text-gray-900">Detail Pengajuan</h1>
            <div class="w-6"></div>
        </div>
    </div>

    <div class="max-w-2xl mx-auto px-4 py-6 space-y-4">
        <!-- Status Badge Card -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border-l-4 border-emerald-500">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm text-gray-600">Status Saat Ini</span>
                <span class="text-xs text-gray-500">{{ $pengajuan->updated_at->format('d M Y') }}</span>
            </div>
            <div class="flex items-center gap-3">
                <div class="flex-shrink-0">
                    @if($pengajuan->status == 'diterima')
                        <div class="flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    @elseif($pengajuan->status == 'ditolak')
                        <div class="flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                    @else
                        <div class="flex items-center justify-center h-12 w-12 rounded-full bg-blue-100">
                            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    @endif
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">
                        @if($pengajuan->status == 'baru')
                            Menunggu
                        @elseif($pengajuan->status == 'disetujui_rt')
                            Disetujui RT
                        @elseif($pengajuan->status == 'diterima')
                            Selesai
                        @else
                            Ditolak
                        @endif
                    </h2>
                    <p class="text-sm text-gray-600">
                        @if($pengajuan->status == 'baru')
                            Pengajuan sedang diverifikasi
                        @elseif($pengajuan->status == 'disetujui_rt')
                            Menunggu persetujuan RW
                        @elseif($pengajuan->status == 'diterima')
                            Surat sudah siap
                        @else
                            Silakan ajukan ulang
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Progress Timeline -->
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h3 class="font-semibold text-gray-900 mb-6">Tahapan Proses</h3>
            <div class="space-y-6">
                <!-- Step 1 -->
                <div class="flex gap-4">
                    <div class="flex flex-col items-center">
                        <div class="flex items-center justify-center h-10 w-10 rounded-full {{ in_array($pengajuan->status, ['baru','disetujui_rt','diterima','ditolak']) ? 'bg-emerald-500 text-white' : 'bg-gray-200 text-gray-400' }}">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        @if(!($pengajuan->status == 'ditolak'))
                            <div class="w-1 h-12 bg-gray-200 mt-2"></div>
                        @endif
                    </div>
                    <div class="flex-1 pt-1">
                        <p class="font-semibold text-gray-900">Pengajuan Dikirim</p>
                        <p class="text-sm text-gray-600 mt-1">{{ $pengajuan->created_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="flex gap-4">
                    <div class="flex flex-col items-center">
                        <div class="flex items-center justify-center h-10 w-10 rounded-full {{ in_array($pengajuan->status, ['disetujui_rt','diterima']) ? 'bg-emerald-500 text-white' : 'bg-gray-200 text-gray-400' }}">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        @if(!in_array($pengajuan->status, ['baru', 'ditolak']))
                            <div class="w-1 h-12 bg-gray-200 mt-2"></div>
                        @endif
                    </div>
                    <div class="flex-1 pt-1">
                        <p class="font-semibold text-gray-900">Disetujui RT</p>
                        <p class="text-sm text-gray-600 mt-1">
                            @if(in_array($pengajuan->status, ['disetujui_rt','diterima']))
                                {{ $pengajuan->statusHistories->where('status', 'disetujui_rt')->first()?->created_at->format('d M Y, H:i') ?? 'Sedang diproses' }}
                            @else
                                Menunggu...
                            @endif
                        </p>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="flex gap-4">
                    <div class="flex flex-col items-center">
                        <div class="flex items-center justify-center h-10 w-10 rounded-full {{ $pengajuan->status == 'diterima' ? 'bg-emerald-500 text-white' : ($pengajuan->status == 'ditolak' ? 'bg-red-500 text-white' : 'bg-gray-200 text-gray-400') }}">
                            @if($pengajuan->status == 'ditolak')
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            @else
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            @endif
                        </div>
                    </div>
                    <div class="flex-1 pt-1">
                        <p class="font-semibold text-gray-900">{{ $pengajuan->status == 'ditolak' ? 'Ditolak' : 'Selesai' }}</p>
                        <p class="text-sm text-gray-600 mt-1">
                            @if($pengajuan->status == 'diterima')
                                {{ $pengajuan->statusHistories->where('status', 'diterima')->first()?->created_at->format('d M Y, H:i') ?? 'Disetujui' }}
                            @elseif($pengajuan->status == 'ditolak')
                                {{ $pengajuan->statusHistories->where('status', 'ditolak')->first()?->created_at->format('d M Y, H:i') ?? 'Ditolak' }}
                            @else
                                Menunggu...
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informasi Pengajuan -->
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h3 class="font-semibold text-gray-900 mb-4">Informasi Pengajuan</h3>
            <div class="space-y-4">
                <div>
                    <label class="text-xs font-semibold text-gray-600 uppercase">Jenis Surat</label>
                    <p class="text-gray-900 font-medium mt-1">{{ $pengajuan->jenis_surat }}</p>
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-600 uppercase">Nama Lengkap</label>
                    <p class="text-gray-900 font-medium mt-1">{{ $pengajuan->nama }}</p>
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-600 uppercase">NIK</label>
                    <p class="text-gray-900 font-medium mt-1">{{ $pengajuan->nik }}</p>
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-600 uppercase">Alamat</label>
                    <p class="text-gray-900 font-medium mt-1">{{ $pengajuan->alamat }}</p>
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-600 uppercase">Alasan</label>
                    <p class="text-gray-900 font-medium mt-1 whitespace-pre-wrap">{{ $pengajuan->alasan }}</p>
                </div>
                @if($pengajuan->file_path)
                    <div>
                        <label class="text-xs font-semibold text-gray-600 uppercase">Berkas Pendukung</label>
                        <a href="{{ Storage::url($pengajuan->file_path) }}" target="_blank" class="inline-flex items-center gap-2 mt-2 px-4 py-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 font-medium text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Download Berkas
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Status Note -->
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h3 class="font-semibold text-gray-900 mb-4">Catatan</h3>
            @if($pengajuan->status == 'baru')
                <div class="bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 rounded-lg text-sm">
                    <p class="font-medium">⏳ Sedang Diproses</p>
                    <p class="mt-1">Pengajuan Anda sedang ditinjau oleh RT. Tunggu update status berikutnya.</p>
                </div>
            @elseif($pengajuan->status == 'disetujui_rt')
                <div class="bg-cyan-50 border border-cyan-200 text-cyan-800 px-4 py-3 rounded-lg text-sm">
                    <p class="font-medium">✓ Disetujui RT</p>
                    <p class="mt-1">Pengajuan sudah disetujui RT, sedang menunggu verifikasi final dari RW.</p>
                </div>
            @elseif($pengajuan->status == 'diterima')
                <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg text-sm">
                    <p class="font-medium">✓ Diterima</p>
                    <p class="mt-1">Surat Anda sudah disetujui! Silakan mengambilnya ke kantor RT/RW.</p>
                </div>
            @else
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg text-sm">
                    <p class="font-medium">✗ Ditolak</p>
                    <p class="mt-1">Pengajuan Anda ditolak. Periksa kembali data Anda dan ajukan ulang jika diperlukan.</p>
                </div>
            @endif
        </div>

        <!-- Riwayat Status -->
        @if($pengajuan->statusHistories->count())
            <div class="bg-white rounded-2xl shadow-sm p-6">
                <h3 class="font-semibold text-gray-900 mb-4">Riwayat Perubahan</h3>
                <div class="space-y-4">
                    @foreach($pengajuan->statusHistories as $history)
                        <div class="flex gap-4 pb-4 border-b border-gray-100 last:border-0 last:pb-0">
                            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $history->status)) }}</p>
                                <p class="text-xs text-gray-600 mt-1">oleh {{ $history->changedBy->name ?? 'Sistem' }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $history->created_at->format('d M Y, H:i') }}</p>
                                @if($history->note)
                                    <p class="text-sm text-gray-700 mt-2">{{ $history->note }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- Action Buttons - Sticky Bottom -->
    @if(auth()->check() && in_array(auth()->user()->role, ['rt', 'rw']))
        @php
            $showApprove = (auth()->user()->role === 'rt' && $pengajuan->status === 'baru') ||
                           (auth()->user()->role === 'rw' && $pengajuan->status === 'disetujui_rt');
        @endphp
        
        @if($showApprove)
            <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 p-4">
                <div class="max-w-2xl mx-auto flex gap-3">
                    <form action="{{ route('status.reject', $pengajuan->id) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full px-4 py-3 bg-red-500 text-white font-semibold rounded-lg hover:bg-red-600 transition">
                            Tolak
                        </button>
                    </form>
                    <form action="{{ route('status.approve', $pengajuan->id) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full px-4 py-3 bg-emerald-500 text-white font-semibold rounded-lg hover:bg-emerald-600 transition">
                            Setujui
                        </button>
                    </form>
                </div>
            </div>
        @endif
    @endif
</div>

<style>
    .min-h-screen { min-height: 100vh; }
    .pb-24 { padding-bottom: 6rem; }
</style>
@endsection

