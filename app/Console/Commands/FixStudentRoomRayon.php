<?php

namespace App\Console\Commands;

use App\Models\Master\Room;
use App\Models\Master\Student;
use Illuminate\Console\Command;

class FixStudentRoomRayon extends Command
{
    protected $signature   = 'students:fix-room-rayon';
    protected $description = 'Perbaiki kamar santri agar sesuai rayonnya (distribusi merata)';

    public function handle(): int
    {
        // Kelompokkan kamar per rayon
        $roomsByRayon = Room::all()->groupBy('rayon_id');

        if ($roomsByRayon->isEmpty()) {
            $this->error('Tidak ada data kamar. Jalankan RoomSeeder terlebih dahulu.');
            return self::FAILURE;
        }

        // Counter round-robin per rayon
        $indexByRayon = [];

        $students = Student::whereNotNull('rayon_id')->get();
        $fixed    = 0;
        $skipped  = 0;

        foreach ($students as $student) {
            $rayonId = $student->rayon_id;
            $rooms   = $roomsByRayon->get($rayonId);

            if (!$rooms || $rooms->isEmpty()) {
                $this->warn("Tidak ada kamar untuk rayon ID {$rayonId} ({$student->name}) — dilewati.");
                $skipped++;
                continue;
            }

            // Ambil kamar secara bergilir (round-robin) agar distribusi merata
            $idx  = $indexByRayon[$rayonId] ?? 0;
            $room = $rooms[$idx % $rooms->count()];
            $indexByRayon[$rayonId] = $idx + 1;

            $student->update([
                'room_id'  => $room->id,
                'rayon_id' => $room->rayon_id,
            ]);
            $fixed++;
        }

        $this->info("Selesai. {$fixed} santri diperbarui, {$skipped} dilewati.");
        return self::SUCCESS;
    }
}
