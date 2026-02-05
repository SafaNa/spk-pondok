<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Master\Department;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
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

        // Department Officers
        $departments = Department::all();
        foreach ($departments as $dept) {
            // Generate email from department name (e.g. keamanan@pondok.test)
            $emailName = strtolower(str_replace(' ', '', $dept->name));
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
