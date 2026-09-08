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
        <article class="bg-white border border-gray-100 rounded-2xl p-4 mb-3 shadow-sm">
            <div class="flex justify-between gap-3">
                <h3 class="font-bold text-gray-800">{{ $due->title }}</h3>
                <strong class="text-emerald-700 whitespace-nowrap">Rp {{ number_format($due->amount, 0, ',', '.') }}</strong>
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
            @if($due->qris_image_path && in_array('qris', $due->payment_methods ?? [], true))
                <div class="mt-4 border-t border-gray-100 pt-3">
                    <p class="text-xs font-semibold text-gray-600 mb-2">Scan QRIS untuk membayar</p>
                    <img src="{{ asset($due->qris_image_path) }}" alt="QRIS {{ $due->title }}" class="max-w-[220px] rounded-lg border border-gray-200">
                </div>
            @endif
            @if($due->payment_methods)
                @php($selectedMethod = optional($paymentRequests->get($due->id))->payment_method)
                <form method="POST" action="{{ route('iuran.payment-method', $due) }}" class="mt-4 border-t border-gray-100 pt-3">
                    @csrf
                    <p class="text-xs font-semibold text-gray-600 mb-2">Pilih metode pembayaran</p>
                    <div class="grid grid-cols-3 gap-2">
                        @foreach($due->payment_methods as $method)
                            <label class="cursor-pointer rounded-lg border p-2 text-center text-xs {{ $selectedMethod === $method ? 'border-emerald-500 bg-emerald-50 text-emerald-700' : 'border-gray-200 text-gray-600' }}">
                                <input type="radio" name="payment_method" value="{{ $method }}" class="sr-only" {{ $selectedMethod === $method ? 'checked' : '' }} required>
                                {{ ['qris' => 'QRIS', 'cash' => 'Cash', 'transfer' => 'Transfer'][$method] ?? $method }}
                            </label>
                        @endforeach
                    </div>
                    <button type="submit" class="mt-3 w-full rounded-lg bg-emerald-600 px-3 py-2 text-xs font-bold text-white">{{ $selectedMethod ? 'Ubah pilihan pembayaran' : 'Pilih metode ini' }}</button>
                    @if($selectedMethod)
                        <p class="mt-2 text-center text-[10px] text-amber-700">Pilihan tersimpan, menunggu konfirmasi pengurus.</p>
                    @endif
                </form>
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
