# Rencana Perbaikan Database - Form Pengajuan Error

## Masalah yang Ditemukan
1. **Duplicate column name: alamat** - Migration `2026_04_24_131400_add_alamat_to_pengajuans_table.php` gagal karena kolom `alamat` sudah ada di migration pembuatan tabel.
2. **Tidak ada transaksi database** di `PengajuanController::store()` - jika gagal di tengah proses, data bisa tidak konsisten.
3. **SQLite busy timeout** tidak diatur - database bisa langsung error "database is locked".
4. **File upload tidak di-proteksi transaksi** - file bisa tersimpan tapi data gagal masuk database.

## Langkah Perbaikan

### Langkah 1: Fix Migration Duplicate Column
- File: `database/migrations/2026_04_24_131400_add_alamat_to_pengajuans_table.php`
- Perubahan: Tambahkan `if (!Schema::hasColumn(...))` sebelum menambah kolom

### Langkah 2: Tambah Transaksi di Controller Store
- File: `app/Http/Controllers/PengajuanController.php`
- Perubahan: Bungkus proses store dengan `DB::beginTransaction()`, `commit()`, `rollback()`
- Jika gagal, file yang sudah di-upload harus dihapus

### Langkah 3: Set SQLite Busy Timeout
- File: `config/database.php`
- Perubahan: Tambahkan `busy_timeout` di konfigurasi SQLite

### Langkah 4: Fix Enum Migration Jika Ada Masalah
- Periksa migration enum apakah juga ada duplicate issue

