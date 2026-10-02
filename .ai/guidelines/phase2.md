# TAHAP 2: Implementasi Frontend & Desain

Agen: GitHub Copilot. Bahasa respons: Indonesia (istilah teknis tetap Inggris).
**Prasyarat:** Tahap 1 sudah selesai dan disetujui. Backend, route, dan skema dari Tahap 1 menjadi acuan; jangan mengubah logika/skema kecuali bug yang ditemukan, dan laporkan dulu. Penambahan fitur di luar dokumen ini wajib ditanyakan ke saya terlebih dahulu.

## 1. Konteks Singkat
Website katalog (BUKAN e-commerce) HMPS MI Polmed, divisi Business Development, memasarkan produk mahasiswa. **Tidak ada transaksi dan tidak ada login pembeli**; pembeli memesan lewat tombol WhatsApp. Hanya admin dan seller yang login. Penilaian: fungsionalitas 30%, UI/UX 25%, kesesuaian konsep katalog 15%, kualitas kode 10%, presentasi 5%.
Tech stack (tidak boleh ditambah): Laravel Blade + TailwindCSS + JavaScript (Alpine.js bawaan Breeze), PostgreSQL. **Dilarang menginstal paket/library tambahan.**

## 2. Design Tokens
Definisikan sebagai CSS variables + extend `tailwind.config.js` (jangan hardcode hex di Blade):
```
--color-primary: #0d3b66;    /* Brand utama */
--color-secondary: #0055ff;  /* Interaktif & CTA */
--color-accent: #ffb703;     /* Highlight & harga */
--color-bg: #f8f9fa;         /* Latar web */
--color-surface: #ffffff;    /* Kartu & container */
--color-text-main: #1e293b;
--color-text-muted: #64748b;
```
Dark mode: buat set token gelap yang selaras (turunan primary/secondary/accent, kontras teks minimal WCAG AA). Konsisten di seluruh halaman.

## 3. Cakupan Tampilan
| Area | Styling brand | Light/Dark | Terjemahan ID/EN |
|---|---|---|---|
| Halaman publik (landing, katalog, detail, penjual, tentang kami) | Ya | Ya | Ya |
| Login, dashboard admin, dashboard seller (termasuk form CRUD) | Ya, layout fungsional sederhana | Ya | Tidak (tetap Indonesia) |

- **Dark mode:** strategi `class` Tailwind, toggle di navbar publik dan di layout dashboard/login; simpan pilihan di `localStorage`, default mengikuti `prefers-color-scheme`; cegah flash (inline script kecil di `<head>`).
- **Terjemahan:** hanya tampilan publik/katalog, pakai file lang Laravel (`lang/id`, `lang/en`), toggle ID/EN di navbar, simpan di session (route sederhana untuk ganti locale). Data produk dari DB tidak diterjemahkan.

