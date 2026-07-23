<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departemen = Department::latest()->paginate(10);
        return view('departemen.index', compact('departemen'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('departemen.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_departemen' => 'required|string|max:255|unique:departments,code',
            'nama_departemen' => 'required|string|max:255',
            'singkatan' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        $department = Department::create([
            'code' => $request->kode_departemen,
            'name' => $request->nama_departemen,
            'acronym' => $request->singkatan,
            'description' => $request->keterangan,
        ]);

        return redirect()->route('admin.departments.index')
            ->with('success', 'Departemen berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    // Binding is 'department' from Route::resource('departments', ...)
    // But view expects $departemen.
    public function show(Department $department)
    {
        $departemen = $department;
        return view('departemen.detail', compact('departemen'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Department $department)
    {
        $departemen = $department;
        return view('departemen.edit', compact('departemen'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Department $department)
    {
        $rules = [
            'kode_departemen' => 'required|string|max:255|unique:departments,code,' . $department->id,
            'nama_departemen' => 'required|string|max:255',
            'singkatan' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ];

        $request->validate($rules);

        $department->update([
            'code' => $request->kode_departemen,
            'name' => $request->nama_departemen,
            'acronym' => $request->singkatan,
            'description' => $request->keterangan,
        ]);

        return redirect()->route('admin.departments.index')
            ->with('success', 'Departemen berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {
        $department->delete();

        return redirect()->route('admin.departments.index')
            ->with('success', 'Departemen berhasil dihapus');
    }
}
