<laravel-boost-guidelines>
=== .ai/phase1 rules ===

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

=== .ai/phase2 rules ===

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

=== foundation rules ===

# Laravel Boost Guidelines

## Foundational Context

This application is a Laravel application running on PHP 8.5. Always use the APIs that match the installed major version of each package — do not assume a version.

Before relying on a package's API, confirm its installed version:
- PHP packages: run `composer show --direct` to list direct dependencies with versions, or `composer show <vendor/package>` for a single package.
- JS packages: check `package.json` for the installed versions.

## Skills Activation

This project has domain-specific skills available in `**/skills/**`. You MUST activate the relevant skill whenever you work in that domain—don't wait until you're stuck.

## Conventions

- You must follow all existing code conventions used in this application. When creating or editing a file, check sibling files for the correct structure, approach, and naming.
- Use descriptive names for variables and methods. For example, `isRegisteredForDiscounts`, not `discount()`.
- Check for existing components to reuse before writing a new one.

## Verification Scripts

- Do not create verification scripts or tinker when tests cover that functionality and prove they work. Unit and feature tests are more important.

## Application Structure & Architecture

- Stick to existing directory structure; don't create new base folders without approval.
- Do not change the application's dependencies without approval.

## Frontend Bundling

- If a frontend change doesn't show in the UI or you get a "Unable to locate file in Vite manifest" error, run `npm run build` or ask the user to run `npm run dev` or `composer run dev`.

## Documentation Files

- You must only create documentation files if explicitly requested by the user.

=== boost rules ===

# Laravel Boost

## Tools

- Laravel Boost is an MCP server with tools designed specifically for this application. Prefer Boost tools over manual alternatives like shell commands or file reads.
- Use `database-query` to run read-only queries against the database instead of writing raw SQL in tinker.
- Use `database-schema` to inspect table structure before writing migrations or models.
- Use `get-absolute-url` to resolve the correct scheme, domain, and port for project URLs. Always use this before sharing a URL with the user.
- Use `browser-logs` to read browser logs, errors, and exceptions. Only recent logs are useful, ignore old entries.

## Searching Documentation (IMPORTANT)

- Use `search-docs` before changes that depend on Laravel ecosystem APIs, behavior, configuration, or version-specific syntax. Skip it for copy-only edits and other changes where package documentation is irrelevant. Reuse sufficient results already in context instead of searching again.
- Pass a `packages` array to scope results when you know which packages are relevant.
- Use multiple broad, topic-based queries: `['rate limiting', 'routing rate limiting', 'routing']`. Expect the most relevant results first.
- Do not add package names to queries because package info is already shared. Use `test resource table`, not `filament 4 test resource table`.

### Search Syntax

1. Use words for auto-stemmed AND logic: `rate limit` matches both "rate" AND "limit".
2. Use `"quoted phrases"` for exact position matching: `"infinite scroll"` requires adjacent words in order.
3. Combine words and phrases for mixed queries: `middleware "rate limit"`.
4. Use multiple queries for OR logic: `queries=["authentication", "middleware"]`.

## Project Rules

- This project contains committed, area-grouped rules in `.ai/rules` when that directory exists, including path-scoped framework guidelines under `.ai/rules/boost`. Before you enter plan mode or create/edit any file, you MUST first: open @.ai/rules/index.md (it maps file globs to rule files), read every rule file whose globs cover the path(s) in scope, and run `grep -rin 'keyword' .ai/rules` to catch what a path match alone misses. Do not write code until you have read and are following every matching rule. If `.ai/rules` does not exist, continue without it.

## Artisan

- Run Artisan commands directly via the command line (e.g., `php artisan route:list`). Use `php artisan list` to discover available commands and `php artisan [command] --help` to check parameters.
- Inspect routes with `php artisan route:list`. Filter with: `--method=GET`, `--name=users`, `--path=api`, `--except-vendor`, `--only-vendor`.
- Read configuration values using dot notation: `php artisan config:show app.name`, `php artisan config:show database.default`. Or read config files directly from the `config/` directory.

