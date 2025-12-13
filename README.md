# 📱 Sistem Presensi Siswa SMKN 1 Bantul

<!-- CI/CD: 2025-12-13 23:09 WIB -->

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-9.52-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/Docker-Ready-2496ED?style=for-the-badge&logo=docker&logoColor=white" alt="Docker">
</p>

<p align="center">
  Sistem manajemen presensi siswa modern berbasis web dan mobile dengan fitur QR Code, geolokasi, dan foto real-time.
</p>

---

## 📋 Deskripsi

Sistem Presensi Siswa SMKN 1 Bantul adalah aplikasi manajemen kehadiran siswa yang komprehensif dengan fitur-fitur modern untuk memudahkan administrasi sekolah dalam mengelola presensi harian, jadwal pelajaran, dan data akademik.

### ✨ Fitur Utama

#### 🎯 Presensi & Kehadiran
- **QR Code Presensi** - Check-in/out otomatis dengan QR Code yang diperbarui setiap hari
- **Validasi Geolokasi** - Memastikan siswa berada di area sekolah saat presensi
- **Foto Verifikasi** - Capture foto siswa saat check-in dan check-out
- **Auto Absent** - Sistem otomatis menandai siswa alpha jika tidak presensi
- **Multi-Status** - Hadir, Terlambat, Sakit, Izin, Alpha dengan catatan

#### 👥 Manajemen Data
- **Data Siswa** - Lengkap dengan NISN, kelas, orang tua, tahun masuk
- **Data Guru** - NIP, mata pelajaran, wali kelas
- **Kelas & Jurusan** - Manajemen classroom dengan tingkat dan jurusan
- **Jadwal Pelajaran** - Jadwal mengajar per hari, mata pelajaran, dan guru
- **Wali Kelas** - Pengelompokan wali kelas per classroom

#### 📊 Laporan & Analitik
- **Rekap Presensi** - Per siswa, per kelas, per tanggal
- **Dashboard** - Statistik kehadiran real-time
- **Export Excel** - Laporan presensi dalam format XLSX dengan styling
- **Bolos Pelajaran** - Tracking siswa yang bolos per mata pelajaran

#### 🔐 Keamanan & Akses
- **Multi-Role** - Admin, Guru, Siswa dengan permissions berbeda
- **Session Management** - Satu device per siswa (mencegah sharing akun)
- **API Authentication** - Laravel Sanctum untuk mobile app
- **Rate Limiting** - Proteksi API dari abuse

#### 🚀 Teknologi Modern
- **RESTful API v1** - Clean API dengan versioning
- **Swagger Documentation** - API docs lengkap untuk Android developer
- **Service Layer** - Business logic terpisah dari controller
- **Enums & Casting** - Type-safe data dengan PHP 8.2 enums
- **Queue Workers** - Background jobs untuk performa optimal
- **Redis Cache** - Caching untuk response lebih cepat

---

## 🏗️ Arsitektur Sistem

```
┌─────────────────────────────────────────────────────────────┐
│                      Web Dashboard (Admin/Guru)              │
│                   Laravel Blade + Bootstrap 5                 │
└───────────────────────────┬─────────────────────────────────┘
                            │
┌───────────────────────────┴─────────────────────────────────┐
│                    Mobile App (Android)                      │
│              REST API (Laravel Sanctum)                      │
└───────────────────────────┬─────────────────────────────────┘
                            │
┌───────────────────────────┴─────────────────────────────────┐
│                   Backend Services Layer                     │
│  AttendanceService | QRCodeService | StudentService          │
└───────────────────────────┬─────────────────────────────────┘
                            │
┌───────────────────────────┴─────────────────────────────────┐
│                    Database Layer (MySQL 8.0)                │
│     Users | Students | Teachers | Attendances | Schedules    │
└──────────────────────────────────────────────────────────────┘
```

---

## 🛠️ Tech Stack

### Backend
- **Framework:** Laravel 9.52.21
- **PHP:** 8.2+ (with Opcache, Redis extension)
- **Database:** MySQL 8.0
- **Cache/Queue:** Redis 7
- **Authentication:** Laravel Sanctum
- **Permissions:** Spatie Laravel Permission
- **API Docs:** L5-Swagger (OpenAPI 3.0)

### Frontend
- **Template Engine:** Blade
- **CSS Framework:** Bootstrap 5
- **Icons:** Bootstrap Icons
- **Charts:** Chart.js
- **Alerts:** SweetAlert2
- **Build Tool:** Vite

### DevOps
- **Containerization:** Docker + Docker Compose
- **Web Server:** Nginx (Alpine)
- **Process Manager:** Supervisor
- **CI/CD:** GitHub Actions
- **Registry:** GitHub Container Registry

