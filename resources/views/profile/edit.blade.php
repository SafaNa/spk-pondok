@use('Illuminate\Support\Facades\Storage')
@extends('layouts.app')

@section('title', 'Profil Saya')
@section('breadcrumb', 'Profil Saya')

@section('content')
    <div class="flex flex-col gap-6 w-full mx-auto pb-10">

        @if(session('success'))
            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 px-4 py-3 rounded-lg flex items-center gap-3">
                <span class="material-symbols-outlined">check_circle</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('success_password'))
            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 px-4 py-3 rounded-lg flex items-center gap-3">
                <span class="material-symbols-outlined">check_circle</span>
                <span>{{ session('success_password') }}</span>
            </div>
        @endif

        {{-- Info Profil --}}
        <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-xl overflow-hidden border border-slate-100 dark:border-slate-800">
            <div class="bg-gradient-to-br from-primary/10 via-purple-500/5 to-pink-500/5 px-6 py-6 sm:px-8 sm:py-8 border-b border-primary/10">
                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 text-center sm:text-left">
                    <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-white dark:bg-slate-800 shadow-sm border border-primary/20 text-primary">
                        <span class="material-symbols-outlined text-[32px]">manage_accounts</span>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold mb-2 text-slate-900 dark:text-white tracking-tight">Profil Saya</h1>
                        <p class="text-slate-500 dark:text-slate-400 text-base max-w-xl">Perbarui informasi akun dan foto profil.</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.profile.update') }}" method="POST"
                enctype="multipart/form-data"
                class="p-6 sm:p-10 flex flex-col gap-10">
                @csrf
                @method('PUT')

                {{-- Informasi Akun --}}
                <div class="space-y-6">
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2 border-b border-slate-200 dark:border-slate-700 pb-2">
                        <span class="material-symbols-outlined text-primary">badge</span>
                        Informasi Akun
                    </h3>

                    {{-- Foto --}}
                    <div class="space-y-2" x-data="photoPreview('{{ $user->photo ? Storage::url($user->photo) : '' }}')">
                        <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Foto <span class="text-slate-400 font-normal text-xs">(opsional, maks 2MB)</span></label>
                        <div class="flex items-center gap-5">
                            <div class="w-20 h-20 rounded-2xl border-2 border-slate-200 dark:border-slate-700 overflow-hidden bg-slate-100 dark:bg-slate-800 flex items-center justify-center flex-shrink-0">
                                <img x-show="preview" :src="preview" class="w-full h-full object-cover" x-cloak>
                                <span x-show="!preview" class="material-symbols-outlined text-4xl text-slate-300 dark:text-slate-600">person</span>
                            </div>
                            <div class="flex-1 space-y-2">
                                <label class="cursor-pointer inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border-2 border-dashed border-slate-300 dark:border-slate-600 hover:border-primary hover:bg-primary/5 transition-all text-sm font-medium text-slate-600 dark:text-slate-400 hover:text-primary">
                                    <span class="material-symbols-outlined text-[18px]">upload</span>
                                    Ganti Foto
                                    <input type="file" name="photo" accept="image/*" class="hidden" @change="onFileChange">
                                </label>
                                <p class="text-xs text-slate-400">JPG, PNG, WebP — kosongkan jika tidak ingin mengganti</p>
                            </div>
                        </div>
                        @error('photo')<p class="text-sm text-red-500 flex items-center gap-1 mt-1"><span class="material-symbols-outlined text-[16px]">error</span>{{ $message }}</p>@enderror
                    </div>

                    {{-- Nama --}}
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Nama Lengkap <span class="text-red-500">*</span></label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                <span class="material-symbols-outlined">person</span>
                            </div>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required autofocus
                                class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white placeholder:text-slate-400 font-medium focus:outline-none focus:border-primary focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-primary/10 transition-all duration-200"
                                placeholder="Nama Lengkap">
                        </div>
                        @error('name')<p class="text-sm text-red-500 flex items-center gap-1 mt-1"><span class="material-symbols-outlined text-[16px]">error</span>{{ $message }}</p>@enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Username --}}
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Username <span class="text-red-500">*</span></label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                    <span class="material-symbols-outlined">account_circle</span>
                                </div>
                                <input type="text" name="username" value="{{ old('username', $user->username) }}" required
                                    class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white placeholder:text-slate-400 font-medium focus:outline-none focus:border-primary focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-primary/10 transition-all duration-200"
                                    placeholder="username">
                            </div>
                            @error('username')<p class="text-sm text-red-500 flex items-center gap-1 mt-1"><span class="material-symbols-outlined text-[16px]">error</span>{{ $message }}</p>@enderror
                        </div>

                        {{-- Email --}}
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Email <span class="text-slate-400 font-normal text-xs">(opsional)</span></label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                    <span class="material-symbols-outlined">email</span>
                                </div>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                    class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white placeholder:text-slate-400 font-medium focus:outline-none focus:border-primary focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-primary/10 transition-all duration-200"
                                    placeholder="alamat@email.com">
                            </div>
                            @error('email')<p class="text-sm text-red-500 flex items-center gap-1 mt-1"><span class="material-symbols-outlined text-[16px]">error</span>{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex flex-col sm:flex-row gap-4 pt-8 border-t border-slate-200 dark:border-slate-800">
                    <button type="submit"
                        class="flex-1 px-8 py-4 rounded-xl bg-primary hover:bg-primary/90 text-white font-bold shadow-lg shadow-primary/25 hover:shadow-xl hover:shadow-primary/40 transform hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 flex items-center justify-center gap-3">
                        <span class="material-symbols-outlined">save</span>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        {{-- Ganti Password --}}
        <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-xl overflow-hidden border border-slate-100 dark:border-slate-800">
            <form action="{{ route('admin.profile.password') }}" method="POST"
                class="p-6 sm:p-10 flex flex-col gap-10">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2 border-b border-slate-200 dark:border-slate-700 pb-2">
                        <span class="material-symbols-outlined text-primary">lock_reset</span>
                        Ganti Password
                    </h3>

                    {{-- Password Saat Ini --}}
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Password Saat Ini <span class="text-red-500">*</span></label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                <span class="material-symbols-outlined">key</span>
                            </div>
                            <input type="password" name="current_password" required
                                class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white placeholder:text-slate-400 font-medium focus:outline-none focus:border-primary focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-primary/10 transition-all duration-200"
                                placeholder="Password lama">
                        </div>
                        @error('current_password')<p class="text-sm text-red-500 flex items-center gap-1 mt-1"><span class="material-symbols-outlined text-[16px]">error</span>{{ $message }}</p>@enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Password Baru <span class="text-red-500">*</span></label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                    <span class="material-symbols-outlined">lock</span>
                                </div>
                                <input type="password" name="password" required
                                    class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white placeholder:text-slate-400 font-medium focus:outline-none focus:border-primary focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-primary/10 transition-all duration-200"
                                    placeholder="Min. 8 karakter">
                            </div>
                            @error('password')<p class="text-sm text-red-500 flex items-center gap-1 mt-1"><span class="material-symbols-outlined text-[16px]">error</span>{{ $message }}</p>@enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Konfirmasi Password Baru <span class="text-red-500">*</span></label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                    <span class="material-symbols-outlined">lock_reset</span>
                                </div>
                                <input type="password" name="password_confirmation" required
                                    class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white placeholder:text-slate-400 font-medium focus:outline-none focus:border-primary focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-primary/10 transition-all duration-200"
                                    placeholder="Ulangi password baru">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-4 pt-8 border-t border-slate-200 dark:border-slate-800">
                    <button type="submit"
                        class="flex-1 px-8 py-4 rounded-xl bg-primary hover:bg-primary/90 text-white font-bold shadow-lg shadow-primary/25 hover:shadow-xl hover:shadow-primary/40 transform hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 flex items-center justify-center gap-3">
                        <span class="material-symbols-outlined">lock_reset</span>
                        Ganti Password
                    </button>
                </div>
            </form>
        </div>

    </div>
@endsection

@push('scripts')
<script>
function photoPreview(initial = '') {
    return {
        preview: initial,
        onFileChange(e) {
            const file = e.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = ev => this.preview = ev.target.result;
            reader.readAsDataURL(file);
        }
    }
}
</script>
@endpush
