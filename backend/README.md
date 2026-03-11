# Zea Pet Shop – Backend API (Laravel 11 + PostgreSQL)

## Tech Stack
- **PHP** 8.2+
- **Laravel** 11
- **Laravel Sanctum** – token-based auth
- **PostgreSQL** – primary database

---

## Prerequisites

### 1 – Install PHP 8.2+
```powershell
winget install PHP.PHP
# or download from https://windows.php.net/download/ (VS16 x64 Thread Safe)
```

Tambahkan ekstensi berikut di `php.ini` (hapus tanda `;` di depannya):
```ini
extension=pdo_pgsql
extension=pgsql
extension=openssl
extension=mbstring
extension=fileinfo
extension=curl
```

### 2 – Install Composer
```powershell
winget install Composer.Composer
# or https://getcomposer.org/Composer-Setup.exe
```

### 3 – Install & jalankan PostgreSQL
Download: https://www.postgresql.org/download/windows/

Buat database:
```sql
CREATE DATABASE zea_pet_shop;
```

---

## Setup Backend

```powershell
# 1. Masuk ke folder backend
cd c:\zea-pet-shop\backend

# 2. Install dependencies Laravel
composer install

# 3. Copy file environment
copy .env.example .env

# 4. Generate application key
php artisan key:generate

# 5. Isi konfigurasi database di .env
#    DB_HOST=127.0.0.1
#    DB_PORT=5432
#    DB_DATABASE=zea_pet_shop
#    DB_USERNAME=postgres
#    DB_PASSWORD=your_password

# 6. Jalankan migrasi + seeder (buat tabel & isi data awal)
php artisan migrate --seed

# 7. Jalankan server API
php artisan serve
# → Berjalan di http://localhost:8000
```

---

## Setup Frontend (Vue)

```powershell
cd c:\zea-pet-shop

# Pastikan .env berisi:
# VITE_API_URL=http://localhost:8000/api

npm run dev
# → Berjalan di http://localhost:5173
```

---

## API Endpoints

### Products
| Method | Endpoint | Keterangan |
|--------|----------|------------|
| GET | `/api/products` | Daftar produk (filter: `category`, `search`, `sort`, `per_page`, `page`) |
| GET | `/api/products/{id}` | Detail produk |

**Query params untuk `/api/products`:**
- `category` – slug kategori (`dog`, `cat`, `bird`, `fish`, `rabbit`, `all`)
- `search`   – teks pencarian
- `sort`     – `price-asc` \| `price-desc` \| `rating` \| `popular`
- `promo`    – `1` untuk produk diskon saja
- `new`      – `1` untuk produk baru saja
- `per_page` – jumlah per halaman (default 12, max 50)
- `page`     – nomor halaman

### Categories
| Method | Endpoint | Keterangan |
|--------|----------|------------|
| GET | `/api/categories` | Daftar kategori + jumlah produk |
| GET | `/api/categories/{slug}/products` | Produk berdasarkan kategori |

### Orders
| Method | Endpoint | Keterangan |
|--------|----------|------------|
| POST | `/api/orders` | Buat pesanan (checkout) |
| GET | `/api/orders/{orderNumber}` | Lacak pesanan |

**Body POST `/api/orders`:**
```json
{
  "first_name": "Budi",
  "last_name": "Santoso",
  "email": "budi@email.com",
  "phone": "08123456789",
  "address": "Jl. Mawar No. 10",
  "city": "Bandung",
  "province": "Jawa Barat",
  "postal_code": "40123",
  "shipping_method": "jne",
  "payment_method": "transfer",
  "items": [
    { "product_id": 1, "qty": 2 },
    { "product_id": 5, "qty": 1 }
  ]
}
```

### Auth
| Method | Endpoint | Keterangan |
|--------|----------|------------|
| POST | `/api/auth/register` | Daftar akun |
| POST | `/api/auth/login` | Login → dapat token |
| POST | `/api/auth/logout` | Logout (butuh token) |
| GET | `/api/auth/user` | Profil user login |

### Admin (butuh Bearer token admin)
| Method | Endpoint | Keterangan |
|--------|----------|------------|
| POST | `/api/admin/products` | Tambah produk |
| PUT | `/api/admin/products/{id}` | Edit produk |
| DELETE | `/api/admin/products/{id}` | Hapus produk |
| GET | `/api/admin/orders` | Semua pesanan |
| PUT | `/api/admin/orders/{id}/status` | Update status pesanan |

---

## Default Admin Account
```
Email    : admin@zeapetshop.com
Password : password
```
> Ganti password setelah pertama kali login!

---

## Struktur Database

```
users           – akun customer & admin
categories      – dog, cat, bird, fish, rabbit
products        – semua produk
product_images  – gambar tambahan per produk
orders          – pesanan dari checkout
order_items     – item dalam pesanan (snapshot harga)
```

---

## Environment Variables Penting

| Variabel | Nilai |
|----------|-------|
| `APP_URL` | `http://localhost:8000` |
| `FRONTEND_URL` | `http://localhost:5173` |
| `DB_CONNECTION` | `pgsql` |
| `DB_DATABASE` | `zea_pet_shop` |
