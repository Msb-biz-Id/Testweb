# 🎣 TUTORIAL SISTEM MANCING ROBLOX LENGKAP
## Panduan Lengkap dalam Bahasa Indonesia

### 📋 DAFTAR ISI
1. [Pengenalan Sistem](#pengenalan-sistem)
2. [Cara Instalasi](#cara-instalasi)
3. [Fitur Utama](#fitur-utama)
4. [Cara Bermain](#cara-bermain)
5. [Sistem Ekonomi](#sistem-ekonomi)
6. [Sistem Ranking](#sistem-ranking)
7. [Kustomisasi](#kustomisasi)
8. [Troubleshooting](#troubleshooting)

---

## 🎮 PENGENALAN SISTEM

Sistem mancing Roblox ini adalah game fishing yang lengkap dengan fitur:
- **Toko Peralatan**: Beli pancingan dan umpan yang lebih baik
- **Pasar Ikan**: Jual ikan yang sudah ditangkap
- **Sistem Duit**: Uang dengan nilai besar (ribuan dolar)
- **Sistem Ranking**: 7 level dari pemula hingga master
- **Sistem Hadiah**: Kirim uang ke pemain lain
- **4 Spot Memancing**: Berbagai lokasi di peta

---

## 🛠️ CARA INSTALASI

### Langkah 1: Persiapan File
1. Buka Roblox Studio
2. Buat game baru atau buka game yang sudah ada
3. Pastikan Anda memiliki akses ke:
   - ServerScriptService
   - ReplicatedStorage
   - StarterPlayer > StarterPlayerScripts

### Langkah 2: Instalasi Script
1. **Buat RemoteEvents terlebih dahulu:**
   - Masukkan file `RemoteEvents.lua` ke ReplicatedStorage
   - Jalankan script ini untuk membuat semua RemoteEvents

2. **Instalasi Script Server:**
   - `MainModule.lua` → ServerScriptService
   - `FishingSystemServer.lua` → ServerScriptService
   - `MapLocations.lua` → ServerScriptService

3. **Instalasi Script Client:**
   - `FishingSystemClient.lua` → StarterPlayerScripts
   - `ShopSystem.lua` → StarterPlayerScripts
   - `InventorySystem.lua` → StarterPlayerScripts
   - `LocationInteraction.lua` → StarterPlayerScripts

### Langkah 3: Pengaturan DataStore
1. Buka Game Settings
2. Pilih Security
3. Aktifkan "Allow HTTP Requests"
4. Aktifkan "Enable Studio Access to API Services"

---

## ✨ FITUR UTAMA

### 🏪 Toko Peralatan (Platform Biru)
- **Lokasi**: Platform biru dengan tanda toko
- **Pancingan Tersedia**:
  - Basic Rod (Gratis) - Level 1
  - Wooden Rod - $50,000 - Level 2
  - Steel Rod - $150,000 - Level 3
  - Golden Rod - $500,000 - Level 4
- **Umpan Tersedia**:
  - Worm - $500 - Level 1
  - Bread - $1,500 - Level 2
  - Cheese - $3,000 - Level 3
  - Premium Bait - $10,000 - Level 4

### 💰 Pasar Ikan (Platform Hijau)
- **Lokasi**: Platform hijau dengan tanda pasar
- **Jual Ikan**: Konversi ikan menjadi uang
- **Harga Ikan**:
  - Common Fish: $1,000 per ekor
  - Rare Fish: $5,000 per ekor
  - Epic Fish: $15,000 per ekor
  - Legendary Fish: $50,000 per ekor

### 🎣 Area Memancing (Semua Air)
- **Central Lake**: Danau besar di tengah peta
- **Main River**: Sungai utama yang mengalir
- **Forest Pond**: Kolam di hutan
- **Mountain Spring**: Mata air di gunung
- **Garden Pool**: Kolam taman
- **Crystal Lake**: Danau kristal
- **Ocean Shore**: Pantai lautan
- **Northern Sea**: Laut utara
- **Eastern Bay**: Teluk timur
- **Western Gulf**: Teluk barat
- **Waterfalls**: Air terjun
- **Streams**: Sungai kecil
- **DAN SEMUA AREA AIR LAINNYA!** 🎣

---

## 🎮 CARA BERMAIN

### Memulai Memancing
1. **Pergi ke Area Air**: Berjalan ke area air manapun di peta
2. **Klik Air atau Tombol**: Klik langsung pada air atau tombol "Mulai Mancing"
3. **Tunggu Animasi**: Proses memancing 3-8 detik
4. **Lihat Hasil**: Ikan tertangkap atau tidak

**Cara Memancing:**
- **Klik Langsung**: Klik pada area air manapun untuk memancing
- **Tombol UI**: Gunakan tombol "Mulai Mancing" di interface
- **Prompt Otomatis**: Sistem akan menampilkan prompt saat dekat air

### Membeli Peralatan
1. **Pergi ke Toko**: Platform biru dengan tanda toko
2. **Klik Toko**: Buka menu peralatan
3. **Pilih Kategori**: Rods atau Bait
4. **Klik "Buy"**: Beli peralatan yang diinginkan

### Menjual Ikan
1. **Pergi ke Pasar**: Platform hijau dengan tanda pasar
2. **Klik Pasar**: Buka inventory ikan
3. **Pilih Ikan**: Klik ikan yang ingin dijual
4. **Jual**: Klik "Sell All" atau "Sell 1"

### Mengirim Hadiah Uang
1. **Klik Tombol 🎁**: Di display uang (kiri atas)
2. **Masukkan Nama**: Nama pemain yang akan diberi hadiah
3. **Masukkan Jumlah**: Berapa uang yang akan dikirim
4. **Klik "Send Gift"**: Kirim hadiah

---

## 💵 SISTEM EKONOMI

### Uang Awal
- **Starting Money**: $100,000
- **Display**: Selalu terlihat di kiri atas layar
- **Animasi**: Perubahan uang ditampilkan dengan animasi

### Cara Mendapat Uang
1. **Menjual Ikan**: Harga berdasarkan kelangkaan
2. **Menerima Hadiah**: Dari pemain lain
3. **Upgrade Equipment**: Investasi untuk hasil lebih baik

### Cara Menggunakan Uang
1. **Beli Peralatan**: Rods dan bait yang lebih baik
2. **Kirim Hadiah**: Berbagi dengan teman
3. **Investasi**: Equipment yang lebih baik = ikan lebih mahal

### Nilai Uang yang Diperbesar
- **Ikan Common**: $1,000 (sebelumnya $10)
- **Ikan Rare**: $5,000 (sebelumnya $50)
- **Ikan Epic**: $15,000 (sebelumnya $150)
- **Ikan Legendary**: $50,000 (sebelumnya $500)
- **Wooden Rod**: $50,000 (sebelumnya $500)
- **Steel Rod**: $150,000 (sebelumnya $1,500)
- **Golden Rod**: $500,000 (sebelumnya $5,000)

---

## 🏆 SISTEM RANKING

### 7 Level Ranking
1. **Novice Fisher** (Level 1) - 0 XP - Abu-abu
2. **Apprentice Fisher** (Level 2) - 100 XP - Hijau
3. **Skilled Fisher** (Level 3) - 300 XP - Biru
4. **Expert Fisher** (Level 4) - 600 XP - Ungu
5. **Master Fisher** (Level 5) - 1,000 XP - Emas
6. **Legendary Fisher** (Level 6) - 1,500 XP - Orange
7. **Fishing God** (Level 7) - 2,500 XP - Merah

### Cara Naik Level
- **Tangkap Ikan**: Setiap ikan memberi XP
- **Common Fish**: +5 XP
- **Rare Fish**: +15 XP
- **Epic Fish**: +30 XP
- **Legendary Fish**: +60 XP

### Bonus Level Tinggi
- **Akses Ikan Langka**: Level tinggi = ikan mahal
- **Animasi Rank Up**: Notifikasi saat naik level
- **Prestise**: Status sosial di game

---

## 🎨 KUSTOMISASI

### Menambah Ikan Baru
Edit file `MainModule.lua`, bagian `FishData`:

```lua
["Nama Ikan Baru"] = {
    Rarity = "Epic", -- Common, Rare, Epic, Legendary
    BasePrice = 25000, -- Harga dalam dolar
    CatchChance = 0.15, -- Peluang tertangkap (0-1)
    Experience = 40, -- XP yang didapat
    MinLevel = 6 -- Level minimum untuk menangkap
}
```

### Menambah Peralatan Baru
Edit file `MainModule.lua`, bagian `EquipmentData`:

```lua
Rods = {
    ["Diamond Rod"] = {
        Level = 5,
        Price = 1000000, -- $1,000,000
        CatchBonus = 0.5, -- +50% peluang
        Durability = 500
    }
}
```

### Mengubah Harga
Edit nilai `BasePrice` untuk ikan atau `Price` untuk equipment di `MainModule.lua`.

---

## 🔧 TROUBLESHOOTING

### Script Tidak Berfungsi
1. **Pastikan RemoteEvents dibuat**: Jalankan `RemoteEvents.lua` dulu
2. **Cek DataStore**: Aktifkan di Game Settings
3. **Restart Game**: Keluar dan masuk lagi

### Uang Tidak Tersimpan
1. **Cek DataStore**: Pastikan enabled di Game Settings
2. **Cek Internet**: DataStore butuh koneksi internet
3. **Tunggu Auto-Save**: Otomatis setiap 5 menit

### UI Tidak Muncul
1. **Cek StarterPlayerScripts**: Pastikan script ada di sini
2. **Restart Client**: Keluar dan masuk lagi
3. **Cek Error**: Lihat Output window untuk error

### Tidak Bisa Menangkap Ikan
1. **Cek Umpan**: Pastikan ada umpan di inventory
2. **Cek Level**: Beberapa ikan butuh level tinggi
3. **Cek Equipment**: Rod dan bait mempengaruhi peluang

### Gift Tidak Terkirim
1. **Cek Nama**: Pastikan nama pemain benar
2. **Cek Uang**: Pastikan punya cukup uang
3. **Cek Pemain**: Pastikan pemain target online

---

## 📊 TIPS DAN TRIK

### Tips Memancing Efektif
1. **Upgrade Equipment**: Rod dan bait lebih baik = hasil lebih baik
2. **Pilih Spot**: Semua spot sama, pilih yang nyaman
3. **Simpan Uang**: Jangan boros, investasi dulu
4. **Beli Bait**: Selalu siapkan umpan

### Tips Ekonomi
1. **Jual Ikan Langka**: Harga lebih mahal
2. **Investasi Equipment**: ROI yang bagus
3. **Gift Teman**: Bangun relasi
4. **Hemat Uang**: Jangan beli yang tidak perlu

### Tips Ranking
1. **Tangkap Ikan Mahal**: XP lebih banyak
2. **Gunakan Equipment Bagus**: Peluang lebih tinggi
3. **Bermain Rutin**: Konsistensi kunci sukses
4. **Target Level**: Setiap level buka ikan baru

---

## 🎯 KESIMPULAN

Sistem mancing Roblox ini memberikan pengalaman bermain yang lengkap dengan:
- **Ekonomi Realistis**: Uang dengan nilai besar
- **Progression System**: Ranking dan equipment upgrade
- **Social Features**: Gift system antar pemain
- **Visual Feedback**: Animasi dan notifikasi
- **Data Persistence**: Progress tersimpan otomatis

Dengan tutorial ini, Anda siap untuk memulai petualangan memancing di Roblox! Selamat bermain dan semoga berhasil menangkap ikan langka! 🎣💰

---

## 📞 DUKUNGAN

Jika mengalami masalah:
1. Baca bagian Troubleshooting
2. Cek file README.md untuk detail teknis
3. Pastikan semua script terinstall dengan benar
4. Restart game jika perlu

**Selamat Memancing!** 🎣