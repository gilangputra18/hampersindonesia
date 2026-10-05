# Starter website toko roti (Laravel + Vercel)

Paket ini adalah **overlay**: salin isinya ke atas proyek Laravel baru (timpa file yang sama).

## Setup lokal
```bash
composer create-project laravel/laravel bakery-site
# salin semua isi folder ini ke dalam bakery-site/
cd bakery-site
php artisan serve
```
Buka http://localhost:8000

## Yang perlu kamu ganti
- `config/site.php`: nama brand, kontak, kategori, produk, dan harga.
- `public/videos/hero.mp4`: video hero (disarankan 720p, di bawah 10 MB). Untuk video besar, upload ke Vercel Blob/CDN lalu set env `HERO_VIDEO_URL`.
- `public/images/`: foto produk `{nama-produk-slug}.jpg`, plus `hero-poster.jpg`, `feature.jpg`, `events.jpg`, `cat-hampers.jpg`, `cat-cakes.jpg`, `cat-cookies.jpg`, `cat-breads.jpg`. Pakai logo dan foto milikmu sendiri.

## Deploy ke Vercel
Vercel tidak menjalankan PHP secara native; panduan resminya memakai container (Docker + FrankenPHP):
https://vercel.com/kb/guide/laravel-php-with-docker
```bash
npm i -g vercel
php artisan key:generate --show     # salin hasilnya
vercel env add APP_KEY              # tempel di sini
vercel deploy --prod
```
Catatan: filesystem container tidak permanen. Untuk data, upload, atau sesi bersama pakai database eksternal, Vercel Blob, atau Redis.
