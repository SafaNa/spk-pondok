<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
            'user_email' => 'required|email|unique:users,email',
            'user_password' => 'required|string|min:6',
        ]);

        $department = Department::create([
            'code' => $request->kode_departemen,
            'name' => $request->nama_departemen,
            'acronym' => $request->singkatan,
            'description' => $request->keterangan,
        ]);

        User::create([
            'name' => $request->nama_departemen,
            'email' => $request->user_email,
            'password' => Hash::make($request->user_password),
            'role' => 'department_officer',
            'department_id' => $department->id,
        ]);

        return redirect()->route('admin.departments.index')
            ->with('success', 'Departemen berhasil ditambahkan beserta akun penggunanya.');
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
        $userAkun = User::where('department_id', $department->id)
            ->where('role', 'department_officer')
            ->first();
        return view('departemen.edit', compact('departemen', 'userAkun'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Department $department)
    {
        $userAkun = User::where('department_id', $department->id)
            ->where('role', 'department_officer')
            ->first();

        $rules = [
            'kode_departemen' => 'required|string|max:255|unique:departments,code,' . $department->id,
            'nama_departemen' => 'required|string|max:255',
            'singkatan' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'user_email' => ['required', 'email', Rule::unique('users', 'email')->ignore($userAkun?->id)],
            'user_password' => 'nullable|string|min:6',
        ];

        $request->validate($rules);

        $department->update([
            'code' => $request->kode_departemen,
            'name' => $request->nama_departemen,
            'acronym' => $request->singkatan,
            'description' => $request->keterangan,
        ]);

        if ($userAkun) {
            $userData = ['email' => $request->user_email];
            if ($request->filled('user_password')) {
                $userData['password'] = Hash::make($request->user_password);
            }
            $userAkun->update($userData);
        }

        return redirect()->route('admin.departments.index')
            ->with('success', 'Departemen berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {
        DB::transaction(function () use ($department) {
            User::where('department_id', $department->id)
                ->where('role', 'department_officer')
                ->delete();

            $department->delete();
        });

        return redirect()->route('admin.departments.index')
            ->with('success', 'Departemen berhasil dihapus');
    }
}
