<?php

namespace Database\Seeders;

use App\Models\Master\Rayon;
use Illuminate\Database\Seeder;

class RayonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rayons = [
            ['name' => 'Rayon A'],
            ['name' => 'Rayon B'],
            ['name' => 'Rayon C'],
            ['name' => 'Rayon D'],
            ['name' => 'Rayon E'],
            ['name' => 'Rayon F'],
        ];

        foreach ($rayons as $rayon) {
            Rayon::create($rayon);
        }

        $this->command->info('✓ Rayons seeded successfully.');
    }
}
