# Testweb Jersey - Project Setup Guide

## Overview
Testweb Jersey adalah website jersey yang dibangun menggunakan PHP Native dengan pola MVC (Model-View-Controller). Website ini memiliki fitur lengkap untuk menampilkan produk jersey, blog, dan sistem admin yang powerful.

## Fitur Utama

### Frontend
- ✅ Homepage dengan konten dinamis
- ✅ Halaman produk dengan filter dan pencarian
- ✅ Halaman detail produk dengan galeri gambar
- ✅ Blog dengan sistem komentar
- ✅ Pencarian global
- ✅ Responsive design
- ✅ SEO-friendly URLs
- ✅ Lazy loading untuk gambar

### Backend (Admin)
- ✅ Sistem login yang aman
- ✅ Dashboard dengan statistik
- ✅ Manajemen produk (CRUD)
- ✅ Manajemen blog posts
- ✅ Manajemen halaman dinamis
- ✅ Pengaturan website global
- ✅ Media library
- ✅ Manajemen testimoni
- ✅ Manajemen logo klien

### Keamanan
- ✅ Password hashing dengan PHP password_hash()
- ✅ Validasi input yang ketat
- ✅ CSRF protection
- ✅ SQL injection prevention dengan PDO
- ✅ XSS protection
- ✅ reCAPTCHA integration

## Struktur Project

```
testweb-jersey/
├── app/
│   ├── controllers/          # Controller classes
│   │   ├── Admin/           # Admin controllers
│   │   ├── HomeController.php
│   │   ├── ProductController.php
│   │   ├── BlogController.php
│   │   └── ...
│   ├── models/              # Model classes
│   │   ├── Model.php        # Base model
│   │   ├── User.php
│   │   ├── Product.php
│   │   ├── Post.php
│   │   └── ...
│   ├── views/               # View templates
│   │   ├── frontend/        # Frontend views
│   │   ├── admin/           # Admin views
│   │   └── layouts/         # Layout templates
│   └── core/                # Core framework classes
│       ├── Database.php
│       ├── Router.php
│       ├── Request.php
│       └── Controller.php
├── config/                  # Configuration files
│   ├── database.php
│   └── app.php
├── database/                # Database files
│   └── schema.sql
├── public/                  # Public web directory
│   ├── assets/             # CSS, JS, images
│   ├── uploads/            # Uploaded files
│   ├── index.php           # Main entry point
│   └── .htaccess
├── .env.example            # Environment variables example
├── .htaccess               # Main .htaccess
├── install.php             # Installation script
└── README.md
```

## Instalasi

### 1. Persyaratan Sistem
- PHP 8.0 atau lebih tinggi
- MySQL 5.7 atau lebih tinggi
- Web server (Apache/Nginx)
- Extensions: PDO, PDO_MySQL, GD, JSON

### 2. Download dan Setup
```bash
# Clone atau download project
git clone [repository-url]
cd testweb-jersey

# Copy environment file
cp .env.example .env

# Edit konfigurasi database di .env
nano .env
```

### 3. Konfigurasi Database
Edit file `.env`:
```env
DB_HOST=localhost
DB_NAME=testweb_jersey
DB_USER=root
DB_PASS=your_password
```

### 4. Installasi Manual (Recommended)
**Installer telah dihapus untuk keamanan. Gunakan setup manual:**

1. **Setup Database:**
   ```bash
   # Buat database
   mysql -u root -p -e "CREATE DATABASE testweb_jersey CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
   
   # Import schema
   mysql -u root -p testweb_jersey < database/schema.sql
   ```

2. **Konfigurasi Environment:**
   ```bash
   # Copy environment file
   cp .env.example .env
   
   # Edit .env dengan kredensial database Anda
   nano .env
   ```

3. **Set Permissions:**
   ```bash
   chmod -R 755 storage/
   chmod -R 755 public/uploads/
   chmod 644 .env
   ```

4. **Setup Admin User:**
   ```sql
   -- Update admin user (password: admin123)
   UPDATE users SET 
       username = 'admin',
       email = 'admin@yourdomain.com',
       password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
       full_name = 'Administrator'
   WHERE id = 1;
   ```

**Lihat `SETUP_MANUAL.md` untuk panduan lengkap setup manual.**

## Konfigurasi

