---

# 📄 PRODUCT REQUIREMENT DOCUMENT (PRD)

---

## 1. Executive Summary & Project Overview

| Parameter | Detail |
| --- | --- |
| **Nama Project** | **BizDev Catalog** (Katalog Digital Produk Mahasiswa Polmed) |
| **Pengembang** | Divisi Business Development (BizDev) HMPS Manajemen Informatika, Politeknik Negeri Medan |
| **Tujuan Utama** | Platform etalase digital untuk mempromosikan dan mendukung produk/jasa karya mahasiswa Politeknik Negeri Medan. |
| **Model Bisnis** | Skema *listing fee* / charge per *upload* produk (nominal ditentukan oleh Divisi BizDev) sebagai sumber pemasukan organisasi. |
| **Aturan Utama (Non-E-Commerce)** | **TIDAK ADA** transaksi langsung di platform, keranjang belanja (*cart*), proses *checkout*, atau *payment gateway*. Transaksi dilakukan secara langsung antara pembeli dan penjual via **WhatsApp**. |

---

## 2. Technical Stack & Configuration

* **Backend Framework:** Laravel (Latest Stable)
* **Templating Engine:** Blade Templating
* **Frontend / UI:** Tailwind CSS + Alpine.js (untuk komponen dinamis)
* **Database:** PostgreSQL
* **Iconography:** Lucide Icons / FontAwesome

### 🎨 Tailwind Design Tokens (`tailwind.config.js`)

```javascript
module.exports = {
  darkMode: 'class',
  theme: {
    extend: {
      colors: {
        primary: '#0d3b66',      // Brand Utama
        secondary: '#0055ff',    // Interactive & CTA
        accent: '#ffb703',       // Highlight & Price Tag
        bg: '#f8f9fa',           // Light Background
        surface: '#ffffff',      // Card & Container Background
        'text-main': '#1e293b',  // Main Text
        'text-muted': '#64748b'  // Muted Text
      }
    }
  }
}

```

---

## 3. Database Schema (PostgreSQL Entity Design)

### 1. Table: `users`

| Column | Type | Constraints / Description |
| --- | --- | --- |
| `id` | BigInt / UUID | Primary Key |
| `name` | VARCHAR(255) | Nama Lengkap User |
| `email` | VARCHAR(255) | Unique |
| `password` | VARCHAR(255) | Hashed Password |
| `role` | ENUM | `'admin'`, `'seller'` |
| `whatsapp_number` | VARCHAR(20) | Format internasional (`628xxx`) |
| `major` | VARCHAR(100) | Program Studi / Jurusan (e.g., Manajemen Informatika) |
| `avatar_url` | VARCHAR(255) | Nullable |
| `created_at` / `updated_at` | Timestamp | Standard Laravel Timestamps |

### 2. Table: `categories`

| Column | Type | Constraints / Description |
| --- | --- | --- |
| `id` | BigInt | Primary Key |
| `name` | VARCHAR(100) | e.g., Kuliner, Jasa, Fashion, Teknologi |
| `slug` | VARCHAR(100) | Unique |
| `created_at` / `updated_at` | Timestamp | Standard Laravel Timestamps |

### 3. Table: `products`

| Column | Type | Constraints / Description |
| --- | --- | --- |
| `id` | BigInt / UUID | Primary Key |
| `user_id` | BigInt | Foreign Key -> `users.id` |
| `category_id` | BigInt | Foreign Key -> `categories.id` |
| `title` | VARCHAR(255) | Nama Produk |
| `slug` | VARCHAR(255) | Unique |
| `short_description` | TEXT | Ringkasan singkat untuk card |
| `full_description` | TEXT | Deskripsi lengkap di halaman detail |
| `price` | BIGINT | Harga Produk (IDR) |
| `image_url` | VARCHAR(255) | URL / Path Gambar Utama |
| `is_approved` | BOOLEAN | Default: `false` (Approval oleh Admin BizDev) |
| `is_featured` | BOOLEAN | Default: `false` (Untuk Rekomendasi/Terlaris) |
| `payment_proof` | VARCHAR(255) | Nullable (Bukti transfer charge *upload*) |
| `views_count` | INTEGER | Default: `0` |
| `created_at` / `updated_at` | Timestamp | Standard Laravel Timestamps |

---

## 4. User Roles & System Workflows

```
┌─────────────────┐       1. Upload Produk & Bukti Charge       ┌─────────────────┐
│     SELLER      │ ──────────────────────────────────────────> │   ADMIN BIZDEV  │
└─────────────────┘                                             └────────┬────────┘
                                                                         │
                                                               2. Verifikasi & Approve
                                                                         │
                                                                         ▼
┌─────────────────┐             3. Lihat Katalog & Order         ┌─────────────────┐
│  PUBLIC / GUEST │ ──────────────────────────────────────────> │  PUBLIC CATALOG │
│    (PEMBELI)    │               via WhatsApp                  └─────────────────┘
└─────────────────┘

```

### A. Pembeli / Guest (Public User)

