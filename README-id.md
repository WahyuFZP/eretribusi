# Sistem E-Retribusi

Sistem manajemen retribusi/tagihan elektronik canggih yang dibangun dengan Laravel, dirancang untuk mengelola tagihan perusahaan, pembayaran, dan faktur dengan kemampuan tagihan berulang otomatis.

## 🌟 Fitur

### Manajemen Tagihan
- **Tagihan Berulang Otomatis**: Atur tagihan berulang bulanan atau tahunan
- **Pelacakan Pembayaran**: Riwayat pembayaran dan pemantauan status yang komprehensif
- **Generasi Tagihan**: Pembuatan tagihan manual dan otomatis dengan penjadwalan
- **Manajemen Tanggal Jatuh Tempo**: Pengingat otomatis dan pelacakan keterlambatan

### Manajemen Perusahaan
- **Dukungan Multi-perusahaan**: Mengelola beberapa perusahaan dengan profil tagihan yang unik
- **Profil Perusahaan**: Informasi bisnis lengkap termasuk alamat, telepon, email
- **Kategori Bisnis**: Dukungan untuk berbagai jenis bisnis (badan usaha, jenis usaha)

### Sistem Faktur & Pembayaran
- **Generasi Faktur**: Pembuatan faktur profesional dengan ekspor PDF
- **Integrasi Payment Gateway**: Dukungan payment gateway Midtrans
- **Riwayat Pembayaran**: Jejak audit lengkap semua transaksi
- **Metode Pembayaran Beragam**: Dukungan untuk berbagai saluran pembayaran

### Manajemen Pengguna & Keamanan
- **Kontrol Akses Berbasis Peran**: Peran admin dan pengguna dengan izin yang tepat
- **Autentikasi**: Sistem login aman dengan Laravel Breeze
- **Perlindungan Data**: Soft delete dan validasi data yang tepat

## 🚀 Panduan Cepat

### Prasyarat
- PHP 8.2 atau lebih tinggi
- Composer
- Node.js & npm
- MySQL/MariaDB
- Web server (Apache/Nginx)

### Instalasi

1. **Clone repository**
```bash
git clone https://github.com/your-username/eretribusi.git
cd eretribusi
```

2. **Install dependensi PHP**
```bash
composer install
```

3. **Install dependensi frontend**
```bash
npm install
```

4. **Setup environment**
```bash
cp .env.example .env
php artisan key:generate
```

5. **Konfigurasi database**
Edit file `.env` Anda dengan kredensial database:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=eretribusi
DB_USERNAME=username_anda
DB_PASSWORD=password_anda
```

6. **Konfigurasi Midtrans (Payment Gateway)**
```env
MIDTRANS_SERVER_KEY=your_server_key
MIDTRANS_CLIENT_KEY=your_client_key
MIDTRANS_IS_PRODUCTION=false
```

7. **Jalankan migrasi dan seeder**
```bash
php artisan migrate --seed
```

8. **Build aset frontend**
```bash
npm run build
# atau untuk development
npm run dev
```

9. **Jalankan aplikasi**
```bash
php artisan serve
```

Kunjungi `http://localhost:8000` untuk mengakses aplikasi.

## 📋 Penggunaan

### Membuat Tagihan Berulang

1. Navigasi ke **Admin > Tagihan > Buat Tagihan**
2. Isi detail tagihan (perusahaan, jumlah, tanggal jatuh tempo)
3. Centang **"Aktifkan Tagihan Berulang Otomatis"**
4. Pilih frekuensi (Bulanan/Tahunan)
5. Simpan tagihan

### Mengelola Tagihan Berulang

- Akses **Admin > Tagihan > Kelola Berulang** untuk melihat semua tagihan berulang
- Generate tagihan secara manual atau biarkan sistem auto-generate
- Lihat tanggal tagihan berikutnya dan ubah pengaturan
- Aktifkan/nonaktifkan tagihan berulang

### Fitur Dashboard

- Pantau tagihan yang terlambat dan pembayaran yang akan datang
- Aksi cepat untuk generasi tagihan
- Ikhtisar status pembayaran
- Metrik performa perusahaan

## 🛠️ Stack Teknologi

### Backend
- **Framework**: Laravel 12.x
- **PHP**: 8.2+
- **Database**: MySQL dengan Eloquent ORM
- **Autentikasi**: Laravel Breeze
- **Permissions**: Spatie Laravel Permission
- **Generasi PDF**: DomPDF

### Frontend
- **CSS Framework**: Tailwind CSS
- **Komponen UI**: DaisyUI
- **Build Tool**: Vite
- **JavaScript**: Alpine.js
- **Animasi**: AOS (Animate On Scroll)

### Integrasi Pembayaran
- **Gateway**: Midtrans
- **Metode**: Kartu Kredit, Transfer Bank, E-Wallet

