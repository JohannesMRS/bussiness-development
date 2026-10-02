# TAHAP 1: Analisis Bug, Loophole & Kelengkapan Fitur (Backend)

Agen: GitHub Copilot. Bahasa respons: Indonesia (istilah teknis tetap Inggris).
**Dilarang** mengerjakan desain/styling di tahap ini. Blade yang dibuat hanya fungsional (HTML polos, tanpa styling khusus). Tahap 2 baru dikerjakan setelah Tahap 1 selesai dan saya setujui.

## 1. Konteks Proyek
Website katalog (BUKAN e-commerce) untuk divisi Business Development HMPS Manajemen Informatika Politeknik Negeri Medan, memasarkan produk/jasa mahasiswa. Divisi mendapat komisi dari tiap produk yang diupload.
- **Aturan utama:** tidak ada transaksi di website. Tidak ada login/registrasi pembeli. Hanya `admin` dan `seller` yang login. Pembeli memesan via link WhatsApp penjual.
- Alur: seller minta akun ke admin via WA/sosmed, lalu admin membuat akun. Seller login lewat `/login`, upload produk + bukti transfer, admin approve/reject.
- Tech stack (tidak boleh ditambah): Laravel + Blade + TailwindCSS + JavaScript (Alpine.js bawaan Breeze) + PostgreSQL. **Dilarang menginstal paket tambahan** (mis. Intervention Image).
- Warna (Tahap 2): primary `#0d3b66`, secondary `#0055ff`, accent `#ffb703`, bg `#f8f9fa`, surface `#ffffff`, text-main `#1e293b`, text-muted `#64748b`.

## 2. Kondisi Saat Ini (sudah ada, jangan dibuat ulang)
- Login Breeze, model, controller CRUD produk admin & seller, seeder (admin, 6 seller, 20 produk, categories: Kuliner, Jasa, Fashion, Teknologi). **Periksa seeder yang ada, jangan menimpa/menduplikasi.**
- Route admin (`/admin/index`, `/admin/product` index/store/show/edit/update) dan seller (`/seller/...` setara), keduanya `auth, verified, role:{admin|seller}`. Route utama `/` masih di `welcome.blade.php`.
- View: `admin/{index, product/*, Seller/*}`, `seller/{index, product/*}` plus view bawaan Breeze.

## 3. Skema Data (final)
**users**: id, name, email, password, role (enum `admin`,`seller`), whatsapp_number, major, bussiness_name, bussiness_description, avatar_url, **is_active (BARU, boolean, default true)**, timestamps.
**categories**: id, name, slug, timestamps.
**products**: id, user_id, category_id, title, slug, short_description, full_description, price, image_url, status (enum `pending`,`approved`,`rejected`; default `pending`), rejection_reason, is_featured, payment_proof, views_count, **fee_amount (BARU)**, timestamps.
Relasi: User hasMany Product, Product belongsTo User & Category.
Aturan skema:
- Ejaan `bussiness_*` dipertahankan. **Dilarang mengganti nama kolom yang ada.** Penambahan kolom hanya lewat migration baru (`is_active`, `fee_amount`).
- Perbaiki enum `status` (sebelumnya salah ketik `role`) dan pastikan kolom `role` di users ada dengan enum di atas (buat migration perbaikan bila belum sesuai).
- `major` = pilihan tetap: Manajemen Informatika, Teknik Komputer, Teknik Mesin, Administrasi Bisnis (simpan sebagai konstanta/enum PHP agar mudah ditambah).

## 4. Aturan Bisnis (sudah disetujui)
1. **Biaya (charge):** per produk, sekali bayar, `fee_amount = 1% × price`, dihitung otomatis di server (bukan input user, tidak boleh mass-assignable). Pembayaran transfer di luar sistem; seller hanya upload bukti ke `payment_proof`. Info rekening ditampilkan statis di form seller (nilai dari `config`/`.env`, bukan hardcode). Tidak ada masa aktif produk.
2. **Status:** produk baru dari seller wajib `payment_proof` dan berstatus `pending`. Admin dapat approve atau reject (reject wajib `rejection_reason`).
3. **Edit oleh seller:** produk `approved` yang data intinya diubah (title, price, category, deskripsi, gambar) kembali ke `pending`; produk `rejected` yang diedit otomatis `pending` dan `rejection_reason` dikosongkan. Hitung ulang `fee_amount` bila price berubah.
4. **Admin membuat produk:** pilih seller lewat dropdown, langsung `approved`, tanpa `payment_proof`, `fee_amount` = 0.
5. **Seller `is_active=false`:** tidak bisa login dan produknya tidak tampil publik.
6. **Fitur terlaris DI-DROP.** Yang ada: "Produk Baru" (approved terbaru) dan "Rekomendasi" (`is_featured`, hanya admin yang mengatur). `views_count` tetap dihitung sekali per sesi per produk (session), hanya dipakai sebagai info di dashboard, tidak ditampilkan publik.
7. **Nomor WA:** normalisasi saat simpan ke format `62xxxxxxxxxx` (dari `08..`, `+62..`, `62..`, buang spasi/strip). Link: `https://wa.me/{nomor}?text={urlencode pesan template berisi nama produk}`.
8. **Direktori penjual:** hanya seller `is_active` dengan minimal 1 produk `approved`.
9. **Filter prodi:** berdasarkan `users.major` penjual produk.

