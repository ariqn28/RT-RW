@extends('layouts.warga_app')

@section('content')
@php
    $due = $paymentRequest->due;
    $methodLabels = ['qris' => 'QRIS', 'cash' => 'Cash', 'transfer' => 'Transfer'];
    $invoiceNumber = 'INV-' . now()->format('Ymd') . '-' . str_pad($paymentRequest->id, 5, '0', STR_PAD_LEFT);
@endphp
<div class="px-4 py-6">
    <a href="{{ route('iuran.index') }}" class="text-sm font-semibold text-emerald-700">&larr; Kembali ke Iuran</a>

    <div class="mt-4 rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
        <div class="flex items-start justify-between gap-3 border-b border-gray-100 pb-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-emerald-600">Invoice pembayaran</p>
                <h1 class="mt-1 text-lg font-bold text-gray-800">{{ $due->title }}</h1>
                <p class="mt-1 text-xs text-gray-500">{{ $invoiceNumber }}</p>
            </div>
            <span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-bold text-amber-700">{{ ucfirst($paymentRequest->status) }}</span>
        </div>

        <div class="flex items-center justify-between py-4">
            <span class="text-sm text-gray-500">Total pembayaran</span>
            <strong class="text-xl text-emerald-700">Rp {{ number_format($due->amount, 0, ',', '.') }}</strong>
        </div>

        <div class="rounded-xl bg-gray-50 p-4 text-sm">
            <p class="text-xs text-gray-500">Metode dipilih</p>
            <p class="mt-1 font-bold text-gray-800">{{ $methodLabels[$paymentRequest->payment_method] ?? $paymentRequest->payment_method }}</p>
        </div>

        @if($paymentRequest->payment_method === 'qris')
            <div class="mt-4 rounded-xl border border-emerald-100 bg-emerald-50 p-4">
                <h2 class="font-bold text-emerald-900">Bayar melalui QRIS</h2>
                @if($due->qris_image_path)
                    <img src="{{ asset($due->qris_image_path) }}" alt="QRIS {{ $due->title }}" class="mx-auto mt-3 max-w-[240px] rounded-lg border border-gray-200 bg-white">
                    <p class="mt-3 text-center text-xs text-emerald-800">Scan QRIS sesuai nominal invoice, lalu simpan bukti pembayaran.</p>
                @else
                    <p class="mt-2 text-xs text-emerald-800">QRIS belum diunggah pengurus. Silakan hubungi RT/RW.</p>
                @endif
            </div>
        @elseif($paymentRequest->payment_method === 'cash')
            <div class="mt-4 rounded-xl border border-amber-100 bg-amber-50 p-4 text-sm text-amber-900">
                <h2 class="font-bold">Bayar cash</h2>
                <p class="mt-1">{{ $due->cash_payment_info ?: 'Datang ke rumah atau kantor Pak RT/RW untuk melakukan pembayaran.' }}</p>
            </div>
        @elseif($paymentRequest->payment_method === 'transfer')
            <div class="mt-4 rounded-xl border border-blue-100 bg-blue-50 p-4 text-sm text-blue-900">
                <h2 class="font-bold">Bayar melalui transfer</h2>
                <p class="mt-2">Bank: <strong>{{ $due->bank_name ?: '-' }}</strong></p>
                <p>Nomor rekening: <strong>{{ $due->account_number ?: '-' }}</strong></p>
                <p>Nama penerima: <strong>{{ $due->account_holder ?: '-' }}</strong></p>
                @if($due->bifast_number)<p>BI-FAST: <strong>{{ $due->bifast_number }}</strong></p>@endif
                <p class="mt-3 text-xs">Setelah transfer, simpan bukti dan hubungi pengurus untuk konfirmasi.</p>
            </div>
        @endif

        <p class="mt-5 text-center text-xs text-gray-500">Invoice ini menunggu konfirmasi pembayaran dari pengurus.</p>
    </div>
</div>
@endsection
