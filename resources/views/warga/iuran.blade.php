@extends('layouts.warga_app')

@section('content')
<div class="px-4 py-6">
    @if(session('success'))
        <div class="mb-4 rounded-xl bg-emerald-50 p-3 text-sm text-emerald-800">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="mb-4 rounded-xl bg-red-50 p-3 text-sm text-red-700">{{ $errors->first() }}</div>
    @endif
    <div class="flex items-center justify-between mb-5">
        <div>
            <p class="text-xs uppercase tracking-wider text-emerald-600 font-bold">Keuangan warga</p>
            <h2 class="text-xl font-bold text-gray-800">Iuran Warga</h2>
        </div>
        <span class="text-2xl">Rp</span>
    </div>

    @forelse($dues as $due)
        <article id="iuran-{{ $due->id }}" class="bg-white border border-gray-100 rounded-2xl p-4 mb-3 shadow-sm">
            <div class="flex justify-between gap-3">
                <h3 class="font-bold text-gray-800">{{ $due->title }}</h3>
                <strong class="text-emerald-700 whitespace-nowrap">Rp {{ number_format($due->amountFor(auth()->id()), 0, ',', '.') }}</strong>
            </div>
            <div class="mt-2 flex items-center justify-between text-xs">
                @php($selectedRequest = $paymentRequests->get($due->id))
                <span class="font-semibold {{ $selectedRequest ? 'text-amber-600' : 'text-red-600' }}">{{ $selectedRequest ? 'Menunggu konfirmasi' : 'Belum dibayar' }}</span>
                <span class="text-gray-500">Total tagihan</span>
            </div>
            @if($due->description)<p class="text-sm text-gray-600 mt-2">{{ $due->description }}</p>@endif
            @if($due->due_date)<p class="text-xs text-amber-700 mt-3">Batas pembayaran: {{ $due->due_date->format('d M Y') }}</p>@endif
            @if($due->payment_info)<p class="text-xs text-gray-500 mt-1">{{ $due->payment_info }}</p>@endif
            @if($due->payment_methods)
                <div class="mt-3 flex flex-wrap gap-2">
                    @foreach($due->payment_methods as $method)
                        <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">{{ ['qris' => 'QRIS', 'cash' => 'Cash', 'transfer' => 'Transfer'][$method] ?? $method }}</span>
                    @endforeach
                </div>
            @endif
            @if($due->payment_methods)
                @php($selectedMethod = optional($selectedRequest)->payment_method)
                <form method="POST" action="{{ route('iuran.payment-method', $due) }}" class="mt-4 border-t border-gray-100 pt-3">
                    @csrf
                    <div class="mb-3 flex items-center justify-between">
                        <p class="text-sm font-bold text-gray-800">Pilih metode pembayaran</p>
                        @if($selectedMethod)<span class="text-[10px] font-semibold text-emerald-700">Pilihan tersimpan</span>@endif
                    </div>
                    <div class="grid grid-cols-1 gap-2">
                        @foreach($due->payment_methods as $method)
                            <label class="flex cursor-pointer items-center justify-between rounded-xl border p-3 text-sm {{ $selectedMethod === $method ? 'border-emerald-500 bg-emerald-50 text-emerald-700' : 'border-gray-200 text-gray-600' }}">
                                <span class="flex items-center gap-3">
                                    <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-gray-100 font-bold text-xs">{{ strtoupper(substr($method, 0, 1)) }}</span>
                                    <span>
                                        <span class="block font-semibold">{{ ['qris' => 'QRIS', 'cash' => 'Cash', 'transfer' => 'Transfer'][$method] ?? $method }}</span>
                                        <span class="block text-[10px] text-gray-500">Bayar sesuai instruksi pengurus</span>
                                    </span>
                                </span>
                                <input type="radio" name="payment_method" value="{{ $method }}" class="sr-only" {{ $selectedMethod === $method ? 'checked' : '' }} required>
                                <span class="h-4 w-4 rounded-full border-2 {{ $selectedMethod === $method ? 'border-emerald-600 bg-emerald-600' : 'border-gray-300' }}"></span>
                            </label>
                        @endforeach
                    </div>
                    <button type="submit" class="mt-3 w-full rounded-xl bg-emerald-600 px-3 py-3 text-sm font-bold text-white">{{ $selectedMethod ? 'Ubah pilihan pembayaran' : 'Lanjutkan pembayaran' }}</button>
                    @if($selectedMethod)
                        <a href="{{ route('iuran.invoice', $selectedRequest) }}" class="mt-2 block text-center text-xs font-bold text-emerald-700">Lihat invoice pembayaran</a>
                    @endif
                </form>
                @if($selectedMethod === 'qris' && $due->qris_image_path)
                    <div class="mt-4 rounded-xl border border-emerald-100 bg-emerald-50 p-3">
                        <p class="text-xs font-bold text-emerald-800 mb-2">Bayar sekarang melalui QRIS</p>
                        <img src="{{ asset($due->qris_image_path) }}" alt="QRIS {{ $due->title }}" class="max-w-[220px] rounded-lg border border-gray-200">
                    </div>
                @elseif($selectedMethod === 'cash')
                    <div class="mt-4 rounded-xl border border-amber-100 bg-amber-50 p-3 text-xs text-amber-900">
                        <strong>Bayar cash</strong>
                        <p class="mt-1">{{ $due->cash_payment_info ?: 'Silakan datang ke rumah atau kantor Pak RT/RW untuk melakukan pembayaran.' }}</p>
                    </div>
                @elseif($selectedMethod === 'transfer')
                    <div class="mt-4 rounded-xl border border-blue-100 bg-blue-50 p-3 text-xs text-blue-900">
                        <strong>Bayar melalui transfer</strong>
                        <p class="mt-1">{{ $due->bank_name }} · {{ $due->account_number }}</p>
                        <p>Atas nama: {{ $due->account_holder }}</p>
                        @if($due->bifast_number)<p>BI-FAST: {{ $due->bifast_number }}</p>@endif
                        <p class="mt-2">Setelah transfer, simpan bukti dan hubungi pengurus untuk konfirmasi.</p>
                    </div>
                @endif
            @endif
        </article>
    @empty
        <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-5 text-sm text-emerald-800">Belum ada informasi iuran aktif.</div>
    @endforelse

    @if($contact->whatsapp)
        @php($wa = preg_replace('/[^0-9]/', '', $contact->whatsapp))
        <a href="https://wa.me/{{ $wa }}?text={{ urlencode($contact->chat_greeting) }}" target="_blank" rel="noopener" class="block mt-5 bg-emerald-600 text-white text-center px-4 py-3 rounded-xl font-bold">Tanya pembayaran via WhatsApp</a>
    @endif

    <a href="{{ route('dashboard') }}" class="mt-4 inline-block bg-emerald-600 text-white px-4 py-2 rounded-lg text-sm">
        Kembali ke Dashboard
    </a>
</div>
@endsection
