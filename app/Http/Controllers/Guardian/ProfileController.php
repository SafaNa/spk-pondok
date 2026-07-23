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
            'avatar'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->only('name', 'phone', 'email', 'nik', 'address', 'job', 'relationship');

        if ($request->hasFile('avatar')) {
            // Hapus avatar lama jika ada
            if ($guardian->avatar && \Illuminate\Support\Facades\Storage::disk('public')->exists($guardian->avatar)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($guardian->avatar);
            }
            // Upload yang baru menggunakan ImageService (crop 1:1, resize 500x500, webp)
            $path = \App\Services\ImageService::processAndSaveAvatar($request->file('avatar'), 'guardian-avatars');
            $data['avatar'] = $path;
        }

        $guardian->update($data);

        return back()->with('success_profile', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        /** @var Guardian $guardian */
        $guardian = Auth::guard('guardian')->user();

        $request->validate([
            'current_password' => 'required|string',
            'password'         => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::min(8)->mixedCase()->numbers()],
        ]);

        if (!Hash::check($request->current_password, $guardian->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.'])->withInput();
        }

        $guardian->update(['password' => Hash::make($request->password)]);

        return back()->with('success_password', 'Password berhasil diubah.');
    }
}
