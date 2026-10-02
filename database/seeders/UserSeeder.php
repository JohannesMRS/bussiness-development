<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /** Password default semua akun dummy: "password" (ganti sebelum production). */
    public function run(): void
    {
        $sellers = [
            ['Rizky Ananda Putra', 'rizky@bizdev.test', '6281234567001', 'Manajemen Informatika',
                'Rizky Digital Studio', 'Jasa desain dan pengembangan digital untuk UMKM dan organisasi kampus.'],
            ['Siti Rahmawati', 'siti@bizdev.test', '6281234567002', 'Administrasi Bisnis',
                'Dapur Sitiara', 'Kue dan camilan rumahan khas Medan, dibuat fresh setiap pesanan.'],
            ['Dewi Lestari Sinaga', 'dewi@bizdev.test', '6281234567003', 'Administrasi Bisnis',
                'Dewi Craft & Wear', 'Fashion dan aksesori handmade dengan sentuhan motif Batak.'],
            ['Ahmad Fauzan Lubis', 'fauzan@bizdev.test', '6281234567004', 'Teknik Komputer',
                'FauzanTech', 'Kit elektronika, perakitan, dan servis perangkat untuk pelajar dan hobiis.'],
            ['Maria Simanjuntak', 'maria@bizdev.test', '6281234567005', 'Administrasi Bisnis',
                'Marsim Kitchen', 'Masakan dan frozen food rumahan, cocok untuk acara dan stok harian.'],
            ['Budi Hartono', 'budi@bizdev.test', '6281234567006', 'Teknik Mesin',
                'BH Garage Works', 'Servis ringan dan custom sablon dengan hasil rapi dan harga mahasiswa.'],
        ];

        $accounts = [
            [User::ROLE_ADMIN, 'Admin BizDev HMPS MI', 'admin@bizdev.test', 'password', null, null, null, null],
        ];

        foreach ($sellers as [$name, $email, $wa, $major, $business, $description]) {
            $accounts[] = [User::ROLE_SELLER, $name, $email, 'password', $wa, $major, $business, $description];
        }

        foreach ($accounts as [$role, $name, $email, $password, $whatsappNumber, $major, $businessName, $businessDescription]) {
            $user = User::firstOrNew(['email' => $email]);

            if ($user->exists) {
                continue;
            }

            $user->fill([
                'name' => $name,
                'email' => $email,
                'password' => $password,
                'whatsapp_number' => $whatsappNumber,
                'major' => $major,
                'bussiness_name' => $businessName,
                'bussiness_description' => $businessDescription,
            ]);
            $user->role = $role;
            $user->is_active = true;
            $user->email_verified_at = now();
            $user->save();
        }
    }
}
