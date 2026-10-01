<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Gambar memakai path placeholder: public/images/products/{slug}.jpg
     * dan galeri {slug}-2.jpg, {slug}-3.jpg. Ganti dengan foto asli nanti.
     */
    public function run(): void
    {
        $categories = Category::pluck('id', 'slug');
        $sellers = User::sellers()->orderBy('id')->pluck('id')->values();

        // [seller index, kategori, judul, ringkasan, deskripsi, harga, status, featured, views, alasan reject]
        $rows = [
            // ---- Kuliner ----
            [1, 'kuliner', 'Brownies Kukus Coklat Sitiara', 'Brownies kukus lembut dengan coklat premium.',
                'Brownies kukus dibuat dari coklat premium tanpa pengawet. Tersedia ukuran loyang 20 cm dan bisa pre-order untuk acara. Tahan 3 hari di suhu ruang.',
                65000, 'approved', true, 412, null],
            [1, 'kuliner', 'Bika Ambon Mini Box Isi 12', 'Bika Ambon mini khas Medan, aroma pandan harum.',
                'Bika Ambon mini isi 12 potong dalam box, tekstur berongga dan manis pas. Cocok untuk oleh-oleh dan hampers.',
                48000, 'approved', false, 230, null],
            [4, 'kuliner', 'Soto Medan Frozen 2 Porsi', 'Soto Medan siap panaskan, bumbu kuah pekat khas.',
                'Paket soto Medan frozen isi 2 porsi: kuah, ayam suwir, dan pelengkap. Tahan 2 minggu di freezer, siap saji dalam 5 menit.',
                55000, 'approved', true, 358, null],
            [4, 'kuliner', 'Nasi Kotak Ayam Penyet', 'Nasi kotak ayam penyet untuk rapat dan acara organisasi.',
                'Minimal pemesanan 10 kotak. Isi: nasi, ayam penyet, lalapan, sambal, dan es teh. Bisa antar area kampus Polmed.',
                22000, 'approved', false, 187, null],
            [1, 'kuliner', 'Keripik Pisang Coklat Kiloan', 'Keripik pisang renyah dengan lapisan coklat.',
                'Keripik pisang dari pisang kepok pilihan, digoreng kering lalu dilapisi coklat. Kemasan 250 gram.',
                30000, 'approved', false, 143, null],
            [4, 'kuliner', 'Sambal Teri Medan Botol 200 gr', 'Sambal teri pedas gurih, tahan lama tanpa pengawet.',
                'Sambal teri dengan cabai rawit dan teri Medan asli. Tahan 1 bulan, cocok untuk anak kos dan oleh-oleh.',
                35000, 'pending', false, 0, null],
            [1, 'kuliner', 'Es Kopi Susu Gula Aren', 'Kopi susu gula aren dalam botol 250 ml.',
                'Kopi susu gula aren dengan biji kopi Sidikalang. Dikemas botol 250 ml, paling nikmat disajikan dingin.',
                18000, 'rejected', false, 0, 'Bukti transfer tidak terbaca. Mohon unggah ulang bukti pembayaran yang jelas.'],

            // ---- Jasa ----
            [0, 'jasa', 'Desain Logo & Branding UMKM', 'Paket logo dan identitas visual untuk usaha Anda.',
                'Paket meliputi 3 konsep logo, 2 kali revisi, dan file master (AI, PNG, PDF). Pengerjaan 3-5 hari kerja.',
                150000, 'approved', true, 502, null],
            [0, 'jasa', 'Website Company Profile', 'Website profil usaha responsif, siap online.',
                'Pembuatan website company profile 5 halaman dengan Laravel dan Tailwind CSS, termasuk setup domain dan hosting tahun pertama.',
                750000, 'approved', true, 389, null],
            [0, 'jasa', 'Desain Poster & Feed Instagram', 'Desain konten promosi untuk event atau usaha.',
                'Paket 5 desain poster atau feed Instagram dengan konsep konsisten. Revisi 2 kali, file siap posting.',
                85000, 'approved', false, 276, null],
            [3, 'jasa', 'Servis Laptop & Install Ulang', 'Install ulang Windows, bersih-bersih, dan upgrade ringan.',
                'Layanan servis laptop: install ulang OS, pembersihan debu dan thermal paste, serta upgrade RAM/SSD. Garansi pengerjaan 7 hari.',
                100000, 'approved', false, 164, null],
            [2, 'jasa', 'Les Privat Akuntansi Dasar', 'Belajar akuntansi dasar dan Excel bersama mahasiswa Akuntansi.',
                'Les privat online atau tatap muka untuk pelajar dan mahasiswa baru: jurnal, buku besar, laporan keuangan sederhana. Per sesi 90 menit.',
                60000, 'pending', false, 0, null],

            // ---- Fashion ----
            [2, 'fashion', 'Tote Bag Ulos Modern', 'Tote bag kanvas dengan aksen kain ulos.',
                'Tote bag kanvas tebal dengan panel ulos modern, muat laptop 14 inci. Handmade dan tersedia 3 pilihan warna.',
                95000, 'approved', true, 321, null],
            [2, 'fashion', 'Gelang Manik Handmade', 'Gelang manik warna-warni, bisa custom nama.',
                'Gelang manik handmade dengan opsi nama atau inisial. Cocok untuk kado dan souvenir. Pengerjaan 2 hari.',
                25000, 'approved', false, 98, null],
            [5, 'fashion', 'Kaos Sablon Custom Satuan', 'Sablon kaos custom untuk komunitas dan acara.',
                'Kaos cotton combed 30s dengan sablon DTF, bisa pesan satuan. Pilihan ukuran S sampai XXL.',
                85000, 'approved', false, 210, null],
            [2, 'fashion', 'Hijab Voal Printing Motif Batak', 'Hijab voal ringan dengan motif khas Batak.',
                'Hijab voal ultrafine dengan printing motif Batak, jatuh dan ringan dipakai. Ukuran 110x110 cm.',
                70000, 'pending', false, 0, null],

            // ---- Teknologi ----
            [3, 'teknologi', 'Kit Belajar Arduino Pemula', 'Paket komponen Arduino lengkap dengan modul belajar.',
                'Isi paket: Arduino Uno, breadboard, sensor, LED, kabel jumper, dan modul PDF 10 proyek. Cocok untuk praktikum.',
                185000, 'approved', true, 447, null],
            [3, 'teknologi', 'Smart Plant Watering Kit', 'Penyiram tanaman otomatis berbasis sensor kelembapan.',
                'Kit penyiram otomatis dengan sensor kelembapan tanah, pompa mini, dan Arduino Nano. Termasuk panduan rakit.',
                120000, 'approved', false, 205, null],
            [0, 'teknologi', 'Template Excel Laporan Keuangan UMKM', 'Template Excel otomatis untuk pembukuan usaha kecil.',
                'Template berisi jurnal harian, buku besar, laba rugi, dan neraca otomatis. Produk digital, dikirim via WhatsApp atau email.',
                45000, 'approved', false, 176, null],
            [0, 'teknologi', 'Aplikasi Kasir Sederhana Web', 'Aplikasi kasir web ringan untuk warung dan kedai.',
                'Aplikasi kasir berbasis web: stok barang, transaksi harian, dan laporan penjualan. Termasuk instalasi dan pelatihan singkat.',
                500000, 'rejected', false, 0, 'Deskripsi kurang jelas dan belum ada contoh tampilan aplikasi. Mohon lengkapi lalu ajukan kembali.'],
        ];

        foreach ($rows as $i => [$sellerIdx, $catSlug, $title, $short, $full, $price, $status, $featured, $views, $reason]) {
            $slug = Str::slug($title);

            $product = Product::updateOrCreate(['slug' => $slug], [
                'user_id' => $sellers[$sellerIdx],
                'category_id' => $categories[$catSlug],
                'title' => $title,
                'short_description' => $short,
                'full_description' => $full,
                'price' => $price,
                'image_url' => "images/products/{$slug}.jpg",
                'status' => $status,
                'rejection_reason' => $reason,
                'is_featured' => $featured,
                'payment_proof' => sprintf('payment-proofs/bukti-%02d.jpg', $i + 1),
                'views_count' => $views,
            ]);

            $product->images()->delete();
            foreach ([2, 3] as $n => $suffix) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => "images/products/{$slug}-{$suffix}.jpg",
                    'sort_order' => $n + 1,
                ]);
            }
        }
    }
}