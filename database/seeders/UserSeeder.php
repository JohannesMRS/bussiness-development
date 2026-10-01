<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /** Password default semua akun dummy: "password" (ganti sebelum production). */
    public function run(): void
    {
        User::updateOrCreate(['email' => 'admin@bizdev.test'], [
            'name' => 'Admin BizDev HMPS MI',
            'password' => 'password',
            'role' => User::ROLE_ADMIN,
            'email_verified_at' => now(),
        ]);

        $sellers = [
            ['Rizky Ananda Putra', 'rizky@bizdev.test', '6281234567001', 'Manajemen Informatika',
                'Rizky Digital Studio', 'Jasa desain dan pengembangan digital untuk UMKM dan organisasi kampus.'],
            ['Siti Rahmawati', 'siti@bizdev.test', '6281234567002', 'Administrasi Bisnis',
                'Dapur Sitiara', 'Kue dan camilan rumahan khas Medan, dibuat fresh setiap pesanan.'],
            ['Dewi Lestari Sinaga', 'dewi@bizdev.test', '6281234567003', 'Akuntansi',
                'Dewi Craft & Wear', 'Fashion dan aksesori handmade dengan sentuhan motif Batak.'],
            ['Ahmad Fauzan Lubis', 'fauzan@bizdev.test', '6281234567004', 'Teknik Elektronika',
                'FauzanTech', 'Kit elektronika, perakitan, dan servis perangkat untuk pelajar dan hobiis.'],
            ['Maria Simanjuntak', 'maria@bizdev.test', '6281234567005', 'Administrasi Bisnis',
                'Marsim Kitchen', 'Masakan dan frozen food rumahan, cocok untuk acara dan stok harian.'],
            ['Budi Hartono', 'budi@bizdev.test', '6281234567006', 'Teknik Mesin',
                'BH Garage Works', 'Servis ringan dan custom sablon dengan hasil rapi dan harga mahasiswa.'],
        ];

        foreach ($sellers as [$name, $email, $wa, $major, $business, $desc]) {
            User::updateOrCreate(['email' => $email], [
                'name' => $name,
                'password' => 'password',
                'role' => User::ROLE_SELLER,
                'whatsapp_number' => $wa,
                'major' => $major,
                'business_name' => $business,
                'business_description' => $desc,
                'email_verified_at' => now(),
            ]);
        }
    }
}