<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Setting::updateOrCreate(
            ['id' => 1],
            [
                'app_name' => 'SPK Pondok',
                'pesantren_name' => 'Annuqayah Latee II',
                'description' => 'Sistem informasi terintegrasi untuk mengelola validasi perizinan kepulangan santri, pembayaran SPP, dan pencatatan pelanggaran dalam satu platform yang terpadu dan efisien.',
                'address' => 'Jl. Pesantren No.1, Guluk-Guluk, Sumenep',
                'phone' => '081234567890',
                'email' => 'info@annuqayah.com',
            ]
        );
    }
}
