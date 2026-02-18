<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     * Shows only Licensing Officers and Finance Officers.
     */
    public function index()
    {
        $this->authorizeAdmin();

        $users = User::whereIn('role', ['licensing_officer', 'finance_officer', 'finance_secretary'])
            ->orderBy('name')
            ->paginate(10);

        return view('users.index', compact('users'));
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
            'role' => ['required', Rule::in(['licensing_officer', 'finance_officer', 'finance_secretary'])],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $this->authorizeAdmin();

        // Prevent editing department officers or admins via this controller (use specialized controllers or profile)
        if (!in_array($user->role, ['licensing_officer', 'finance_officer', 'finance_secretary'])) {
            return redirect()->route('users.index')->with('error', 'Anda hanya dapat mengedit Petugas Perizinan dan Keuangan di sini.');
        }

        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $this->authorizeAdmin();

        if (!in_array($user->role, ['licensing_officer', 'finance_officer', 'finance_secretary'])) {
            return redirect()->route('users.index')->with('error', 'Invalid User Role for Edit.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            // Password optional
            'password' => 'nullable|string|min:8|confirmed',
            'role' => ['required', Rule::in(['licensing_officer', 'finance_officer', 'finance_secretary'])],
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $this->authorizeAdmin();

        if (!in_array($user->role, ['licensing_officer', 'finance_officer', 'finance_secretary'])) {
            return redirect()->route('users.index')->with('error', 'Invalid User Role for Delete.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }

    private function authorizeAdmin()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized');
        }
    }
}