## 4. Halaman yang Dibuat
Gunakan layout Blade terpisah: `layouts/public` (navbar + footer) dan layout dashboard/login (pakai komponen Breeze yang ada, direstyle).
- **Navbar publik:** Beranda, Katalog Produk, Penjual, Tentang Kami, toggle tema, toggle bahasa, dan tautan login kecil untuk seller/admin (tanpa tombol daftar). Mobile: hamburger menu (Alpine).
- **Landing `/`:** hero + CTA ke katalog, bagian "Produk Baru", "Rekomendasi" (`is_featured`), ringkasan kategori, cuplikan cara pesan (lihat produk, klik WhatsApp, transaksi langsung dengan penjual), CTA "Ingin jualan? Hubungi admin" (link WA/sosmed admin dari config).
- **Katalog `/katalog`:** search (nama produk/usaha), filter kategori (Kuliner, Jasa, Fashion, Teknologi), filter prodi (dari `users.major`), pagination, state kosong ("tidak ada hasil"), query string tetap saat berpindah halaman. Filter/search boleh submit GET biasa dengan JS untuk peningkatan UX (debounce).
- **Card produk (wajib):** nama penjual/usaha, nama produk, foto, harga (aksen `--color-accent`, format Rp), deskripsi singkat (line-clamp), badge kategori, tombol **Pesan** (link `wa.me` ke penjual dengan pesan template nama produk, `target="_blank" rel="noopener"`). Klik card ke detail.
- **Detail `/produk/{slug}`:** foto besar, info lengkap (full description), info penjual + link ke profilnya, tombol WA menonjol (sticky di mobile), produk terkait (kategori sama).
- **Direktori `/penjual` & `/penjual/{id}`:** grid penjual (avatar, nama usaha, prodi, jumlah produk), profil penjual dengan deskripsi usaha dan produk approved miliknya, tombol WA.
- **Tentang Kami `/tentang-kami`:** profil divisi Business Development, tempat foto tim (placeholder yang mudah diganti `public/images/about/`), visi/misi singkat, cara kerja (alur seller & komisi 1% secara umum), kontak admin.
- **Login `/login`:** form sederhana ber-brand, tanpa link register/lupa password, ada toggle tema, pesan jelas untuk akun nonaktif.
- **Dashboard admin:** ringkasan (jumlah produk per status, seller), daftar produk dengan filter status, aksi approve/reject (modal alasan), lihat bukti bayar, toggle featured, CRUD produk, manajemen seller (list, buat, edit, aktif/nonaktif, reset password). Tampilkan `fee_amount` dan `views_count`.
- **Dashboard seller:** daftar produk + badge status (pending/approved/rejected + alasan penolakan), form create/edit (pratinjau gambar, upload bukti bayar, tampilkan biaya 1% otomatis dan info rekening statis dari config), edit profil usaha (profile Breeze disesuaikan field `bussiness_*`, WA, major, avatar).
- **Halaman error:** 404 sederhana ber-brand.

## 5. Kualitas UI/UX & Performa
- **Mobile-first & responsive** (kebanyakan pengguna HP): grid 1/2/3-4 kolom, tap target ≥ 44px, tabel dashboard dapat discroll horizontal atau jadi card di mobile.
- **Animasi halus, tidak berlebihan:** hover card, fade/slide-in saat scroll (Intersection Observer atau Alpine), transisi toggle/menu/modal. Hormati `prefers-reduced-motion`.
- **Optimasi gambar (tanpa paket):** `loading="lazy"`, atribut `width/height` (cegah layout shift), `object-cover` dengan aspect ratio tetap, placeholder bila gambar hilang.
- **SEO:** `<title>` unik dan meta description per halaman (via section di layout), Open Graph dasar untuk detail produk, `lang` mengikuti locale, heading hierarki benar, alt text pada gambar.
- **Konsistensi:** komponen Blade reusable (`x-product-card`, `x-seller-card`, `x-badge`, `x-button`, `x-empty-state`), tidak ada duplikasi markup. Hindari CSS inline; gunakan utility Tailwind. Build dengan `npm run build` (purge aktif).
- **Aksesibilitas dasar:** kontras cukup di kedua tema, label form, fokus terlihat, `aria-label` pada tombol ikon.
- **Konsep katalog:** tidak boleh ada elemen yang terkesan transaksi (keranjang, checkout, "beli sekarang", pembayaran). Gunakan "Pesan via WhatsApp".

## 6. Cara Kerja & Output
1. Rencanakan dulu secara ringkas (daftar komponen + urutan kerja), lalu kerjakan bertahap: tokens/layout, komponen, halaman publik, login, dashboard.
2. Pastikan semua fitur wajib berjalan: landing, katalog, search, filter, detail, direktori penjual, panel admin CRUD, link WA valid, responsive.
3. Uji manual: mobile (≈375px) dan desktop, light/dark, ID/EN, alur admin lalu seller lalu pengunjung, link WA (nomor ternormalisasi `62...`).
4. Akhiri dengan ringkasan perubahan, daftar file baru/diubah, dan hal yang perlu saya putuskan. Jangan menambah fitur di luar dokumen ini tanpa konfirmasi.