## Tinker

- Execute PHP in app context for debugging and testing code. Do not create models without user approval, prefer tests with factories instead. Prefer existing Artisan commands over custom tinker code.
- Always use single quotes to prevent shell expansion: `php artisan tinker --execute 'Your::code();'`
  - Double quotes for PHP strings inside: `php artisan tinker --execute 'User::where("active", true)->count();'`

=== php rules ===

# PHP

- Always use curly braces for control structures, even for single-line bodies.
- Use PHP 8 constructor property promotion: `public function __construct(public GitHub $github) { }`. Do not leave empty zero-parameter `__construct()` methods unless the constructor is private.
- Use explicit return type declarations and type hints for all method parameters: `function isAccessible(User $user, ?string $path = null): bool`
- Use TitleCase for Enum keys: `FavoritePerson`, `BestLake`, `Monthly`.
- Prefer PHPDoc blocks over inline comments. Only add inline comments for exceptionally complex logic.
- Use array shape type definitions in PHPDoc blocks.

=== deployments rules ===

# Deployment

- Laravel can be deployed using [Laravel Cloud](https://cloud.laravel.com/), which is the fastest way to deploy and scale production Laravel applications.

=== tests rules ===

# Test Enforcement

- Add or update tests for behavior and logic changes when a test provides meaningful regression coverage.
- Pure copy, styling, and layout-only changes do not require new or updated tests.
- When test coverage applies, run the affected tests and ensure they pass.
- Test the changed behavior and its important failure modes, but do not add tests beyond them.
- Read the `testing-best-practices` skill before writing tests.

=== laravel/core rules ===

# Do Things the Laravel Way

- Use `php artisan make:` commands to create new files (i.e. migrations, controllers, models, etc.). You can list available Artisan commands using `php artisan list` and check their parameters with `php artisan [command] --help`.
- If you're creating a generic PHP class, use `php artisan make:class`.
- Pass `--no-interaction` to all Artisan commands to ensure they work without user input. You should also pass the correct `--options` to ensure correct behavior.

### Model Creation

- When creating new models, create useful factories and seeders for them too. Ask the user if they need any other things, using `php artisan make:model --help` to check the available options.

## APIs & Eloquent Resources

- For APIs, default to using Eloquent API Resources and API versioning unless existing API routes do not, then you should follow existing application convention.

## URL Generation

- When generating links to other pages, prefer named routes and the `route()` function.

## Testing

- When creating models for tests, use the factories for the models. Check if the factory has custom states that can be used before manually setting up the model.
- Faker: Use methods such as `$this->faker->word()` or `fake()->randomDigit()`. Follow existing conventions whether to use `$this->faker` or `fake()`.
- When creating tests, make use of `php artisan make:test [options] {name}` to create a feature test, and pass `--unit` to create a unit test. Most tests should be feature tests.

=== pint/core rules ===

# Laravel Pint Code Formatter

- If you have modified any PHP files, you must run `vendor/bin/pint --dirty --format agent` before finalizing changes to ensure your code matches the project's expected style.
- Do not run `vendor/bin/pint --test --format agent`, simply run `vendor/bin/pint --format agent` to fix any formatting issues.

=== phpunit/core rules ===

# PHPUnit

- This project uses PHPUnit. Create tests with `php artisan make:test --phpunit {name}`.
- Do not include the test suite directory in `{name}`. Use `SomeFeatureTest`, not `Feature/SomeFeatureTest`.
- Read the `testing-best-practices` skill for guidance on coverage, naming, structure, dependency isolation, and review.

## Running Tests

- Run the narrowest set of tests that covers the change. Pass a file path or `--filter=testName` to `php artisan test --compact`.
- Rerun a test after each change to it.
- Run `vendor/bin/phpunit` to call the test runner directly. It accepts the same file path and `--filter=testName` arguments.

</laravel-boost-guidelines>
