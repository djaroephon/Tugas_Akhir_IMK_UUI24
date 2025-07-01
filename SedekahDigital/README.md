# 🛠️ Backend Laravel 12 - API Donasi Panti Asuhan

Proyek ini adalah backend REST API menggunakan **Laravel 12** untuk mengelola data panti asuhan dan donasi online.

## 🚀 Instalasi

```bash
git clone https://github.com/username/nama-repo.git
cd 
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve

| Teknologi       | Keterangan                                                     |
| --------------- | -------------------------------------------------------------- |
| Laravel 12      | Framework PHP untuk REST API dan backend yang aman dan efisien |
| MySQL           | Sistem manajemen basis data relasional                         |
| Sanctum         | Autentikasi token sederhana untuk API                          |
| Laravel Artisan | Tool CLI bawaan Laravel untuk migrasi dan development          |
| CORS            | Untuk mengizinkan akses API dari frontend berbeda domain       |



| Method | Endpoint                    | Deskripsi                  |
| ------ | --------------------------- | -------------------------- |
| GET    | /api/kamar                  | Menampilkan semua kamar    |
| POST   | /api/kamar                  | Tambah kamar baru          |
| PUT    | /api/kamar/{id}             | Update data kamar          |
| DELETE | /api/kamar/{id}             | Hapus data kamar           |
| GET    | /api/anak                   | Tampilkan semua anak panti |
| POST   | /api/anak                   | Tambah anak panti          |
| GET    | /api/donasi                 | Lihat daftar donasi        |
| POST   | /api/donasi                 | Input donasi               |

