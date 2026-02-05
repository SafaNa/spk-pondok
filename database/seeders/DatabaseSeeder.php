<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            DepartmentSeeder::class,
            UserSeeder::class,
            \Laravolt\Indonesia\Seeds\DatabaseSeeder::class,
            AcademicYearSeeder::class,
            EducationLevelSeeder::class,
            RayonSeeder::class,
            RoomSeeder::class,
            MemorizationTypeSeeder::class,
            SantriSeeder::class,
        ]);
    }
}