### Environment Variables (.env)
```env
# Database
DB_HOST=localhost
DB_NAME=testweb_jersey
DB_USER=root
DB_PASS=

# Application
APP_DEBUG=true
APP_URL=http://localhost

# Google Analytics
GOOGLE_ANALYTICS_ID=GA_MEASUREMENT_ID

# reCAPTCHA
RECAPTCHA_SITE_KEY=your_site_key
RECAPTCHA_SECRET_KEY=your_secret_key
RECAPTCHA_ENABLED=true

# WhatsApp
WHATSAPP_NUMBER=6281234567890
WHATSAPP_MESSAGE=Halo, saya tertarik dengan produk jersey Anda
```

### Pengaturan Website
Setelah login ke admin panel, Anda dapat mengatur:
- Nama website
- Meta title dan description default
- Google Analytics ID
- reCAPTCHA keys
- Nomor WhatsApp
- Kualitas kompresi gambar

## Penggunaan

### Admin Panel
1. Akses: `http://your-domain/admin/login`
2. Login dengan kredensial yang dibuat saat instalasi
3. Kelola produk, blog, halaman, dan pengaturan

### Frontend
1. Akses: `http://your-domain/`
2. Website akan menampilkan konten yang telah dikonfigurasi

## API Endpoints

### Frontend Routes
- `GET /` - Homepage
- `GET /produk` - Daftar produk
- `GET /produk/{slug}` - Detail produk
- `GET /blog` - Daftar blog posts
- `GET /blog/{slug}` - Detail blog post
- `POST /blog/{slug}/comment` - Tambah komentar
- `GET /search` - Pencarian
- `GET /page/{slug}` - Halaman dinamis

### Admin Routes
- `GET /admin/login` - Login form
- `POST /admin/login` - Proses login
- `GET /admin/dashboard` - Dashboard
- `GET /admin/products` - Kelola produk
- `GET /admin/posts` - Kelola blog
- `GET /admin/pages` - Kelola halaman
- `GET /admin/settings` - Pengaturan

## Database Schema

### Tabel Utama
- `users` - Data admin
- `products` - Data produk
- `posts` - Data blog posts
- `pages` - Halaman dinamis
- `categories` - Kategori blog
- `tags` - Tag blog
- `testimonials` - Testimoni klien
- `client_logos` - Logo klien
- `comments` - Komentar blog
- `configurations` - Pengaturan global

## Keamanan

### Implemented Security Features
1. **Password Hashing**: Menggunakan `password_hash()` dengan bcrypt
2. **SQL Injection Prevention**: Menggunakan PDO prepared statements
3. **XSS Protection**: HTML escaping di semua output
4. **CSRF Protection**: Token validation untuk form
5. **Input Validation**: Validasi ketat untuk semua input
6. **File Upload Security**: Validasi tipe dan ukuran file
7. **Session Security**: Secure session configuration

### Best Practices
- Selalu gunakan HTTPS di production
- Update password admin secara berkala
- Backup database secara rutin
- Monitor log error
- Update PHP dan dependencies

## Customization

### Menambah Fitur Baru
1. Buat model di `app/models/`
2. Buat controller di `app/controllers/`
3. Buat view di `app/views/`
4. Tambah route di `public/index.php`

### Mengubah Tampilan
1. Edit file CSS di `public/assets/css/style.css`
2. Edit template di `app/views/`
3. Gunakan Bootstrap 5 untuk styling

## Troubleshooting

### Common Issues

1. **Database Connection Error**
   - Periksa konfigurasi di `.env`
   - Pastikan MySQL service berjalan
   - Periksa kredensial database

2. **Permission Denied**
   - Set permission folder uploads: `chmod 755 public/uploads`
   - Set permission file .env: `chmod 644 .env`

3. **404 Error**
   - Pastikan mod_rewrite enabled
   - Periksa konfigurasi .htaccess
   - Pastikan document root mengarah ke folder public

4. **reCAPTCHA Error**
   - Periksa site key dan secret key
   - Pastikan domain terdaftar di Google reCAPTCHA

## Support

Untuk bantuan dan support:
- Dokumentasi lengkap tersedia di folder `docs/`
- Contoh penggunaan ada di folder `examples/`
- Issue tracker: [GitHub Issues]

## License

Project ini menggunakan lisensi MIT. Lihat file `LICENSE` untuk detail lengkap.

## Changelog

### v1.0.0 (2024-01-01)
- Initial release
- Complete MVC framework
- Admin panel dengan drag-drop editor
- Frontend responsive
- Security features
- SEO optimization