---

## 📦 Installation

### Prerequisites

- Docker & Docker Compose (recommended)
- OR PHP 8.2+, Composer, Node.js 18+, MySQL 8.0, Redis

### Quick Start dengan Docker

```bash
# Clone repository
git clone https://github.com/Alfian57/Presensi-Skansaba-Web.git
cd Presensi-Skansaba-Web

# Copy environment file
cp .env.example .env

# Update .env dengan konfigurasi Anda
# Terutama DB_HOST=mysql dan REDIS_HOST=redis

# Start semua services
docker-compose up -d

# Install dependencies & setup
docker-compose exec app composer install
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate --seed
docker-compose exec app php artisan storage:link
docker-compose exec app php artisan l5-swagger:generate
```

Aplikasi berjalan di:
- **Web:** http://localhost:8000
- **API Docs:** http://localhost:8000/api/documentation
- **phpMyAdmin:** http://localhost:8080

### Manual Installation

```bash
# Install PHP dependencies
composer install

# Install Node dependencies
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Run migrations
php artisan migrate --seed

# Build assets
npm run build

# Generate API documentation
php artisan l5-swagger:generate

# Link storage
php artisan storage:link

# Start development server
php artisan serve
```

---

## 📖 API Documentation

API dokumentasi lengkap tersedia dalam format OpenAPI 3.0:

- **Lokal:** http://localhost:8000/api/documentation
- **Produksi:** https://presensi.smkn1bantul.sch.id/api/documentation

### API Endpoints

```
Authentication:
POST   /api/v1/auth/register           # Register siswa
POST   /api/v1/auth/login              # Login
POST   /api/v1/auth/logout             # Logout
GET    /api/v1/auth/profile            # Get profile

Presensi:
POST   /api/v1/attendances/check-in    # Check-in presensi
POST   /api/v1/attendances/check-out   # Check-out presensi
GET    /api/v1/attendances              # List presensi
GET    /api/v1/attendances/today        # Presensi hari ini

Jadwal:
GET    /api/v1/schedules                # Jadwal pelajaran
GET    /api/v1/schedules/today          # Jadwal hari ini

Data Master:
GET    /api/v1/students                 # List siswa
GET    /api/v1/teachers                 # List guru
GET    /api/v1/classrooms               # List kelas
```

---

## 🚀 Deployment

Lihat [DEPLOYMENT.md](DEPLOYMENT.md) untuk panduan deployment lengkap.

### Quick Deploy dengan Docker

```bash
# Di production server
git pull origin main
docker-compose -f docker-compose.prod.yml up -d --build
docker-compose exec app php artisan migrate --force
docker-compose exec app php artisan optimize
```

### CI/CD Pipeline

Setiap push ke `main` branch akan otomatis:
1. ✅ Run tests
2. ✅ Code quality checks
3. ✅ Build Docker image
4. ✅ Push ke GitHub Container Registry
5. ✅ Deploy ke production (jika configured)

---

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run dengan coverage
php artisan test --coverage

# Run specific test
php artisan test --filter=AttendanceTest

# Code style check
./vendor/bin/pint --test

# Static analysis
./vendor/bin/phpstan analyse
```

---

## 📝 Default Credentials

Setelah seeding, gunakan credentials berikut:

### Admin
```
Email: admin@smkn1bantul.sch.id
Password: password
```

### Guru
```
Email: guru@smkn1bantul.sch.id
Password: password
```

### Siswa
```
Email: siswa@smkn1bantul.sch.id
Password: password
```

> ⚠️ **Penting:** Ganti semua password default di production!

---

## 🤝 Contributing

Kontribusi sangat diterima! Silakan:

1. Fork repository
2. Create feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

Pastikan kode Anda:
- ✅ Mengikuti PSR-12 coding standard
- ✅ Lulus semua tests
- ✅ Ter-format dengan Laravel Pint
- ✅ Memiliki dokumentasi yang jelas

---

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

## 🙏 Acknowledgments

- **SMKN 1 Bantul** - Client dan pengguna sistem
- **Laravel** - PHP framework yang powerful
- **Spatie** - Laravel packages yang berkualitas
- **Community** - Semua kontributor open source

---

## 📞 Support

Untuk pertanyaan, bug reports, atau feature requests:

- **Email:** support@smkn1bantul.sch.id
- **GitHub Issues:** [Create an issue](https://github.com/Alfian57/Presensi-Skansaba-Web/issues)
- **Developer:** [@Alfian57](https://github.com/Alfian57)

---

<p align="center">
  Made with ❤️ by <a href="https://github.com/Alfian57">Alfian</a> for SMKN 1 Bantul
</p>
