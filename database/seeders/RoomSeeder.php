<?php

namespace Database\Seeders;

use App\Models\Master\Room;
use App\Models\Master\Rayon;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        
        Room::query()->delete();

        $rayons = Rayon::orderBy('name')->get();

        foreach ($rayons as $index => $rayon) {
            $letter = chr(65 + $index); // A, B, C, D, ...

            for ($i = 1; $i <= 5; $i++) {
                Room::firstOrCreate(
                    ['name' => "Kamar {$letter}{$i}", 'rayon_id' => $rayon->id],
                    ['capacity' => 10]
                );
            }
        }

        $this->command->info("✓ Rooms seeded: {$rayons->count()} rayon × 5 kamar = " . ($rayons->count() * 5) . " kamar.");
    }
}
