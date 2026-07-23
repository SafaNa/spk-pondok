@extends('layouts.guardian')

@section('title', 'Profil Saya')
@section('mobile_title', 'Profil Saya')

@section('content')

<div>

    {{-- Header --}}
    <div class="rounded-2xl p-5 border border-blue-100 mb-6"
        style="background: linear-gradient(135deg, #eff6ffff 20%, #eef2ffb3 50%, #faf5ff99 80%);">
        <h1 class="text-xl font-black text-[#0d141b] dark:text-white mb-0.5">Profil Saya</h1>
        <p class="text-sm text-[#4c739a]">Kelola informasi akun dan keamanan Anda.</p>
    </div>

    {{-- Edit Profil --}}
    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-[#e7edf3] dark:border-slate-800 p-6 mb-5">

        <h2 class="text-base font-bold text-[#0d141b] dark:text-white mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-[20px] text-primary">person</span>
            Informasi Pribadi
        </h2>

        @if(session('success_profile'))
            <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">check_circle</span>
                {{ session('success_profile') }}
            </div>
        @endif

        <form action="{{ route('guardian.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                
                {{-- Foto Profil --}}
                <div class="space-y-1.5 sm:col-span-2 flex flex-col sm:flex-row items-center gap-4">
                    <div class="h-20 w-20 rounded-full bg-blue-100 flex items-center justify-center overflow-hidden flex-shrink-0 border-2 border-blue-200">
                        @if($guardian->avatar)
                            <img src="{{ asset('storage/' . $guardian->avatar) }}" alt="Avatar" class="h-full w-full object-cover">
                        @else
                            <span class="text-blue-600 font-bold text-2xl">{{ strtoupper(substr($guardian->name, 0, 1)) }}</span>
                        @endif
                    </div>
                    <div class="w-full">
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Foto Profil (Opsional)</label>
                        <input type="file" name="avatar" accept="image/*"
                            class="crop-avatar w-full px-3 py-2 mt-1 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white text-sm focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        <p class="text-xs text-slate-400 mt-1">Format: JPG, PNG. Maksimal 2MB.</p>
                        @error('avatar')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $guardian->name) }}" required
                        class="w-full px-3 py-2.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white text-sm focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all">
                    @error('name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="space-y-1.5">
                    <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Username</label>
                    <input type="text" value="{{ $guardian->username }}" disabled
                        class="w-full px-3 py-2.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 text-sm cursor-not-allowed">
                    <p class="text-xs text-slate-400">Username tidak dapat diubah.</p>
                </div>

                <div class="space-y-1.5">
                    <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">No. HP / WhatsApp</label>
                    <input type="text" name="phone" value="{{ old('phone', $guardian->phone) }}"
                        class="w-full px-3 py-2.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white text-sm focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all"
                        placeholder="08xxxxxxxxxx">
                    @error('phone')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="space-y-1.5">
                    <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Email</label>
                    <input type="email" name="email" value="{{ old('email', $guardian->email) }}"
                        class="w-full px-3 py-2.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white text-sm focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all"
                        placeholder="email@contoh.com">
                    @error('email')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="space-y-1.5">
                    <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">NIK</label>
                    <input type="text" name="nik" value="{{ old('nik', $guardian->nik) }}" maxlength="16"
                        class="w-full px-3 py-2.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white text-sm focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all"
                        placeholder="16 digit NIK KTP">
                    @error('nik')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="space-y-1.5">
                    <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Pekerjaan</label>
                    <input type="text" name="job" value="{{ old('job', $guardian->job) }}"
                        class="w-full px-3 py-2.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white text-sm focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all"
                        placeholder="Contoh: Wiraswasta">
                    @error('job')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="space-y-1.5 sm:col-span-2">
                    <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Hubungan dengan Santri <span class="text-red-500">*</span></label>
                    <select name="relationship" required
                        class="w-full px-3 py-2.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white text-sm focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all">
                        <option value="father"        {{ old('relationship', $guardian->relationship) === 'father'        ? 'selected' : '' }}>Ayah</option>
                        <option value="mother"        {{ old('relationship', $guardian->relationship) === 'mother'        ? 'selected' : '' }}>Ibu</option>
                        <option value="sibling"       {{ old('relationship', $guardian->relationship) === 'sibling'       ? 'selected' : '' }}>Saudara Kandung</option>
                        <option value="uncle"         {{ old('relationship', $guardian->relationship) === 'uncle'         ? 'selected' : '' }}>Paman</option>
                        <option value="aunt"          {{ old('relationship', $guardian->relationship) === 'aunt'          ? 'selected' : '' }}>Bibi</option>
                        <option value="nephew_niece"  {{ old('relationship', $guardian->relationship) === 'nephew_niece'  ? 'selected' : '' }}>Keponakan</option>
                        <option value="grandfather"   {{ old('relationship', $guardian->relationship) === 'grandfather'   ? 'selected' : '' }}>Kakek</option>
                        <option value="grandmother"   {{ old('relationship', $guardian->relationship) === 'grandmother'   ? 'selected' : '' }}>Nenek</option>
                    </select>
                    @error('relationship')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="space-y-1.5 sm:col-span-2">
                    <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Alamat</label>
                    <textarea name="address" rows="2"
                        class="w-full px-3 py-2.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white text-sm focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all resize-none"
                        placeholder="Alamat lengkap">{{ old('address', $guardian->address) }}</textarea>
                    @error('address')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit"
                    class="px-6 py-2.5 rounded-xl bg-primary hover:bg-primary/90 text-white text-sm font-bold shadow-md transition-all">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    {{-- Ubah Password --}}
    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-[#e7edf3] dark:border-slate-800 p-6">

        <h2 class="text-base font-bold text-[#0d141b] dark:text-white mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-[20px] text-amber-500">lock</span>
            Ubah Password
        </h2>

        @if(session('success_password'))
            <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">check_circle</span>
                {{ session('success_password') }}
            </div>
        @endif

        <form action="{{ route('guardian.profile.password') }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="space-y-1.5">
                <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Password Saat Ini <span class="text-red-500">*</span></label>
                <input type="password" name="current_password" required
                    class="w-full px-3 py-2.5 rounded-xl border-2 {{ $errors->has('current_password') ? 'border-red-400' : 'border-slate-200 dark:border-slate-700' }} bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white text-sm focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all"
                    placeholder="Masukkan password saat ini">
                @error('current_password')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Password Baru <span class="text-red-500">*</span></label>
                    <input type="password" name="password" id="new_password" required
                        class="w-full px-3 py-2.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white text-sm focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all"
                        placeholder="Minimal 8 karakter">
                    <x-password-strength input-id="new_password" />
                    @error('password')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="space-y-1.5">
                    <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Konfirmasi Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password_confirmation" required
                        class="w-full px-3 py-2.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white text-sm focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all"
                        placeholder="Ulangi password baru">
                </div>
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit"
                    class="px-6 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-sm font-bold shadow-md transition-all">
                    Ubah Password
                </button>
            </div>
        </form>
    </div>

</div>

@endsection
