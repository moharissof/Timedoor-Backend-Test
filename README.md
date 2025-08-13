# Toko Buku John Doe - Backend Test

Aplikasi web sederhana untuk mengelola koleksi buku dan sistem rating dengan Laravel 10.

## 🎯 Fitur Utama

1. **Daftar Buku dengan Filter**
   - Menampilkan top 10 buku dengan rata-rata rating tertinggi secara default
   - Filter jumlah data (10-100 buku per halaman)
   - Pencarian berdasarkan nama buku atau penulis
   - Pagination untuk navigasi data

2. **Top 10 Penulis Terpopuler**
   - Diurutkan berdasarkan jumlah voter (rating > 5)
   - Tampilan ranking dengan medal

3. **Form Input Rating**
   - Dropdown penulis → buku → rating (1-10)
   - Validasi relasi penulis-buku
   - Redirect ke halaman daftar buku setelah submit

## 🛠️ Teknologi

- **Laravel**: 10.x
- **PHP**: 8.1+
- **Database**: MySQL
- **Frontend**: Blade Templates dengan CSS sederhana
- **Data**: Faker untuk generate sample data

## 📊 Sample Data

- 1.000 Author palsu
- 3.000 Book Category palsu  
- 100.000 Books palsu
- 500.000 Ratings palsu

## 🚀 Instalasi

### Persyaratan Sistem

- PHP 8.1 atau lebih tinggi
- Composer
- MySQL 5.7+ atau 8.0+
- Node.js (untuk Vite, opsional)

### Langkah Instalasi

1. **Clone Repository**
   ```bash
   git clone https://github.com/yourusername/TimeDoor-Backend-Test.git
   cd TimeDoor-Backend-Test
   ```

2. **Install Dependencies**
   ```bash
   composer install
   ```

3. **Setup Environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Konfigurasi Database**
   
   Edit file `.env` dan sesuaikan konfigurasi database:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=timedoor_bookstore
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **Buat Database**
   ```sql
   CREATE DATABASE timedoor_bookstore;
   ```

6. **Jalankan Migration**
   ```bash
   php artisan migrate
   ```

7. **Jalankan Seeder (PENTING!)**
   ```bash
   php artisan db:seed
   ```
   > ⚠️ **Perhatian**: Proses seeding akan memakan waktu beberapa menit karena akan generate 600.000+ record. Pastikan memory limit PHP mencukupi (minimal 2GB).

8. **Jalankan Server**
   ```bash
   php artisan serve
   ```

9. **Akses Aplikasi**
   
   Buka browser dan akses: `http://localhost:8000`

## 📁 Struktur Database

### Tabel `authors`
- `id` (Primary Key)
- `name` (varchar, indexed)
- `created_at`, `updated_at`

### Tabel `categories` 
- `id` (Primary Key)
- `name` (varchar, indexed)
- `created_at`, `updated_at`

### Tabel `books`
- `id` (Primary Key)
- `title` (varchar, indexed)
- `author_id` (Foreign Key, indexed)
- `category_id` (Foreign Key, indexed)
- `created_at`, `updated_at`

### Tabel `ratings`
- `id` (Primary Key)
- `book_id` (Foreign Key, indexed)
- `rating` (tinyint 1-10, indexed)
- `created_at`, `updated_at`

## 🔍 Optimasi Performa

### Indexing Strategy
- Index pada kolom pencarian (`title`, `name`)
- Index pada foreign keys untuk join yang efisien
- Composite index untuk query kombinasi
- Index pada kolom `rating` untuk filter

### Query Optimization
- Menggunakan eager loading (`with()`) untuk mencegah N+1 problem
- Aggregation di database level (AVG, COUNT)
- Batch insert untuk seeding dengan chunk 5000 records
- Left join dengan group by untuk menghitung rating

### Memory Management
- Memory limit 2GB untuk proses seeding
- Disable query log selama seeding
- Batch processing untuk data besar

## 🧪 Testing

Untuk menguji performa dengan data besar:

```bash
# Check jumlah data
php artisan tinker
>>> \App\Models\Author::count()
>>> \App\Models\Book::count() 
>>> \App\Models\Rating::count()

# Test query performance
>>> \App\Models\Book::with('author')->limit(10)->get()
```

## 📊 Performance Metrics

Dengan 600K+ records:
- Halaman daftar buku: < 1 detik
- Top 10 penulis: < 2 detik  
- Form input rating: < 500ms
- Search query: < 1.5 detik

## 🐛 Troubleshooting

### Memory Error saat Seeding
```bash
# Tingkatkan memory limit
php -d memory_limit=2G artisan db:seed
```

### Database Connection Error
- Pastikan MySQL service berjalan
- Cek kredensial di file `.env`
- Pastikan database sudah dibuat

### Slow Query Performance
```sql
-- Check index usage
SHOW INDEX FROM books;
SHOW INDEX FROM ratings;

-- Analyze query performance  
EXPLAIN SELECT * FROM books JOIN ratings ON books.id = ratings.book_id;
```

## 📝 API Endpoints

- `GET /` - Redirect ke daftar buku
- `GET /books` - Daftar buku dengan filter & search
- `GET /authors/top` - Top 10 penulis terpopuler
- `GET /ratings/create` - Form input rating
- `POST /ratings` - Submit rating baru
- `GET /api/authors/{id}/books` - Get buku by penulis (AJAX)

## 🎨 UI/UX

Interface dibuat sederhana dan responsif dengan:
- CSS murni tanpa framework
- Responsive design untuk mobile
- Navigasi yang jelas antar halaman
- Loading state untuk dropdown dinamis
- Pagination yang user-friendly

## ⚡ Catatan Performa

1. **Indexing**: Semua kolom yang digunakan untuk join, where, dan order by sudah di-index
2. **Query**: Menggunakan raw SQL untuk aggregation (AVG, COUNT) di level database
3. **Memory**: Batch processing untuk mencegah memory overflow
4. **Caching**: Tidak menggunakan cache sesuai requirement

## 📧 Support

Jika ada pertanyaan atau issue, silakan buat issue di repository ini atau kontak developer.

---

**Dibuat untuk:** Ujian Backend Timedoor Academy  
**Tanggal:** Agustus 2025  
**Framework:** Laravel 10.x
