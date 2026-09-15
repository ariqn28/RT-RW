# PRD – Pedoman Pengembangan Proyek RT-RW

## Tujuan

Dokumen ini menjadi pedoman wajib sebelum melakukan perubahan apa pun pada proyek RT-RW. Setiap implementasi harus mengikuti struktur, teknologi, gaya penulisan, dan pola arsitektur yang sudah digunakan dalam proyek.

## Prosedur sebelum mengubah kode

Sebelum membuat atau mengubah fitur, lakukan pemeriksaan berikut:

1. Baca struktur folder proyek.
2. Temukan seluruh file yang berkaitan dengan permintaan pengguna.
3. Periksa route, controller, model, middleware, service, migration, Blade, layout, komponen, JavaScript, dan CSS yang relevan.
4. Pahami alur data dan fungsi yang sudah berjalan.
5. Periksa teknologi dan package yang telah digunakan melalui `composer.json`, `package.json`, konfigurasi Vite, serta file konfigurasi terkait.
6. Periksa pola penamaan, struktur kode, dan cara fitur serupa dibuat di dalam proyek.
7. Jelaskan secara singkat hasil pemeriksaan dan file yang kemungkinan perlu diubah.
8. Jangan mulai mengedit sebelum memahami kode yang berkaitan dengan permintaan.

## Aturan implementasi

Setiap perubahan wajib memenuhi ketentuan berikut:

* Gunakan struktur dan pola kode yang sudah ada.
* Sesuaikan implementasi baru dengan versi Laravel yang digunakan proyek.
* Jika proyek menggunakan Blade, lanjutkan dengan Blade.
* Jika proyek menggunakan layout, component, partial, atau `@extends`, gunakan pola yang sama.
* Jika proyek menggunakan Tailwind CSS atau utility class, gunakan teknologi tersebut.
* Jika proyek menggunakan Bootstrap atau CSS biasa, ikuti teknologi yang sudah tersedia.
* Gunakan asset, warna, font, ikon, komponen, dan library yang sudah ada jika masih sesuai.
* Jangan memasang package, framework, atau library baru tanpa alasan yang kuat dan persetujuan pengguna.
* Jangan membuat sistem kedua yang menduplikasi fungsi yang sudah tersedia.
* Jangan membuat file baru jika perubahan dapat diterapkan dengan baik pada file yang sudah ada.
* Pertahankan konsistensi nama file, class, method, route, variable, tabel, dan komponen.
* Jangan mengubah kontrak atau perilaku kode yang sudah berjalan apabila tidak diminta.
* Jangan mengubah backend ketika permintaan hanya berkaitan dengan frontend.
* Jangan mengubah frontend lain ketika permintaan hanya berkaitan dengan satu halaman atau komponen.
* Jangan menghapus fitur, validasi, otorisasi, atau pengamanan yang sudah tersedia.
* Hindari perubahan besar di luar ruang lingkup permintaan.
* Jangan mengisi fitur dengan data statis apabila proyek sudah memiliki sumber data dinamis.
* Jangan meninggalkan kode percobaan, komentar yang tidak diperlukan, atau file duplikat.

## Aturan perubahan UI/UX

Jika permintaan berkaitan dengan tampilan:

* Pertahankan fungsi dan alur data yang sudah berjalan.
* Gunakan sistem styling yang telah digunakan proyek.
* Sesuaikan desain dengan identitas aplikasi RT-RW.
* Buat tampilan sederhana, modern, profesional, rapi, dan tidak terlihat seperti template AI.
* Pastikan responsif di desktop, tablet, dan perangkat seluler.
* Perhatikan hierarki informasi, jarak, ukuran elemen, warna, kontras, dan keterbacaan.
* Tambahkan keadaan hover, focus, active, disabled, loading, empty, dan error jika relevan.
* Gunakan komponen yang konsisten pada seluruh halaman.
* Jangan mengubah nama input, route, method, action, CSRF, validasi, variable, dan proses backend tanpa kebutuhan yang jelas.

## Aturan perubahan backend

Jika permintaan berkaitan dengan backend:

* Pelajari alur fitur yang sudah ada sebelum menambahkan logika.
* Ikuti struktur controller, model, service, request validation, dan authorization yang digunakan proyek.
* Pertahankan kompatibilitas dengan database serta data yang sudah tersedia.
* Jangan mengubah atau menghapus kolom database tanpa migration yang aman.
* Jangan menaruh kredensial atau data sensitif secara langsung di dalam kode.
* Gunakan konfigurasi dan environment variable jika diperlukan.
* Tambahkan validasi dan penanganan error yang sesuai.
* Jangan mengubah endpoint atau format response yang sudah digunakan tanpa permintaan khusus.

## Batasan pekerjaan

* Kerjakan hanya bagian yang diminta pengguna.
* Jangan melakukan refactor besar di luar ruang lingkup.
* Jangan mengubah konfigurasi deployment tanpa diminta.
* Jangan mengubah branch.
* Jangan menjalankan `git commit`, `git push`, merge, atau Pull Request tanpa perintah pengguna.
* Jangan menghapus file atau data tanpa persetujuan pengguna.
* Jika terdapat pilihan implementasi yang dapat memengaruhi fitur lain, jelaskan terlebih dahulu dan tunggu keputusan pengguna.

## Pemeriksaan setelah perubahan

Setelah implementasi selesai:

1. Periksa perubahan menggunakan `git status` dan `git diff`.
2. Pastikan hanya file yang relevan yang berubah.
3. Periksa syntax dan jalankan pengujian yang relevan.
4. Pastikan fungsi lama tetap berjalan.
5. Pastikan tidak ada route, tampilan, asset, atau proses build yang rusak.
6. Pastikan desain responsif apabila terdapat perubahan UI.
7. Bersihkan kode percobaan dan output debugging.
8. Laporkan file yang dibuat atau diubah.
9. Jelaskan perubahan dan hasil pengujian secara singkat.
10. Sebutkan kendala atau bagian yang belum dapat diverifikasi.

## Format kerja wajib

Untuk setiap permintaan, kerjakan dengan urutan berikut:

### Tahap 1 – Analisis

* Jelaskan bagian proyek yang diperiksa.
* Jelaskan teknologi dan pola yang ditemukan.
* Sebutkan file yang berkaitan.
* Tentukan file yang perlu diubah.
* Jelaskan rencana perubahan secara singkat.

### Tahap 2 – Implementasi

* Lakukan perubahan hanya setelah analisis selesai.
* Ikuti kode dan pola proyek yang sudah ada.
* Batasi perubahan sesuai permintaan.

### Tahap 3 – Verifikasi

* Periksa `git diff`.
* Jalankan pengujian yang relevan.
* Laporkan hasil perubahan dan pengujian.

Setiap instruksi pengguna harus dibaca bersama dokumen PRD ini. Jika instruksi pengguna bertentangan dengan kondisi aktual proyek, jangan menebak atau memaksakan implementasi. Jelaskan perbedaannya dan minta keputusan pengguna sebelum melanjutkan.
