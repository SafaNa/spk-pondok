<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Master\Department;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class UserSeeder extends Seeder
{
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        User::truncate();
        Schema::enableForeignKeyConstraints();

        $password = Hash::make('password');

        // Admin
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@pondok.test',
            'password' => $password,
            'role' => 'admin',
        ]);

        // Licensing Officer
        User::create([
            'name' => 'Petugas Perizinan',
            'email' => 'perizinan@pondok.test',
            'password' => $password,
            'role' => 'licensing_officer',
        ]);

        // Finance Officer
        User::create([
            'name' => 'Petugas Keuangan',
            'email' => 'keuangan@pondok.test',
            'password' => $password,
            'role' => 'finance_officer',
        ]);

        // Department Officers
        $departments = Department::all();
        foreach ($departments as $dept) {
            // Generate email from department name (e.g. perizinan@pondok.test)
            // Use str_word_count to ignore symbols like '&' (e.g. "Sarana & Prasarana" -> ["Sarana", "Prasarana"])
            $words = str_word_count($dept->name, 1);
            $emailName = strtolower($words[0]);
            $check = User::where('email', $emailName . '@pondok.test')->first();
            if ($check) {
                $emailName = strtolower($words[1]);
            }
            User::create([
                'name' => 'Petugas ' . $dept->name,
                'email' => $emailName . '@pondok.test',
                'password' => $password,
                'role' => 'department_officer',
                'department_id' => $dept->id,
            ]);
        }
    }
}
