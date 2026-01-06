<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
                // Department and violation structure FIRST
            DepartemenSeeder::class,
            KategoriPelanggaranSeeder::class,
            JenisPelanggaranSeeder::class,
                // Then users and master data
            UserSeeder::class,
            PeriodeMigrationSeeder::class,
            KriteriaSeeder::class,
            SantriSeeder::class,
            DummyDataSeeder::class,
        ]);
    }
}