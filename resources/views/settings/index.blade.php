@extends('layouts.app')

@section('title', 'Pengaturan Aplikasi')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-slate-800 dark:text-white">Pengaturan Aplikasi</h1>
    </div>

    @if(session('success'))
    <div class="bg-green-50 dark:bg-green-900/50 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-300 rounded-xl p-4 flex items-center mb-6">
        <span class="material-symbols-outlined mr-2">check_circle</span>
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="p-6 md:p-8 space-y-8">
                <!-- Informasi Pesantren -->
                <div>
                    <h3 class="text-lg font-medium text-slate-900 dark:text-white mb-4">Informasi Pesantren</h3>
                    
                    <div class="space-y-6">
                        <!-- Nama & Logo -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Nama Pesantren</label>
                                <input type="text" name="pesantren_name" value="{{ old('pesantren_name', $setting->pesantren_name ?? '') }}" required class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                                @error('pesantren_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Logo Pesantren</label>
                                <div class="flex items-center gap-4">
                                    @if($setting && $setting->logo)
                                        <div class="flex-shrink-0">
                                            <img src="{{ asset('storage/' . $setting->logo) }}" alt="Logo" class="w-12 h-12 object-contain bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg p-1">
                                        </div>
                                    @endif
                                    <div class="flex-1">
                                        <input type="file" name="logo" accept="image/*" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 file:mr-4 file:py-1 file:px-3 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 text-sm">
                                        @error('logo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Kontak & Alamat -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Email</label>
                                <input type="email" name="email" value="{{ old('email', $setting->email ?? '') }}" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Telepon</label>
                                <input type="text" name="phone" value="{{ old('phone', $setting->phone ?? '') }}" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                                @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Alamat Lengkap</label>
                                <textarea name="address" rows="3" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">{{ old('address', $setting->address ?? '') }}</textarea>
                                @error('address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="border-slate-200 dark:border-slate-700">

                <!-- Informasi Aplikasi -->
                <div>
                    <h3 class="text-lg font-medium text-slate-900 dark:text-white mb-4">Informasi Aplikasi</h3>
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Nama Aplikasi</label>
                            <input type="text" name="app_name" value="{{ old('app_name', $setting->app_name ?? '') }}" required class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                            @error('app_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Deskripsi Aplikasi</label>
                            <textarea name="description" rows="3" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">{{ old('description', $setting->description ?? '') }}</textarea>
                            <p class="text-slate-500 dark:text-slate-400 text-sm mt-2">Deskripsi ini akan ditampilkan di halaman login dan area publik lainnya.</p>
                            @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

            </div>
            <div class="p-6 md:p-8 bg-slate-50 dark:bg-slate-800/50 border-t border-slate-200 dark:border-slate-700 flex justify-end">
                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2.5 px-6 rounded-xl flex items-center transition-colors">
                    <span class="material-symbols-outlined mr-2">save</span>
                    Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
