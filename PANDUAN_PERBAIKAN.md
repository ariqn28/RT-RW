# Panduan Perbaikan Form Pengajuan RT/RW

## Status Saat Ini
- Server: **BERJALAN** (HTTP 200 OK)
- Database: **AMAN** (4 data pengajuan tetap ada)
- PHP POST: **NORMAL** (pure PHP POST berhasil)
- Laravel POST: **CRASH** saat submit form pengajuan

## Penyebab Kemungkinan
Berdasarkan investigasi, error "Gagal terhubung ke server" kemungkinan disebabkan oleh:
1. **Session/Cookie corrupt** - Laravel session expired atau cookie tidak valid
2. **CSRF token mismatch** - Token form tidak cocok dengan session
3. **Middleware conflict** - Ada middleware yang crash saat POST
4. **Browser cache** - Cache lama mengganggu request

---

## Langkah Perbaikan (Urut dari yang Paling Mudah)

### Langkah 1: Clear Browser Cache & Cookie (WAJIB)
1. Buka browser Chrome/Edge
2. Tekan **Ctrl+Shift+Delete**
3. Pilih:
   - ✅ Cookies and other site data
   - ✅ Cached images and files
   - ⏰ Time range: **All time**
4. Klik **Clear data**
5. Tutup browser sepenuhnya
6. Buka ulang browser
7. Akses: http://127.0.0.1:8000/login
8. Login dengan akun Anda

### Langkah 2: Test Submit Tanpa File
1. Login ke aplikasi
2. Buka: http://127.0.0.1:8000/ajukan
3. Isi semua field form
4. **JANGAN upload file** (biarkan kosong)
5. Klik **Kirim**

**Jika berhasil** → Masalahnya di file upload. Lanjut ke Langkah 4.
**Jika gagal** → Lanjut ke Langkah 3.

### Langkah 3: Test dengan Endpoint Bypass
1. Buka: http://127.0.0.1:8000/test_laravel_multipart.html
2. Isi form dan klik **Kirim ke Laravel Test**
3. Perhatikan hasilnya:
   - **Jika muncul JSON** → Laravel bisa handle POST, masalahnya di auth/session form asli
   - **Jika "can't reach page"** → Ada masalah fundamental di Laravel POST

### Langkah 4: Periksa Ukuran File
Jika error hanya muncul saat upload file:
- Maksimal file: **1MB** (sudah diatur di form)
- Format yang diizinkan: PDF, DOC, DOCX, JPG, PNG
- Jika file > 1MB, kompres dulu atau gunakan file lebih kecil

### Langkah 5: Restart Server (Jika Semua Gagal)
1. Buka terminal yang menjalankan server (biasanya ada jendela CMD/PS terpisah)
2. Tekan **Ctrl+C** untuk menghentikan server
3. Tunggu 5 detik
4. Jalankan ulang: klik file **start-server.bat** atau ketik di terminal:
   ```
   php artisan serve
   ```
5. Ulangi Langkah 1-2

---

## File Test yang Tersedia

| File | URL | Kegunaan |
|------|-----|----------|
| test_diagnosis.html | http://127.0.0.1:8000/test_diagnosis.html | Halaman diagnosis lengkap |
| test_pure_post.html | http://127.0.0.1:8000/test_pure_post.html | Test POST tanpa Laravel |
| test_laravel_multipart.html | http://127.0.0.1:8000/test_laravel_multipart.html | Test POST ke Laravel |
| api_ajukan.php | http://127.0.0.1:8000/api_ajukan.php | Endpoint bypass Laravel |

---

## Jika Masih Gagal

Silakan cek terminal server (CMD/PS yang menjalankan `php artisan serve`) saat Anda klik **Kirim** di form. Copy pesan error yang muncul di terminal dan beritahu saya.

## Data Aman
✅ Database SQLite: `database/database.sqlite`  
✅ Backup tersedia: `database/database_backup_20260428_105034.sqlite`  
✅ 4 data pengajuan tidak akan hilang