### Tools Pengembangan
- **Testing**: Pest PHP
- **Code Quality**: Laravel Pint
- **Development Environment**: Laravel Sail
- **Package Management**: Composer & npm

## 🔧 Perintah Artisan

### Manajemen Tagihan Berulang
```bash
# Generate tagihan berulang
php artisan bills:generate-recurring

# Preview tagihan yang akan di-generate (dry run)
php artisan bills:generate-recurring --dry-run

# Cek tagihan yang akan datang
php artisan bills:check-upcoming
```

### Perintah Development
```bash
# Jalankan test
php artisan test
# atau
./vendor/bin/pest

# Clear cache
php artisan optimize:clear

# Generate dokumentasi
php artisan route:list
```

## 📂 Struktur Proyek

```
app/
├── Console/Commands/     # Perintah Artisan
├── Http/
│   ├── Controllers/     # Controller aplikasi
│   └── Requests/       # Validasi form request
├── Models/             # Model Eloquent
│   ├── Bill.php       # Manajemen tagihan
│   ├── Company.php    # Profil perusahaan
│   ├── Invoice.php    # Sistem faktur
│   ├── Payment.php    # Pelacakan pembayaran
│   └── User.php       # Manajemen pengguna
└── Policies/          # Policy otorisasi

database/
├── factories/         # Factory model untuk testing
├── migrations/        # Migrasi skema database
└── seeders/          # Seeder database

resources/
├── views/            # Template Blade
├── css/              # File stylesheet
└── js/               # File JavaScript

routes/
├── web.php           # Route web
├── api.php           # Route API
└── auth.php          # Route autentikasi
```

## 🔒 Fitur Keamanan

- **Perlindungan CSRF**: Semua form dilindungi terhadap serangan CSRF
- **Pencegahan SQL Injection**: Eloquent ORM dengan prepared statement
- **Perlindungan XSS**: Blade template engine dengan escaping otomatis
- **Akses Berbasis Peran**: Sistem permission granular
- **Soft Delete**: Kemampuan pemulihan data
- **Validasi Input**: Validasi form request yang komprehensif

## ⚠️ Praktik Keamanan Terbaik

### Variabel Environment
- **Jangan pernah commit file `.env`** ke version control
- **Gunakan `.env.example`** sebagai template tanpa kredensial asli
- **Regenerate APP_KEY** setelah clone: `php artisan key:generate`
- **Gunakan password yang kuat** untuk database dan akun admin

### Deployment Produksi
- **Aktifkan HTTPS** di environment produksi
- **Set `APP_ENV=production`** dan `APP_DEBUG=false`
- **Gunakan API key dan kredensial database** khusus environment
- **Aktifkan rate limiting** untuk endpoint API
- **Update keamanan reguler** untuk semua dependensi

### Keamanan API
- **Validasi callback Midtrans** menggunakan verifikasi server key
- **Log semua transaksi pembayaran** untuk audit trail
- **Gunakan CORS** dengan benar untuk integrasi frontend

## 🚦 Testing

Jalankan test suite:
```bash
# Jalankan semua test
./vendor/bin/pest

# Jalankan file test spesifik
./vendor/bin/pest tests/Feature/BillTest.php

# Jalankan dengan coverage
./vendor/bin/pest --coverage
```

## 📈 Performa

- **Caching**: Dukungan Redis/Memcached untuk session dan cache
- **Optimasi Asset**: Vite untuk bundling CSS/JS yang optimal
- **Optimasi Database**: Indexing dan optimasi query yang tepat
- **Sistem Queue**: Pemrosesan background job untuk tugas berat

## 🤝 Kontribusi

1. Fork repository
2. Buat feature branch (`git checkout -b feature/fitur-menakjubkan`)
3. Commit perubahan Anda (`git commit -m 'Tambah fitur menakjubkan'`)
4. Push ke branch (`git push origin feature/fitur-menakjubkan`)
5. Buat Pull Request

### Panduan Pengembangan

- Ikuti standar coding PSR-12
- Tulis test untuk fitur baru
- Update dokumentasi untuk perubahan API
- Gunakan conventional commit messages

## 📄 Lisensi

Proyek ini dilisensikan di bawah MIT License - lihat file [LICENSE](LICENSE) untuk detail.

## 🆘 Dukungan

Untuk dukungan dan pertanyaan:
- Buat issue di GitHub
- Hubungi tim pengembang
- Cek dokumentasi di `/docs`

## 🏷️ Riwayat Versi

- **v1.2.0** - Fitur tagihan berulang dengan penjadwalan otomatis
- **v1.1.0** - Integrasi payment gateway dengan Midtrans  
- **v1.0.0** - Rilis awal dengan sistem tagihan dasar

---

**Dibangun dengan  menggunakan Laravel Framework**