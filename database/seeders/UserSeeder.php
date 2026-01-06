<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Departemen;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $password = Hash::make('password'); // Password sama untuk semua user

        // 1. Admin User
        User::firstOrCreate(
            ['email' => 'admin@pondok.test'],
            [
                'name' => 'Administrator',
                'password' => $password,
                'role' => 'admin',
                'departemen_id' => null,
            ]
        );

        // 2. Pengurus Perizinan
        User::firstOrCreate(
            ['email' => 'perizinan@pondok.test'],
            [
                'name' => 'Pengurus Perizinan',
                'password' => $password,
                'role' => 'pengurus_perizinan',
                'departemen_id' => null,
            ]
        );

        // 3. Pengurus Departemen - satu untuk setiap departemen
        $departemen = Departemen::all();

        foreach ($departemen as $dept) {
            User::firstOrCreate(
                ['email' => strtolower($dept->singkatan) . '@pondok.test'],
                [
                    'name' => 'Pengurus ' . $dept->singkatan,
                    'password' => $password,
                    'role' => 'pengurus_departemen',
                    'departemen_id' => $dept->id,
                ]
            );
        }
    }
}
