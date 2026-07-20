@extends('layouts.warga_app')

@section('content')
<div class="px-4 py-6 pb-24 space-y-6">
    <div class="flex justify-between items-center mb-2">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Riwayat Status</h2>
            <p class="text-gray-500 text-xs">Update perubahan status surat Anda.</p>
        </div>
        <a href="{{ route('dashboard') }}" class="text-sm font-semibold text-emerald-600 bg-emerald-50 px-4 py-2 rounded-xl">Kembali</a>
    </div>

    @if($histories->count())
        <div class="space-y-4">
            @foreach($histories as $h)
            <div class="bg-white p-5 rounded-3xl border border-gray-100 shadow-sm transition-all hover:shadow-md">
                <div class="flex justify-between items-start mb-3">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">{{ $h->created_at->format('d M Y, H:i') }}</span>

                    @php
                        $statusColors = [
                            'diterima' => 'bg-emerald-100 text-emerald-700',
                            'ditolak' => 'bg-red-100 text-red-700',
                            'disetujui_rt' => 'bg-blue-100 text-blue-700',
                            'default' => 'bg-gray-100 text-gray-600'
                        ];
                        $statusClass = $statusColors[$h->status] ?? $statusColors['default'];
                    @endphp
                    <span class="px-3 py-1 rounded-full text-[10px] font-bold {{ $statusClass }}">
                        {{ ucfirst(str_replace('_', ' ', $h->status)) }}
                    </span>
                </div>

                <h6 class="font-bold text-gray-800 mb-2">{{ $h->pengajuan->jenis_surat ?? '-' }}</h6>

                <div class="bg-gray-50 p-3 rounded-2xl text-xs text-gray-600 border border-gray-100">
                    <span class="font-bold text-gray-700">Catatan:</span> {{ $h->note ?? '-' }}
                </div>

                <div class="text-[10px] text-gray-400 mt-4 pt-3 border-t border-gray-50">
                    Diproses oleh: <span class="font-semibold text-gray-500">{{ $h->changedBy->name ?? 'Sistem' }}</span>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $histories->links('pagination::tailwind') }}
        </div>

    @else
        <div class="bg-gray-50 border border-gray-200 text-gray-500 p-6 rounded-3xl text-center text-sm">
            Belum ada riwayat perubahan status.
        </div>
    @endif
</div>
@endsection