1. **Landing Page:** Mengakses hero section, banner promosi, filter cepat, dan grid katalog.
2. **Pencarian & Filter:**
* Pencarian kata kunci (*search by product title* / *seller name*).
* Filter berdasarkan kategori (Kuliner, Jasa, Fashion, dll.) atau program studi/jurusan.
* Sorting: *Terbaru*, *Harga Termurah*, *Harga Termahal*, *Terpopuler*.


3. **Detail Produk:** Membuka detail produk, galeri foto, profil usaha penjual, dan deskripsi lengkap.
4. **CTA WhatsApp (Order Direct):**
Mengklik tombol **"Pesan via WhatsApp"** yang langsung memicu URL pesan otomatis:
`[https://wa.me/](https://wa.me/){seller_whatsapp}?text=Halo%20{seller_name},%20saya%20tertarik%20dengan%20produk%20{product_title}%20di%20BizDev%20HMPS%20MI.`
5. **Direktori Penjual:** Mengakses daftar profil usaha mahasiswa dan melihat seluruh produk yang dijual oleh mahasiswa tersebut.

### B. Penjual / Mahasiswa (Seller)

1. Registrasi & Login ke dashboard seller.
2. Form Input Produk (Judul, Kategori, Harga, Foto, Deskripsi, Nomor WA).
3. Upload Bukti Transfer (*listing charge fee*).
4. Monitoring status produk (*Pending*, *Approved*, atau *Rejected*).

### C. Admin Divisi BizDev

1. Login ke Admin Panel.
2. Meninjau daftar pengajuan produk baru dan memverifikasi pratinjau (*preview*) bukti transfer charge.
3. Mengubah status approval produk (`Approve` / `Reject`).
4. Mengelola status produk rekomendasi/unggulan (`is_featured`).
5. CRUD penuh untuk Kategori dan Manajemen User/Seller.

---

## 5. Navigation & Layout Structure

### Navbar Structure (Responsive Desktop & Mobile)

* **Brand:** Logo HMPS MI x BizDev
* **Nav Links:**
* Home / Katalog
* Direktori Usaha Mahasiswa
* Tentang Kami (Profil Divisi BizDev + Foto Tim)


* **Utilities:**
* Quick Search Toggle Bar
* Switcher Bahasa (`ID` / `EN` via Alpine.js / Session)
* Dark / Light Mode Toggle
* Button `Masuk / Tambah Produk` (Portal Seller & Admin)



---

## 6. Functional Requirements Matrix

| Modul | Fitur | Deskripsi Teknis |
| --- | --- | --- |
| **Katalog & Landing** | Hero Section | Banner headline + tombol CTA mengarah langsung ke Katalog |
|  | Product Card Grid | Menampilkan Foto, Nama Produk, Nama Usaha, Badges Harga & Kategori, serta Tombol WA |
|  | Filter & Search | AJAX / Live-search menggunakan Alpine.js tanpa reload halaman |
|  | Sorting Options | Terbaru, Harga (Low-High / High-Low), Terpopuler (*views*) |
| **Halaman Detail** | Image Viewer | Preview gambar produk resolusi tinggi dengan efek zoom/modal |
|  | Dynamic WA CTA | Tombol pemesanan dinamis berbasis nomor WhatsApp penjual dengan format pesan otomatis |
|  | Related Products | Rekomendasi 4 produk serupa dalam kategori yang sama |
| **Admin & Seller Panel** | Dashboard | Ringkasan statistik (Total Produk, Pending Approval, Total Seller) |
|  | Verifikasi Charge | Preview modal bukti transfer sebelum konfirmasi approval |
| **UX & Value-Add** | Dark Mode Toggle | Pengaturan tema menggunakan Class-based Tailwind (`dark:bg-slate-900`) |
|  | Multi-Language | Beralih tampilan Bahasa Indonesia / Bahasa Inggris untuk UI Katalog |
|  | SEO & Performance | Meta tag OpenGraph dinamis per produk & kompresi gambar otomatis |

---

## 7. Hackathon Evaluation Strategy (Bobot Penilaian)

1. **Fungsionalitas (30%)**
* Pre-filled message URL WhatsApp berfungsi presisi.
* Alur Upload -> Bukti Bayar -> Approval Admin -> Tampil Publik berjalan lancar tanpa *bug*.


2. **UI/UX (25%)**
* *Micro-interactions* & animasi halus via Alpine.js / Tailwind Transitions.
* Tampilan *mobile-first* yang sangat responsif.
* Penerapan *Skeleton Loading* saat memuat filter atau pencarian data.


3. **Kesesuaian Konsep (15%)**
* Murni platform katalog/etalase informasi (tidak ada fitur e-commerce transaksi langsung).


4. **Kualitas Kode (10%)**
* Kebersihan arsitektur MVC Laravel.
* Penggunaan Form Request Validation, Eloquent Scopes, dan Blade Components modular.


5. **Presentasi & Demo (5%)**
* Menyiapkan data *dummy* produk mahasiswa yang variatif dan realistis (Kuliner, Jasa, Fashion, Tech) lengkap dengan aset foto berkualitas tinggi.

## 8. Fitur tambahan (opsional, tapi kalau ada lebih oke)

* Filter untuk teman kita yang berkebutuhan khusus