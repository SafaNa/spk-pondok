<?php

namespace App\Http\Controllers\Guardian;

use App\Http\Controllers\Controller;
use App\Models\Guardian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function show()
    {
        /** @var Guardian $guardian */
        $guardian = Auth::guard('guardian')->user();
        return view('guardian.profile', compact('guardian'));
    }

    public function update(Request $request)
    {
        /** @var Guardian $guardian */
        $guardian = Auth::guard('guardian')->user();

        $request->validate([
            'name'         => 'required|string|max:255',
            'phone'        => 'nullable|string|max:20',
            'email'        => 'nullable|email|max:255',
            'nik'          => 'nullable|string|max:16',
            'address'      => 'nullable|string|max:1000',
            'job'          => 'nullable|string|max:100',
            'relationship' => 'required|in:father,mother,guardian,sibling',
        ]);

        $guardian->update($request->only('name', 'phone', 'email', 'nik', 'address', 'job', 'relationship'));

        return back()->with('success_profile', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        /** @var Guardian $guardian */
        $guardian = Auth::guard('guardian')->user();

        $request->validate([
            'current_password' => 'required|string',
            'password'         => ['required', 'confirmed', Password::min(6)],
        ]);

        if (!Hash::check($request->current_password, $guardian->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.'])->withInput();
        }

        $guardian->update(['password' => Hash::make($request->password)]);

        return back()->with('success_password', 'Password berhasil diubah.');
    }
}
