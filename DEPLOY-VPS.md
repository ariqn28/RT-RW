# Deploy RT/RW ke VPS

## 1. Paket server

Contoh Ubuntu 22.04/24.04:

```bash
sudo apt update
sudo apt install -y nginx mysql-server php8.2-fpm php8.2-cli php8.2-mysql php8.2-mbstring php8.2-xml php8.2-curl php8.2-zip unzip git
php -v
php -m | grep -E 'ctype|fileinfo|mbstring|openssl|pdo_mysql|tokenizer|xml'
composer --version
```

Pastikan versi PHP sesuai socket pada `deploy/nginx-rt-rw.conf`.

## 2. Aplikasi dan database

```bash
sudo mkdir -p /var/www/rt-rw
sudo chown -R $USER:$USER /var/www/rt-rw
git clone <URL-REPOSITORY> /var/www/rt-rw
cd /var/www/rt-rw
cp .env.vps.example .env
php artisan key:generate --force
```

Buat database dan user MySQL, lalu isi `.env` dengan `APP_URL`, kredensial database, dan `APP_KEY` yang benar. Jangan menyalin `.env` lokal Windows ke VPS karena berisi path SQLite Windows.

## 3. Deploy Laravel

```bash
chmod +x deploy/deploy.sh
./deploy/deploy.sh
```

Skrip deployment akan menghentikan proses jika `.env` belum ada, `APP_KEY` masih placeholder, `APP_DEBUG=true`, Composer tidak tersedia, atau koneksi MySQL gagal. Jangan meng-commit `.env` ke GitHub.

## 4. Nginx dan HTTPS

```bash
sudo cp deploy/nginx-rt-rw.conf /etc/nginx/sites-available/rt-rw
sudo ln -s /etc/nginx/sites-available/rt-rw /etc/nginx/sites-enabled/rt-rw
sudo nginx -t
sudo systemctl reload nginx
sudo apt install -y certbot python3-certbot-nginx
sudo certbot --nginx -d your-domain.example
```

Ganti `your-domain.example` di konfigurasi Nginx dan `.env` sebelum menjalankan perintah di atas.

## 5. Pemeriksaan

```bash
curl -i https://your-domain.example/health
```

Respons yang benar adalah JSON `{"status":"ok"}`. Cek login setelah itu; semua form Laravel menggunakan host aktif dan session HTTPS.

## Mode ngrok di Windows

Jalankan `start-ngrok.bat`. Script akan:

1. membersihkan cache Laravel;
2. menjalankan server lokal jika port 8000 belum aktif;
3. tidak membuat proses ngrok kedua;
4. membuka halaman login pada domain tunnel.

Untuk production VPS, gunakan domain + Nginx + HTTPS langsung. Ngrok cocok untuk demo atau testing, bukan sebagai reverse proxy permanen.