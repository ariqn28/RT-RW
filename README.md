# Sistem Pengajuan Surat RT/RW

Aplikasi web berbasis Laravel untuk mengelola pengajuan surat-menyurat warga dengan sistem persetujuan bertingkat oleh petugas RT dan RW.

## Fitur Utama

- **Pengajuan Surat Online** — Warga dapat mengajukan berbagai jenis surat tanpa datang ke kantor
- **Sistem Approval Bertingkat** — RT menyetujui pertama, kemudian RW menyetujui akhir
- **Audit Trail** — Riwayat perubahan status tercatat lengkap dengan petugas yang mengubah
- **Role-Based Access Control** — 4 peran dengan hak akses berbeda
- **Upload Berkas** — Dukungan lampiran dokumen (PDF, DOC, JPG, PNG)
- **Dashboard Statistik** — Ringkasan data pengajuan dalam bentuk kartu

---

## Teknologi

| Komponen | Versi |
|----------|-------|
| Laravel | 10.x |
| PHP | 8.1+ |
| Bootstrap | 5.3 |
| Database | SQLite (Default) |
| Icons | Bootstrap Icons |

---

## Instalasi

### 1. Clone & Install Dependency
```bash
git clone <url-repo>
cd rt-rw
composer install
npm install
```

### 2. Konfigurasi Environment
```bash
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
touch database/database.sqlite
```

Edit `.env` sesuai konfigurasi database Anda:
```env
DB_DATABASE=rt_rw_db
DB_USERNAME=root
DB_PASSWORD=password
```

### 3. Migrasi & Seeder
```bash
php artisan migrate
php artisan db:seed
```

### 4. Jalankan Aplikasi
```bash
php artisan serve
```

Buka browser: `http://localhost:8000`

---

## Struktur Peran (Role)

| Peran | Deskripsi |
|-------|-----------|
| **Warga** | Mengajukan surat dan memantau status pengajuan sendiri |
| **RT** | Memverifikasi pengajuan baru (status: baru → disetujui_rt) |
| **RW** | Memberikan persetujuan akhir (status: disetujui_rt → diterima) |
| **Admin** | Mengelola pengguna dan melihat statistik keseluruhan |

---

## Alur Kerja Sistem

```
┌─────────┐    ajukan surat     ┌──────────┐
│  Warga  │ ──────────────────► │ Status:  │
└─────────┘                     │  Baru    │
                                └────┬─────┘
                                     │
                              ┌──────▼──────┐
                              │  RT Setujui │
                              └──────┬──────┘
                                     │
                              ┌──────▼───────────┐
                              │ Status:          │
                              │ Disetujui RT     │
                              └──────┬───────────┘
                                     │
                              ┌──────▼──────┐
                              │  RW Setujui │
                              └──────┬──────┘
                                     │
                              ┌──────▼────────┐
                              │ Status:       │
                              │ Diterima      │
                              │ (Selesai)     │
                              └───────────────┘
```

> **Catatan:** Jika ditolak oleh RT atau RW, status langsung menjadi `ditolak`.

---

## Hak Akses per Peran

### Warga
| Fitur | Akses |
|-------|-------|
| Dashboard | ✅ Hanya pengajuan sendiri |
| Ajukan Surat | ✅ `/ajukan` |
| Detail Status | ✅ Hanya milik sendiri |
| Setujui/Tolak | ❌ Tidak bisa |

### RT
| Fitur | Akses |
|-------|-------|
| Dashboard | ✅ Semua pengajuan |
| Setujui | ✅ Hanya status "baru" |
| Tolak | ✅ |
| Edit Status | ✅ |
| Hapus | ✅ |

### RW
| Fitur | Akses |
|-------|-------|
| Dashboard | ✅ Semua pengajuan |
| Setujui | ✅ Hanya status "disetujui_rt" |
| Tolak | ✅ |
| Edit Status | ✅ |
| Hapus | ✅ |

### Admin
| Fitur | Akses |
|-------|-------|
| Dashboard | ✅ Statistik keseluruhan |
| Manajemen User | ✅ CRUD pengguna |
| Proses Pengajuan | ❌ Tidak bisa (by design) |

---

## Daftar Route

| Route | Method | Role | Keterangan |
|-------|--------|------|------------|
| `/` | GET | Public | Redirect ke dashboard atau login |
| `/login` | GET/POST | Public | Halaman login |
| `/register` | GET/POST | Public | Registrasi (auto role: warga) |
| `/logout` | POST | Auth | Keluar |
| `/dashboard` | GET | Auth | Halaman utama |
| `/ajukan` | GET | Warga | Form pengajuan surat |
| `/ajukan` | POST | Warga | Simpan pengajuan |
| `/status/{id}` | GET | Warga/RT/RW | Detail pengajuan |
| `/status/{id}/edit` | GET | RT/RW | Form edit status |
| `/status/{id}` | PUT | RT/RW | Update status |
| `/status/{id}/setujui` | POST | RT/RW | Setujui pengajuan |
| `/status/{id}/tolak` | POST | RT/RW | Tolak pengajuan |
| `/status/{id}` | DELETE | RT/RW | Hapus pengajuan |
| `/admin/users` | GET | Admin | Daftar pengguna |
| `/admin/users/create` | GET | Admin | Form tambah user |
| `/admin/users` | POST | Admin | Simpan user baru |
| `/admin/users/{id}/edit` | GET | Admin | Form edit user |
| `/admin/users/{id}` | PUT | Admin | Update role user |
| `/admin/users/{id}` | DELETE | Admin | Hapus user |

---

## Skema Database

### Tabel `users`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint PK | Auto increment |
| name | string | Nama lengkap |
| email | string | Email unik |
| password | string | Hash password |
| role | enum | warga/rt/rw/admin |
| timestamps | datetime | created_at, updated_at |

### Tabel `pengajuans`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint PK | Auto increment |
| jenis_surat | string | Jenis surat yang diajukan |
| nama | string | Nama pemohon |
| nik | string(20) | NIK pemohon |
| alamat | string | Alamat pemohon |
| alasan | text | Alasan pengajuan |
| status | enum | baru/disetujui_rt/diterima/ditolak |
| file_path | string | Path file upload (nullable) |
| user_id | bigint FK | Relasi ke users |
| timestamps | datetime | created_at, updated_at |

### Tabel `pengajuan_status_histories`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint PK | Auto increment |
| pengajuan_id | bigint FK | Relasi ke pengajuans |
| status | enum | Status yang diubah |
| changed_by | bigint FK | User yang mengubah |
| note | text | Catatan perubahan |
| timestamps | datetime | created_at, updated_at |

---

## Jenis Surat yang Tersedia

1. Surat Keterangan Domisili
2. Surat Pengantar
3. Surat Keterangan Tidak Mampu
4. Surat Izin Keramaian
5. Surat Keterangan Usaha
6. Surat Keterangan Pernikahan
7. Surat Pengantar KTP/KK
8. Surat Keterangan Kelahiran
9. Surat Keterangan Kematian
10. Lainnya

---

## Keamanan

- Autentikasi session-based Laravel
- Role middleware (`CheckRole`) membatasi akses per halaman
- Warga hanya bisa melihat pengajuan sendiri (403 jika mencoba akses orang lain)
- Validasi form server-side
- Upload file dengan validasi tipe dan ukuran
- **Penting:** Registrasi otomatis menjadi warga, tidak bisa memilih role

---

## Pengembangan Selanjutnya

Lihat file `TODO.md` untuk daftar perbaikan dan fitur yang direncanakan.

---

## Lisensi

Proyek ini menggunakan lisensi MIT.
