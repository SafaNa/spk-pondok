<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Master\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $this->authorizeAdmin();

        $licensingOfficers = User::where('role', 'licensing_officer')
            ->orderBy('name')
            ->get();

        $departmentOfficers = User::where('role', 'department_officer')
            ->with('department')
            ->orderBy('name')
            ->get();

        return view('users.index', compact('licensingOfficers', 'departmentOfficers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorizeAdmin();
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => ['required', Rule::in([
                'licensing_officer',
                // [HIDDEN] 'finance_officer',
                // [HIDDEN] 'finance_secretary',
            ])],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $this->authorizeAdmin();

        if (!in_array($user->role, ['licensing_officer', 'department_officer'])) {
            return redirect()->route('admin.users.index')->with('error', 'User tidak dapat diedit di sini.');
        }

        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $this->authorizeAdmin();

        if (!in_array($user->role, ['licensing_officer', 'department_officer'])) {
            return redirect()->route('admin.users.index')->with('error', 'Invalid User Role for Edit.');
        }

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = [
            'name'  => $validated['name'],
            'email' => $validated['email'],
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $this->authorizeAdmin();

        if ($user->role !== 'licensing_officer') {
            return redirect()->route('admin.users.index')->with('error', 'Hanya Petugas Perizinan yang dapat dihapus.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }

    private function authorizeAdmin()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized');
        }
    }
}
