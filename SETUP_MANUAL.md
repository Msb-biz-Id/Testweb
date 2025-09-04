# Manual Setup Guide - Testweb Jersey

## 🚀 Setup Manual (Tanpa Installer)

Karena installer telah dihapus untuk keamanan, berikut adalah panduan setup manual yang aman:

## 1. Persyaratan Sistem

- PHP 8.0 atau lebih tinggi
- MySQL 5.7 atau lebih tinggi
- Web server (Apache/Nginx)
- Extensions: PDO, PDO_MySQL, GD, JSON, mbstring

## 2. Download dan Upload

```bash
# Upload semua file ke server
# Pastikan struktur folder seperti ini:
testweb-jersey/
├── app/
├── config/
├── database/
├── public/
├── storage/
├── .env.example
├── .htaccess
└── README.md
```

## 3. Setup Database

### 3.1. Buat Database
```sql
CREATE DATABASE testweb_jersey CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 3.2. Import Schema
```bash
mysql -u username -p testweb_jersey < database/schema.sql
```

### 3.3. Atau via phpMyAdmin
- Buka phpMyAdmin
- Pilih database `testweb_jersey`
- Klik tab "Import"
- Upload file `database/schema.sql`
- Klik "Go"

## 4. Konfigurasi Environment

### 4.1. Copy Environment File
```bash
cp .env.example .env
```

### 4.2. Edit File .env
```env
# Database Configuration
DB_HOST=localhost
DB_NAME=testweb_jersey
DB_USER=your_db_username
DB_PASS=your_db_password

# Application Configuration
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Google Analytics
GOOGLE_ANALYTICS_ID=GA_MEASUREMENT_ID

# reCAPTCHA Configuration
RECAPTCHA_SITE_KEY=your_site_key
RECAPTCHA_SECRET_KEY=your_secret_key
RECAPTCHA_ENABLED=true

# WhatsApp Configuration
WHATSAPP_NUMBER=6281234567890
WHATSAPP_MESSAGE=Halo, saya tertarik dengan produk jersey Anda

# Site Configuration
SITE_NAME=Testweb Jersey
SITE_DESCRIPTION=Jersey berkualitas tinggi dengan desain terbaik
META_TITLE_DEFAULT=Testweb Jersey - Jersey Berkualitas Tinggi
META_DESCRIPTION_DEFAULT=Temukan jersey berkualitas tinggi dengan desain terbaik di Testweb Jersey

# Upload Configuration
MAX_UPLOAD_SIZE=5242880
IMAGE_QUALITY=85
```

## 5. Setup Web Server

### 5.1. Apache Configuration

#### Document Root
Set document root ke folder `public/`:
```apache
<VirtualHost *:80>
    ServerName yourdomain.com
    DocumentRoot /path/to/testweb-jersey/public
    
    <Directory /path/to/testweb-jersey/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

#### .htaccess (sudah tersedia)
File `.htaccess` sudah dikonfigurasi dengan:
- URL rewriting
- Security headers
- Compression
- Caching

### 5.2. Nginx Configuration

```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /path/to/testweb-jersey/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.0-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }
}
```

## 6. Set Permissions

```bash
# Set permissions untuk folder storage dan uploads
chmod -R 755 storage/
chmod -R 755 public/uploads/
chmod 644 .env

# Set ownership (ganti dengan user web server)
chown -R www-data:www-data storage/
chown -R www-data:www-data public/uploads/
```

## 7. Setup Admin User

### 7.1. Via Database
```sql
-- Update admin user (password: admin123)
UPDATE users SET 
    username = 'admin',
    email = 'admin@yourdomain.com',
    password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    full_name = 'Administrator'
WHERE id = 1;
```

### 7.2. Atau Buat User Baru
```sql
INSERT INTO users (username, email, password, full_name, role) VALUES
('admin', 'admin@yourdomain.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'admin');
```

## 8. SSL Certificate (Recommended)

### 8.1. Let's Encrypt (Free)
```bash
# Install certbot
sudo apt install certbot python3-certbot-apache

# Get certificate
sudo certbot --apache -d yourdomain.com
```

### 8.2. Update .env untuk HTTPS
```env
APP_URL=https://yourdomain.com
```

## 9. Testing

### 9.1. Test Website
1. Buka `https://yourdomain.com`
2. Pastikan homepage load dengan benar
3. Test navigation menu
4. Test responsive design

### 9.2. Test Admin Panel
1. Buka `https://yourdomain.com/admin/login`
2. Login dengan kredensial admin
3. Test semua fitur admin

### 9.3. Test Database
```sql
-- Test koneksi database
SELECT COUNT(*) FROM users;
SELECT COUNT(*) FROM products;
SELECT COUNT(*) FROM posts;
```

## 10. Security Checklist

### 10.1. File Permissions
- [ ] .env file tidak accessible via web
- [ ] app/ folder tidak accessible via web
- [ ] config/ folder tidak accessible via web
- [ ] database/ folder tidak accessible via web

### 10.2. Server Security
- [ ] Firewall configured
- [ ] SSH key authentication
- [ ] Regular security updates
- [ ] Database user dengan limited privileges

### 10.3. Application Security
- [ ] APP_DEBUG=false di production
- [ ] Strong admin password
- [ ] reCAPTCHA enabled
- [ ] HTTPS enabled

## 11. Backup Strategy

### 11.1. Database Backup
```bash
# Daily backup script
mysqldump -u username -p testweb_jersey > backup_$(date +%Y%m%d).sql
```

### 11.2. File Backup
```bash
# Backup uploads folder
tar -czf uploads_backup_$(date +%Y%m%d).tar.gz public/uploads/
```

## 12. Monitoring

### 12.1. Log Files
- Application logs: `storage/logs/`
- Web server logs: `/var/log/apache2/` atau `/var/log/nginx/`
- PHP logs: `/var/log/php/`

### 12.2. Performance Monitoring
- Google Analytics setup
- Server monitoring tools
- Database performance monitoring

## 13. Troubleshooting

### 13.1. Common Issues

#### Database Connection Error
```bash
# Check database credentials in .env
# Test connection
mysql -u username -p -h localhost testweb_jersey
```

#### Permission Denied
```bash
# Fix permissions
chmod -R 755 storage/
chmod -R 755 public/uploads/
chown -R www-data:www-data storage/
chown -R www-data:www-data public/uploads/
```

#### 404 Errors
```bash
# Check .htaccess
# Check mod_rewrite enabled
sudo a2enmod rewrite
sudo systemctl restart apache2
```

#### SSL Issues
```bash
# Check certificate
sudo certbot certificates
# Renew if needed
sudo certbot renew
```

## 14. Maintenance

### 14.1. Regular Updates
- Update PHP dan dependencies
- Update web server
- Update database
- Security patches

### 14.2. Performance Optimization
- Enable caching
- Optimize images
- Minify CSS/JS
- Database optimization

## 15. Support

Jika mengalami masalah:
1. Check log files
2. Verify configuration
3. Test database connection
4. Check file permissions
5. Review server requirements

## 🎉 Setup Complete!

Setelah mengikuti panduan ini, website Testweb Jersey akan berjalan dengan aman dan optimal di production environment.