<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::with('rayon')->orderBy('name')->get();
        $rayons = \App\Models\Master\Rayon::orderBy('name')->get();
        return view('rooms.index', compact('rooms', 'rayons'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'rayon_id' => 'required|exists:rayons,id',
            'capacity' => 'required|integer|min:0'
        ]);

        Room::create($validated);
        return redirect()->route('rooms.index')->with('success', 'Kamar berhasil ditambahkan');
    }

    public function update(Request $request, Room $room)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'rayon_id' => 'required|exists:rayons,id',
            'capacity' => 'required|integer|min:0'
        ]);

        $room->update($validated);
        return redirect()->route('rooms.index')->with('success', 'Kamar berhasil diperbarui');
    }

    public function destroy(Room $room)
    {
        $room->delete();
        return redirect()->route('rooms.index')->with('success', 'Kamar berhasil dihapus');
    }
}
