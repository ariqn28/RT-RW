# 🚀 Panduan Menjalankan Aplikasi RT/RW System

## ✅ Prasyarat (Sudah Terinstall)
- PHP 8.3 (via WinGet)
- Composer (untuk Laravel)
- SQLite (built-in dengan PHP)

---

## 🎯 Cara Menjalankan (2 Langkah Saja)

### **Langkah 1: Jalankan Server**

**Cara A: Klik File Batch (Paling Mudah)**
1. Buka folder `C:\Users\ASUS\rt-rw`
2. Klik 2x file **start-server.bat**
3. Jangan tutup jendela CMD yang muncul!

**Cara B: Lewat Terminal VSCode**
1. Buka VSCode
2. Tekan `Ctrl + Shift + ` (backtick) untuk buka terminal
3. Ketik:
```powershell
$env:PHPRC="C:\Users\ASUS\AppData\Local\Microsoft\WinGet\Packages\PHP.PHP.NTS.8.3_Microsoft.Winget.Source_8wekyb3d8bbwe\php.ini"
cd c:\Users\ASUS\rt-rw
php artisan serve --host=127.0.0.1 --port=8000
```
4. Jangan tutup terminal!

---

### **Langkah 2: Buka di Browser**

Buka browser (Chrome/Edge/Firefox), ketik:

```
http://127.0.0.1:8000/login
```

---

## 👤 Login

| Email | Password | Role |
|-------|----------|------|
| ariq@gmail.com | 12345678 | Warga |

---

## 📋 Cara Mengajukan Surat

1. Klik **"Ajukan Surat"** di sidebar kiri
2. Isi form:
   - **Jenis Surat:** Pilih salah satu (contoh: Surat Keterangan Domisili)
   - **Nama:** Nama lengkap Anda
   - **NIK / NIM:** Nomor identitas
   - **Alamat:** Alamat lengkap
   - **Alasan:** Jelaskan keperluan pengajuan
   - **Upload Berkas:** (opsional, max 2MB)
3. Klik tombol **Kirim**

---

## 📊 Melihat Status Pengajuan

1. Kembali ke **Dashboard** (klik logo RT/RW atau menu Dashboard)
2. Lihat daftar pengajuan di tabel
3. Klik tombol **Detail** untuk melihat:
   - Status saat ini
   - Progress step (Diajukan → Disetujui RT → Selesai)
   - Riwayat perubahan status

---

## 🔧 Troubleshooting

### **Error: "could not find driver"**
**Solusi:**
1. Tutup semua terminal CMD
2. Buka ulang dengan cara **Langkah 1** di atas
3. Pastikan menggunakan `start-server.bat` atau `$env:PHPRC` di terminal

### **Error: "Unable to connect" / "Connection refused"**
**Solusi:**
1. Pastikan server masih berjalan (jendela CMD tidak ditutup)
2. Coba refresh halaman browser
3. Jika mati, ulangi **Langkah 1**

### **Halaman putih / tidak muncul**
**Solusi:**
1. Tunggu 5-10 detik (server sedang loading)
2. Refresh halaman (F5)
3. Cek apakah URL benar: `http://127.0.0.1:8000/login`

---

## 💾 Tentang Database

Aplikasi ini menggunakan **SQLite** (bukan MySQL/XAMPP):
- File database: `database/database.sqlite`
- Tidak perlu XAMPP/MySQL
- Tidak perlu phpMyAdmin
- Data tersimpan permanen di file

Untuk melihat data langsung, install **DB Browser for SQLite**:
https://sqlitebrowser.org/dl/

Lalu buka file: `C:\Users\ASUS\rt-rw\database\database.sqlite`

---

## ⚠️ Yang Tidak Perlu Dilakukan

❌ Buka XAMPP Control Panel
❌ Nyalakan Apache/MySQL di XAMPP
❌ Buka `localhost/phpmyadmin`
❌ Install MySQL

✅ Cukup jalankan `start-server.bat` dan buka browser

---

## 📞 Butuh Bantuan?

Jika masih ada error, cek:
1. Apakah jendela CMD server masih terbuka?
2. Apakah URL yang dibuka benar `http://127.0.0.1:8000/login`?
3. Coba restart: tutup CMD, lalu klik `start-server.bat` lagi

