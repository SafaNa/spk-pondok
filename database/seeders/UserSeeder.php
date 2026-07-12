<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Master\Department;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        User::truncate();
        Schema::enableForeignKeyConstraints();

        $password = Hash::make('password');

        // Admin (type=0)
        User::create([
            'name'     => 'Administrator',
            'username' => 'admin',
            'email'    => 'admin@pondok.test',
            'password' => $password,
            'type'     => 0,
        ]);

        // Department officers (type=1), satu per departemen
        $departments = Department::all();
        foreach ($departments as $dept) {
            $username = strtolower(Str::slug($dept->acronym, '_'));

            // pastikan username unik
            $base = $username;
            $i = 2;
            while (User::where('username', $username)->exists()) {
                $username = $base . '_' . $i++;
            }

            User::create([
                'name'          => 'Petugas ' . $dept->name,
                'username'      => $username,
                'password'      => $password,
                'type'          => 1,
                'department_id' => $dept->id,
            ]);
        }
    }
}
