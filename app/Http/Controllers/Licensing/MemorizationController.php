<?php

namespace App\Http\Controllers\Licensing;

use App\Http\Controllers\Controller;
use App\Models\Licensing\StudentLicense;
use Illuminate\Http\Request;

class MemorizationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get licenses that are active or upcoming
        // We might want to see history too, but for now focusing on active/upcoming
        $licenses = StudentLicense::with('student')
            ->where('end_date', '>=', now()->subDays(1)) // Show until 1 day after end date
            ->orderBy('start_date', 'desc')
            ->get();

        return view('licensing.memorization.index', compact('licenses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StudentLicense $license)
    {
        $request->validate([
            'memorization_check' => 'required|boolean',
        ]);

        $license->update([
            'memorization_check' => $request->memorization_check,
        ]);

        return response()->json(['message' => 'Status hafalan berhasil diperbarui.']);
    }
}
