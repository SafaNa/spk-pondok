<?php

namespace Database\Seeders;

use App\Models\Master\Room;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    public function run()
    {
        $rayonA = \App\Models\Master\Rayon::where('name', 'Rayon A')->first();
        $rayonB = \App\Models\Master\Rayon::where('name', 'Rayon B')->first();
        $rayonC = \App\Models\Master\Rayon::where('name', 'Rayon C')->first();
        $rayonD = \App\Models\Master\Rayon::where('name', 'Rayon D')->first();

        $rooms = [
            ['name' => 'Kamar A1', 'capacity' => 10, 'rayon_id' => $rayonA?->id],
            ['name' => 'Kamar A2', 'capacity' => 10, 'rayon_id' => $rayonA?->id],
            ['name' => 'Kamar B1', 'capacity' => 10, 'rayon_id' => $rayonB?->id],
            ['name' => 'Kamar B2', 'capacity' => 10, 'rayon_id' => $rayonC?->id],
        ];

        foreach ($rooms as $room) {
            Room::create($room);
        }

        $this->command->info('✓ Rooms seeded successfully.');
    }
}
