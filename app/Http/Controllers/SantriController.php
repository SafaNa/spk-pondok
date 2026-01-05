<?php

namespace App\Http\Controllers;

use App\Models\Santri;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SantriImport;
use App\Exports\SantriTemplateExport;

class SantriController extends Controller
{
    public function downloadTemplate()
    {
        return Excel::download(new SantriTemplateExport, 'template_santri.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new SantriImport, $request->file('file'));
            return redirect()->route('santri.index')->with('success', 'Data Santri berhasil diimport!');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Import Error: ' . $e->getMessage());
            \Illuminate\Support\Facades\Log::error($e->getTraceAsString());
            return redirect()->route('santri.index')->with('error', 'Gagal mengimport data: ' . $e->getMessage());
        }
    }

    public function index()
    {
        $santri = Santri::latest()->paginate(10);
        return view('santri.index', compact('santri'));
    }

    public function indexV2()
    {
        $santri = Santri::latest()->paginate(10);
        return view('santri-v2', compact('santri'));
    }

    public function create()
    {
        return view('santri.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nis' => 'required|unique:santri,nis|max:20',
            'nama' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:50',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string|max:255',
            'nama_ortu' => 'required|string|max:100',
            'no_hp_ortu' => 'required|string|max:20',
            'status' => 'required|in:aktif,non-aktif,lulus,drop-out',
        ]);

        Santri::create($validated);

        return redirect()->route('santri.index')
            ->with('success', 'Data santri berhasil ditambahkan');
    }

    public function show(Santri $santri)
    {
        return view('santri.show', compact('santri'));
    }

    public function edit(Santri $santri)
    {
        return view('santri.edit', compact('santri'));
    }

    public function update(Request $request, Santri $santri)
    {
        $validated = $request->validate([
            'nis' => [
                'required',
                'max:20',
                Rule::unique('santri')->ignore($santri->id)
            ],
            'nama' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:50',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string|max:255',
            'nama_ortu' => 'required|string|max:100',
            'no_hp_ortu' => 'required|string|max:20',
            'status' => 'required|in:aktif,non-aktif,lulus,drop-out',
        ]);

        $santri->update($validated);

        return redirect()->route('santri.index')
            ->with('success', 'Data santri berhasil diperbarui');
    }

    public function destroy(Santri $santri)
    {
        $santri->delete();
        return redirect()->route('santri.index')
            ->with('success', 'Data santri berhasil dihapus');
    }

    public function export()
    {
        $santri = Santri::latest()->get();
        $filename = "data_santri_" . date('Y-m-d_H-i-s') . ".csv";

        $handle = fopen('php://output', 'w');

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        return response()->stream(function () use ($handle, $santri) {
            fputcsv($handle, ['No', 'NIS', 'Nama', 'Jenis Kelamin', 'Tempat Lahir', 'Tanggal Lahir', 'Alamat', 'Nama Ortu', 'No HP Ortu', 'Status']);

            foreach ($santri as $index => $item) {
                fputcsv($handle, [
                    $index + 1,
                    $item->nis,
                    $item->nama,
                    $item->jenis_kelamin,
                    $item->tempat_lahir,
                    Carbon::parse($item->tanggal_lahir)->format('Y-m-d'),
                    $item->alamat,
                    $item->nama_ortu,
                    $item->no_hp_ortu,
                    $item->status,
                ]);
            }
            fclose($handle);
        }, 200, $headers);
    }
}