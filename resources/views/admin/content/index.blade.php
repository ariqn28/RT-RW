@extends('layouts.app')

@section('content')
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
@if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif

<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h2 class="h4 mb-1">Iuran &amp; Konten Warga</h2><p class="text-muted mb-0">Terbitkan iuran, pilih metode QRIS/cash/transfer, dan kelola informasi warga.</p></div>
    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Kembali</a>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card card-panel p-4 mb-4">
            <h5>Upload Berita</h5>
            <form method="POST" action="{{ route('admin.content.announcements.store') }}" enctype="multipart/form-data" class="row g-3">
                @csrf
                <div class="col-12"><label class="form-label">Judul</label><input name="title" class="form-control" required maxlength="180"></div>
                <div class="col-12"><label class="form-label">Isi berita</label><textarea name="body" class="form-control" rows="4" required></textarea></div>
                <div class="col-12"><label class="form-label">Gambar (opsional, JPG/PNG/WEBP maksimal 4 MB)</label><input type="file" name="image" class="form-control" accept="image/jpeg,image/png,image/webp"></div>
                <div class="col-12"><button class="btn btn-primary">Publikasikan Berita</button></div>
            </form>
        </div>

        <div class="card card-panel p-4">
            <h5>Tambah Iuran</h5>
            <form method="POST" action="{{ route('admin.content.dues.store') }}" enctype="multipart/form-data" class="row g-3">
                @csrf
                <div class="col-md-8"><label class="form-label">Nama iuran</label><input name="title" class="form-control" placeholder="Iuran kebersihan September" required></div>
                <div class="col-md-4"><label class="form-label">Nominal (Rp)</label><input type="number" name="amount" min="0" class="form-control" required></div>
                <div class="col-12"><label class="form-label">Keterangan</label><textarea name="description" class="form-control" rows="2"></textarea></div>
                <div class="col-md-6"><label class="form-label">Batas pembayaran</label><input type="date" name="due_date" class="form-control"></div>
                <div class="col-md-6"><label class="form-label">Info pembayaran</label><input name="payment_info" class="form-control" placeholder="Transfer atau bayar ke bendahara"></div>
                <div class="col-12">
                    <label class="form-label d-block">Metode pembayaran</label>
                    <div class="d-flex flex-wrap gap-3">
                        <label><input type="checkbox" name="payment_methods[]" value="qris"> QRIS</label>
                        <label><input type="checkbox" name="payment_methods[]" value="cash"> Cash</label>
                        <label><input type="checkbox" name="payment_methods[]" value="transfer"> Transfer</label>
                    </div>
                </div>
                <div class="col-12"><label class="form-label">Gambar QRIS (wajib jika QRIS dipilih, maksimal 4 MB)</label><input type="file" name="qris_image" class="form-control" accept="image/jpeg,image/png,image/webp"></div>
                <div class="col-12"><button class="btn btn-success">Terbitkan Iuran</button></div>
            </form>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card card-panel p-4 mb-4">
            <h5>Kontak Pengurus</h5>
            <form method="POST" action="{{ route('admin.content.contact.update') }}" class="row g-3">
                @csrf @method('PUT')
                <div class="col-12"><label class="form-label">Nama kantor/pengurus</label><input name="office_name" value="{{ $contact->office_name }}" class="form-control" required></div>
                <div class="col-12"><label class="form-label">WhatsApp (format 628...)</label><input name="whatsapp" value="{{ $contact->whatsapp }}" class="form-control" placeholder="628123456789"></div>
                <div class="col-md-6"><label class="form-label">Telepon</label><input name="phone" value="{{ $contact->phone }}" class="form-control"></div>
                <div class="col-md-6"><label class="form-label">Email</label><input type="email" name="email" value="{{ $contact->email }}" class="form-control"></div>
                <div class="col-12"><label class="form-label">Alamat</label><textarea name="address" class="form-control" rows="2">{{ $contact->address }}</textarea></div>
                <div class="col-12"><label class="form-label">Pesan pembuka chat</label><textarea name="chat_greeting" class="form-control" rows="2" required>{{ $contact->chat_greeting }}</textarea></div>
                <div class="col-12"><button class="btn btn-warning">Simpan Kontak</button></div>
            </form>
        </div>
    </div>
</div>

<div class="card card-panel p-4 mt-4">
    <h5 class="mb-3">Konten Terbit</h5>
    @foreach($announcements as $announcement)
        <div class="d-flex justify-content-between align-items-center border-bottom py-2">
            <span><strong>{{ $announcement->title }}</strong><small class="text-muted d-block">Berita · {{ $announcement->created_at->format('d M Y') }}</small></span>
            <form method="POST" action="{{ route('admin.content.announcements.destroy', $announcement) }}">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Hapus</button></form>
        </div>
    @endforeach
    @foreach($dues as $due)
        <div class="d-flex justify-content-between align-items-center border-bottom py-2">
            <span><strong>{{ $due->title }}</strong><small class="text-muted d-block">Iuran · Rp {{ number_format($due->amount, 0, ',', '.') }}</small></span>
            <form method="POST" action="{{ route('admin.content.dues.destroy', $due) }}">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Hapus</button></form>
        </div>
    @endforeach
    @if($announcements->isEmpty() && $dues->isEmpty())<p class="text-muted mb-0">Belum ada konten terbit.</p>@endif
</div>

<div class="card card-panel p-4 mt-4">
    <h5 class="mb-3">Pilihan Pembayaran Warga</h5>
    @forelse($paymentRequests as $paymentRequest)
        <div class="d-flex justify-content-between align-items-center border-bottom py-2">
            <span>
                <strong>{{ $paymentRequest->user->name }}</strong>
                <small class="text-muted d-block">{{ $paymentRequest->due->title }} · Rp {{ number_format($paymentRequest->due->amount, 0, ',', '.') }}</small>
            </span>
            <span class="badge text-bg-warning">{{ ['qris' => 'QRIS', 'cash' => 'Cash', 'transfer' => 'Transfer'][$paymentRequest->payment_method] ?? $paymentRequest->payment_method }} · {{ ucfirst($paymentRequest->status) }}</span>
        </div>
    @empty
        <p class="text-muted mb-0">Belum ada warga yang memilih metode pembayaran.</p>
    @endforelse
</div>
@endsection