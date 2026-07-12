<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function edit()
    {
        /** @var User $user */
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'username' => ['required', 'string', 'max:50', 'alpha_dash', Rule::unique('users')->ignore($user->id)],
            'email'    => ['nullable', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'photo'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = [
            'name'     => $validated['name'],
            'username' => $validated['username'],
            'email'    => $validated['email'] ?? null,
        ];

        if ($request->hasFile('photo')) {
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }
            $data['photo'] = $request->file('photo')->store('users', 'public');
        }

        $user->update($data);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required|string|current_password',
            'password'         => 'required|string|min:8|confirmed',
        ]);

        $user->update(['password' => Hash::make($request->password)]);

        return back()->with('success_password', 'Password berhasil diubah.');
    }
}