## 5. Fitur Wajib yang Harus Ditambahkan (sudah disetujui, implementasikan)
- **Manajemen akun seller oleh admin:** list, create, edit, nonaktifkan/aktifkan (`is_active`), reset password. Akun baru: `role=seller`, `email_verified_at` otomatis terisi. Gunakan view `admin/seller/` (huruf kecil, ganti folder `admin/Seller/` karena case-sensitive di Linux).
- **Route admin tambahan:** product create (GET), destroy (DELETE), approve, reject (+`rejection_reason`), toggle featured.
- **Route seller tambahan:** product create (GET), destroy (DELETE, hanya milik sendiri).
- **Route publik:** `/` (landing: hero, produk baru, rekomendasi, cuplikan katalog), `/katalog` (daftar penuh + search nama produk/usaha + filter kategori & prodi + pagination), `/produk/{slug}`, `/penjual`, `/penjual/{id}`, `/tentang-kami`.
- **Auth:** matikan/hapus route registrasi publik dan lupa password Breeze (reset dilakukan admin). Pastikan seller tidak terkunci oleh middleware `verified`. Redirect setelah login sesuai role (admin ke `admin.index`, seller ke `seller.index`).
- **SEO dasar:** layout publik mendukung `<title>` dan meta description per halaman via section/slot Blade.
- **Optimasi gambar tanpa paket:** validasi upload (jenis jpg/png/webp, maksimal ukuran, mis. 2MB), siapkan atribut `width/height` dan `loading="lazy"` untuk dipakai di Tahap 2.

## 6. Yang Harus Diperiksa (potensi bug/loophole)
Periksa seluruh kode lalu laporkan temuan. Minimal cek:
- **Otorisasi/IDOR:** seller bisa melihat/edit/hapus produk seller lain via `{id}`; admin vs seller route terpisah dengan benar; alias middleware `role` terdaftar dan menangani user belum login/role salah.
- **Mass assignment:** seller tidak boleh mengisi `status`, `is_featured`, `fee_amount`, `views_count`, `user_id` (ambil dari `auth()`), `role`.
- **Validasi:** FormRequest untuk store/update (price numerik > 0, category_id exists, gambar & bukti bayar wajib sesuai aturan, WA valid, major termasuk pilihan tetap).
- **File upload:** `payment_proof` disimpan di disk **private** (hanya admin dan pemilik yang bisa melihat, lewat route terproteksi); gambar produk di disk public (`storage:link`); hapus file lama saat diganti/dihapus; nama file di-hash.
- **Slug:** otomatis dari title, unik, aman saat title berubah/duplikat; route publik memakai slug.
- **Query:** N+1 (eager load `user`, `category`), query publik hanya `approved` + seller aktif; pencarian aman dari injection, memakai `ILIKE` untuk PostgreSQL.
- **Konsistensi:** typo/nama route, redirect, flash message, soft-fail saat data tidak ditemukan (404), seeder konsisten dengan skema baru (admin, 6 seller, 20 produk dengan gambar placeholder lokal SVG, campuran status).
- Hal lain yang menurut Anda kurang/berisiko.

## 7. Cara Kerja & Output
1. Baca seluruh struktur proyek, **jangan mengubah apa pun dulu**.
2. Buat laporan temuan (bahasa Indonesia, ringkas), dikelompokkan: **Bug**, **Loophole keamanan**, **Fitur kurang**, **Pertanyaan/ambiguitas**, masing-masing dengan file/lokasi, dampak, dan usulan perbaikan, diberi prioritas (Tinggi/Sedang/Rendah).
3. Poin bagian 5 sudah disetujui, boleh dikerjakan setelah laporan dikirim. **Temuan lain, dan setiap penambahan fitur di luar dokumen ini, wajib menunggu persetujuan saya per item.**
4. Pertanyaan terbuka yang perlu Anda ajukan ke saya (jangan berasumsi): (a) bila price produk `approved` berubah, apakah seller wajib upload bukti bayar baru? (b) apakah seller boleh menghapus produk `approved`?
5. Setelah selesai, jalankan migrate + seed, uji manual alur utama, lalu ringkas perubahan. **Berhenti, jangan lanjut ke Tahap 2.**