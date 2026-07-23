<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Master\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $this->authorizeAdmin();

        $query = User::with('department')->orderBy('type')->orderBy('name');

        if ($request->filled('role')) {
            if ($request->role === 'perizinan') {
                $query->whereHas('department', function ($q) {
                    $q->where('acronym', 'PERIZINAN');
                })->where('type', 1);
            } elseif ($request->role === 'departemen') {
                $query->where('type', 1)->where(function ($q) {
                    $q->whereDoesntHave('department', function ($sq) {
                        $sq->where('acronym', 'PERIZINAN');
                    })->orWhereNull('department_id');
                });
            }
        }

        $users = $query->get();

        return view('users.index', compact('users'));
    }

    public function create()
    {
        $this->authorizeAdmin();
        $departments = Department::orderBy('name')->get();
        return view('users.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'username'      => 'required|string|max:50|unique:users|alpha_dash',
            'email'         => 'nullable|string|email|max:255|unique:users',
            'photo'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'password'      => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::min(8)->mixedCase()->numbers()],
            'department_id' => 'required|exists:departments,id',
        ]);

        $photoPath = $request->hasFile('photo')
            ? \App\Services\ImageService::processAndSaveAvatar($request->file('photo'), 'users')
            : null;

        User::create([
            'name'          => $validated['name'],
            'username'      => $validated['username'],
            'email'         => $validated['email'] ?? null,
            'photo'         => $photoPath,
            'password'      => Hash::make($validated['password']),
            'type'          => 1,
            'department_id' => $validated['department_id'],
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $this->authorizeAdmin();
        $departments = Department::orderBy('name')->get();
        return view('users.edit', compact('user', 'departments'));
    }

    public function update(Request $request, User $user)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'username'      => ['required', 'string', 'max:50', 'alpha_dash', Rule::unique('users')->ignore($user->id)],
            'email'         => ['nullable', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'photo'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'password'      => ['nullable', 'confirmed', \Illuminate\Validation\Rules\Password::min(8)->mixedCase()->numbers()],
            'department_id' => $user->isAdmin() ? 'nullable' : 'required|exists:departments,id',
        ]);

        $data = [
            'name'          => $validated['name'],
            'username'      => $validated['username'],
            'email'         => $validated['email'] ?? null,
            'department_id' => $user->isAdmin() ? null : ($validated['department_id'] ?? null),
        ];

        if ($request->hasFile('photo')) {
            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }
            $data['photo'] = \App\Services\ImageService::processAndSaveAvatar($request->file('photo'), 'users');
        }

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $this->authorizeAdmin();

        if ($user->isAdmin()) {
            return redirect()->route('admin.users.index')->with('error', 'Akun Admin tidak dapat dihapus.');
        }

        if ($user->photo) {
            Storage::disk('public')->delete($user->photo);
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }

    private function authorizeAdmin(): void
    {
        /** @var User $authUser */
        $authUser = Auth::user();
        if (!$authUser?->isAdmin()) {
            abort(403, 'Unauthorized');
        }
    }
}
