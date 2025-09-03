# Testweb

Jawaban sebelumnya telah mencakup skema database dan BRD yang terperinci. Untuk membuatnya lebih lengkap dan komprehensif, saya akan menambahkan bagian-bagian yang menjelaskan secara praktis **alur kerja (workflow)**, **spesifikasi teknis tambahan**, dan **rencana deployment** untuk proyek Anda. Dengan demikian, dokumen ini akan menjadi panduan yang sangat solid dari hulu ke hilir.

---

### **BRD (Business Requirements Document)**

**Proyek:** Website Landing Page Perusahaan Jersey
**Versi:** 5.0 (Final)
**Tanggal:** 3 September 2025
**Metodologi Pengembangan:** PHP Native dengan Pola Desain MVC

---

### **1. Pendahuluan**

Dokumen ini adalah panduan lengkap untuk pengembangan website jersey. Tujuannya adalah membangun platform yang **fleksibel**, **aman**, dan **mudah dikelola** menggunakan PHP Native dengan pola MVC. Dokumen ini mencakup semua persyaratan, dari skema database hingga alur kerja implementasi, yang akan memastikan proyek berjalan lancar dan sesuai ekspektasi.

---

### **2. Persyaratan Fungsional yang Disempurnakan**

#### **2.1. Frontend (Tampilan Pengguna)**

* **Header:** Navigasi one-page, responsif, dan tombol "Hubungi Kami" statis yang mengarah ke WhatsApp.
* **Halaman Beranda:** Konten sepenuhnya dinamis melalui **drag-and-drop editor**. Komponen yang dapat di-drag:
    * **Hero Section:** Gambar besar, judul, dan tombol CTA.
    * **Testimonials Slider:** Menampilkan testimoni klien yang dapat diatur dari backend.
    * **Client Logo Carousel:** Carousel logo klien yang dinamis.
    * **Product Showcase:** Grid produk unggulan yang dipilih secara manual.
    * **Alur Pemesanan:** Elemen visual untuk menjelaskan proses pemesanan.
* **Produk & Blog:**
    * **Halaman Produk (Archive & Single):** Halaman grid produk dengan fitur filter, dan halaman detail produk dengan galeri gambar yang dapat di-zoom serta tombol langsung ke marketplace eksternal.
    * **Halaman Blog (Archive & Single):** Halaman daftar artikel dan halaman detail post dengan fitur komentar (tanpa login) yang dilindungi reCAPTCHA/Turnstile.
* **Pencarian:** Fitur pencarian global di header untuk produk dan post.
* **Performa & UX:**
    * **Lazy Loading:** Semua gambar (produk, post, galeri) harus menggunakan lazy loading.
    * **URL yang Bersih:** Semua URL harus SEO-friendly (misal: `/produk/jersey-keren`).

#### **2.2. Backend (Admin Dashboard)**

* **Login Aman:** Sistem login yang dilindungi oleh `password_hash` dan validasi sesi.
* **Modul Manajemen Konten:**
    * **Pages:** Editor visual **drag-and-drop** untuk halaman statis. 
    * **Products:** CRUD (Create, Read, Update, Delete) dengan form input untuk semua data produk, termasuk URL marketplace.
    * **Posts:** CRUD dengan editor **WYSIWYG** (seperti TinyMCE atau CKEditor) dan fitur kategori/tag.
* **Pengaturan Website Global:**
    * **Tab SEO:** Input untuk **meta title & description default**, dan **Google Analytics ID**.
    * **Tab Keamanan:** Input untuk **Site Key** dan **Secret Key** reCAPTCHA/Turnstile dengan opsi untuk mengaktifkan/menonaktifkan.
    * **Tab Media:** Pengaturan kualitas kompresi gambar saat diunggah.
* **Media Library:** File manager terpusat untuk mengelola semua gambar di website.

---

### **3. Skema Database yang Final**

Berikut adalah skema database yang telah disempurnakan.

* `users`: Menyimpan data admin.
* `pages`: Menyimpan konten halaman dinamis (termasuk homepage) dalam format JSON.
* `products`: Menyimpan data produk, termasuk URL marketplace.
* `posts`: Menyimpan konten blog dengan metadata SEO.
* `categories` & `tags`: Digunakan untuk klasifikasi post.
* `post_tags` (tabel pivot): Menghubungkan post dengan tag.
* `testimonials` & `client_logos`: Menyimpan data testimoni dan logo klien.
* `configurations`: Tabel kunci untuk menyimpan semua pengaturan global seperti kunci API reCAPTCHA, Google Analytics ID, dan meta tag default.

---

### **4. Arsitektur Teknis & Workflow**

#### **4.1. Arsitektur PHP Native (MVC)**

* **Folder Struktur:**
    * `/app/controllers/`: Logika alur aplikasi.
    * `/app/models/`: Berisi kelas yang berhubungan langsung dengan database.
    * `/app/views/`: Kode presentasi (HTML/PHP).
    * `/app/core/`: Kelas inti seperti `Database.php`, `Router.php`, dan `Request.php`.
* **Alur Kerja Request-Response:**
    1.  Pengguna mengakses URL (misal: `/produk/kaos-keren`).
    2.  File `public/index.php` (entry point) menerima permintaan.
    3.  Router mengurai URL dan memanggil method yang sesuai di `ProductController`.
    4.  `ProductController` memanggil `ProductModel` untuk mengambil data produk dari database.
    5.  Data dikirim ke `views/product/single.php` untuk ditampilkan.

#### **4.2. Spesifikasi Teknis Tambahan**

* **Database Connection:** Menggunakan PDO atau MySQLi yang terparameterisasi untuk mencegah SQL Injection.
* **Validator:** Validasi data input di backend sebelum disimpan ke database.
* **Optimasi Gambar:** Menggunakan library PHP (misal: `Intervention Image`) untuk kompresi dan thumbnail generation.
* **API Security:** Untuk fitur reCAPTCHA/Turnstile, backend akan memvalidasi token melalui API eksternal.

#### **4.3. Rencana Deployment**

* **Server:** Shared hosting atau VPS dengan PHP 8.x dan MySQL.
* **Struktur Server:** Folder `public` akan menjadi root dokumen web (`public_html`), sementara folder `/app` akan berada di luar direktori yang dapat diakses publik untuk alasan keamanan.
* **Backup:** Implementasi skrip backup otomatis untuk database dan file.

---

### **5. Lingkup Proyek & Deliverables**

Proyek ini akan menghasilkan website yang sepenuhnya fungsional, terorganisir dengan pola MVC, dan dilengkapi dengan dashboard admin yang kuat. Deliverables utama adalah **kode sumber yang bersih dan terdokumentasi** serta **database yang terstruktur** sesuai skema yang telah ditetapkan.
