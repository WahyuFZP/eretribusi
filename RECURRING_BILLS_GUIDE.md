# Fitur Tagihan Otomatis (Auto-Recurring Bills)

## Deskripsi
Fitur tagihan otomatis memungkinkan admin untuk mengatur tagihan yang akan dibuat secara otomatis setiap bulan atau tahun pada tanggal yang sama.

## Cara Kerja

### 1. Membuat Tagihan dengan Auto-Generate
1. Buka halaman **Admin > Tagihan**
2. Klik **"Buat Tagihan"**
3. Isi data tagihan seperti biasa
4. Di bagian **"Pengaturan Tagihan Otomatis"**:
   - Centang **"Aktifkan tagihan otomatis berulang"**
   - Pilih frekuensi: **Bulanan** atau **Tahunan**
   - Tanggal tagihan akan mengikuti tanggal jatuh tempo yang dipilih
5. Simpan tagihan

### 2. Kelola Tagihan Otomatis
1. Buka halaman **Admin > Tagihan**  
2. Klik tombol **"Kelola Otomatis"**
3. Di halaman ini Anda bisa:
   - Melihat semua tagihan otomatis yang aktif
   - Melihat tanggal tagihan berikutnya
   - Generate tagihan secara manual
   - Edit pengaturan recurring
   - Nonaktifkan tagihan otomatis

### 3. Mengatur Tagihan Existing menjadi Otomatis
Untuk tagihan yang sudah ada, Anda bisa mengaktifkan fitur otomatis:
1. Di halaman **"Kelola Otomatis"** 
2. Klik **"Edit Pengaturan"** pada tagihan yang diinginkan
3. Atur frekuensi dan tanggal mulai
4. Simpan

### 4. Otomatis Generate Setelah Pembayaran
Sistem akan otomatis membuat tagihan periode berikutnya ketika:
- Tagihan dengan status recurring dibayar (status = paid)
- Tanggal tagihan berikutnya sudah tiba

## Jadwal Otomatis
- Command `bills:generate-recurring` akan berjalan **setiap hari pukul 09:00**
- Sistem akan mengecek semua tagihan recurring yang tanggal berikutnya <= hari ini
- Tagihan baru akan dibuat otomatis

## Menjalankan Manual
Admin bisa menjalankan generate manual kapan saja:
```bash
# Lihat apa yang akan di-generate (dry run)
php artisan bills:generate-recurring --dry-run

# Generate tagihan sekarang
php artisan bills:generate-recurring
```

## Informasi Teknis

### Field Database Baru (table `bills`)
- `is_recurring` (boolean): Apakah tagihan ini recurring
- `recurring_frequency` (string): 'monthly' atau 'yearly' 
- `recurring_day_of_month` (integer): Tanggal dalam bulan (1-31)
- `next_billing_date` (date): Tanggal tagihan berikutnya
- `parent_bill_id` (foreign key): ID tagihan induk (untuk tagihan yang auto-generated)

### Routes Baru
- `GET admin/tagihan/recurring` - Halaman kelola recurring
- `POST admin/tagihan/{bill}/setup-recurring` - Setup recurring pada bill existing
- `POST admin/tagihan/{bill}/disable-recurring` - Nonaktifkan recurring  
- `POST admin/tagihan/{bill}/generate-next` - Generate manual tagihan berikutnya

## Contoh Skenario
1. **Admin membuat tagihan retribusi untuk PT ABC**
   - Jumlah: Rp 500.000
   - Jatuh tempo: 10 Januari 2026
   - Aktifkan recurring: Bulanan
   
2. **Sistem otomatis akan:**
   - Membuat tagihan baru setiap tanggal 10 bulan berikutnya
   - Tagihan berikutnya: 10 Februari 2026, 10 Maret 2026, dst
   
3. **Setelah PT ABC bayar tagihan Januari:**
   - Status tagihan Januari = paid
   - Sistem langsung generate tagihan Februari (jika tanggalnya sudah tiba)

## Tips Penggunaan
- Gunakan untuk tagihan bulanan seperti retribusi, sewa, atau subscription
- Set tanggal jatuh tempo yang konsisten (misal selalu tanggal 1 atau 15)
- Monitor halaman "Kelola Otomatis" untuk memastikan sistem berjalan lancar
- Nonaktifkan recurring jika perusahaan sudah tidak aktif