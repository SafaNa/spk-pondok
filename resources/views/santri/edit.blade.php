@extends('layouts.app')

@section('title', 'Edit Santri')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Edit Data Santri</h1>
            <p class="mt-1 text-gray-600">Perbarui data santri di bawah ini</p>
        </div>
        <a href="{{ route('santri.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-[var(--color-primary-700)] bg-[var(--color-primary-100)] hover:bg-[var(--color-primary-200)] focus:outline-none transition-colors duration-200">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar
        </a>
    </div>

    <!-- Form -->
    <div class="glass-card rounded-2xl p-6 shadow-xl">
        <form action="{{ route('santri.update', $santri->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Kolom Kiri -->
                <div class="space-y-5">
                    <div class="form-group">
                        <label for="nis" class="block text-sm font-medium text-gray-700 mb-1.5">NIS</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-id-card text-[var(--color-primary-500)]"></i>
                            </div>
                            <input type="text" name="nis" id="nis" value="{{ old('nis', $santri->nis) }}" required
                                class="pl-10 py-3 text-base block w-full rounded-lg border-gray-300 shadow-sm focus:border-[var(--color-primary-500)] focus:ring focus:ring-[var(--color-primary-200)] focus:ring-opacity-50 transition duration-200"
                                placeholder="Masukkan NIS"
                                style="height: 46px;">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="nama" class="block text-sm font-medium text-gray-700 mb-1.5">Nama Santri</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user text-[var(--color-primary-500)]"></i>
                            </div>
                            <input type="text" name="nama" id="nama" value="{{ old('nama', $santri->nama) }}" required
                                class="pl-10 py-3 text-base block w-full rounded-lg border-gray-300 shadow-sm focus:border-[var(--color-primary-500)] focus:ring focus:ring-[var(--color-primary-200)] focus:ring-opacity-50 transition duration-200"
                                placeholder="Nama lengkap santri"
                                style="height: 46px;">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin</label>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="inline-flex items-center p-3 rounded-lg border border-gray-200 bg-white hover:bg-gray-50 cursor-pointer transition-colors duration-200 {{ old('jenis_kelamin', $santri->jenis_kelamin) == 'L' ? 'border-[var(--color-primary-300)] bg-[var(--color-primary-50)]' : '' }}">
                                <input type="radio" name="jenis_kelamin" value="L" required
                                    {{ old('jenis_kelamin', $santri->jenis_kelamin) == 'L' ? 'checked' : '' }}
                                    class="h-4 w-4 text-[var(--color-primary-600)] border-gray-300 focus:ring-[var(--color-primary-500)]">
                                <span class="ml-2 text-gray-700">Laki-laki</span>
                            </label>
                            <label class="inline-flex items-center p-3 rounded-lg border border-gray-200 bg-white hover:bg-gray-50 cursor-pointer transition-colors duration-200 {{ old('jenis_kelamin', $santri->jenis_kelamin) == 'P' ? 'border-[var(--color-primary-300)] bg-[var(--color-primary-50)]' : '' }}">
                                <input type="radio" name="jenis_kelamin" value="P" required
                                    {{ old('jenis_kelamin', $santri->jenis_kelamin) == 'P' ? 'checked' : '' }}
                                    class="h-4 w-4 text-[var(--color-primary-600)] border-gray-300 focus:ring-[var(--color-primary-500)]">
                                <span class="ml-2 text-gray-700">Perempuan</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan -->
                <div class="space-y-5">
                    <div class="form-group">
                        <label for="tempat_lahir" class="block text-sm font-medium text-gray-700 mb-1.5">Tempat Lahir</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-map-marker-alt text-[var(--color-primary-500)]"></i>
                            </div>
                            <input type="text" name="tempat_lahir" id="tempat_lahir" value="{{ old('tempat_lahir', $santri->tempat_lahir) }}" required
                                class="pl-10 py-3 text-base block w-full rounded-lg border-gray-300 shadow-sm focus:border-[var(--color-primary-500)] focus:ring focus:ring-[var(--color-primary-200)] focus:ring-opacity-50 transition duration-200"
                                placeholder="Tempat lahir"
                                style="height: 46px;">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal Lahir</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="far fa-calendar-alt text-[var(--color-primary-500)]"></i>
                            </div>
                            <input type="date" name="tanggal_lahir" id="tanggal_lahir" 
                                value="{{ old('tanggal_lahir', $santri->tanggal_lahir->format('Y-m-d')) }}" required
                                class="pl-10 py-3 text-base block w-full rounded-lg border-gray-300 shadow-sm focus:border-[var(--color-primary-500)] focus:ring focus:ring-[var(--color-primary-200)] focus:ring-opacity-50 transition duration-200"
                                style="height: 46px;">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1.5">Status</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-info-circle text-[var(--color-primary-500)]"></i>
                            </div>
                            <select id="status" name="status" required
                                class="pl-10 py-3 text-base block w-full rounded-lg border-gray-300 shadow-sm focus:border-[var(--color-primary-500)] focus:ring focus:ring-[var(--color-primary-200)] focus:ring-opacity-50 transition duration-200"
                                style="height: 46px; padding-top: 0.5rem; padding-bottom: 0.5rem;">
                                <option value="aktif" {{ old('status', $santri->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="non-aktif" {{ old('status', $santri->status) == 'non-aktif' ? 'selected' : '' }}>Non-Aktif</option>
                                <option value="lulus" {{ old('status', $santri->status) == 'lulus' ? 'selected' : '' }}>Lulus</option>
                                <option value="drop-out" {{ old('status', $santri->status) == 'drop-out' ? 'selected' : '' }}>Drop Out</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Full Width Fields -->
                <div class="col-span-1 md:col-span-2">
                    <div class="form-group">
                        <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1.5">Alamat Lengkap</label>
                        <div class="relative">
                            <div class="absolute top-3 left-3">
                                <i class="fas fa-map-marker-alt text-[var(--color-primary-500)]"></i>
                            </div>
                            <textarea name="alamat" id="alamat" rows="3" required
                                class="pl-10 py-3 text-base block w-full rounded-lg border-gray-300 shadow-sm focus:border-[var(--color-primary-500)] focus:ring focus:ring-[var(--color-primary-200)] focus:ring-opacity-50 transition duration-200"
                                placeholder="Alamat lengkap santri"
                                style="min-height: 100px;">{{ old('alamat', $santri->alamat) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="col-span-1 md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label for="nama_ortu" class="block text-sm font-medium text-gray-700 mb-1.5">Nama Orang Tua/Wali</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user-friends text-[var(--color-primary-500)]"></i>
                            </div>
                            <input type="text" name="nama_ortu" id="nama_ortu" value="{{ old('nama_ortu', $santri->nama_ortu) }}" required
                                class="pl-10 py-3 text-base block w-full rounded-lg border-gray-300 shadow-sm focus:border-[var(--color-primary-500)] focus:ring focus:ring-[var(--color-primary-200)] focus:ring-opacity-50 transition duration-200"
                                placeholder="Nama orang tua/wali"
                                style="height: 46px;">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="no_hp_ortu" class="block text-sm font-medium text-gray-700 mb-1.5">No. HP Orang Tua/Wali</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-phone-alt text-[var(--color-primary-500)]"></i>
                            </div>
                            <input type="text" name="no_hp_ortu" id="no_hp_ortu" value="{{ old('no_hp_ortu', $santri->no_hp_ortu) }}" required
                                class="pl-10 py-3 text-base block w-full rounded-lg border-gray-300 shadow-sm focus:border-[var(--color-primary-500)] focus:ring focus:ring-[var(--color-primary-200)] focus:ring-opacity-50 transition duration-200"
                                placeholder="Nomor telepon yang bisa dihubungi"
                                style="height: 46px;">
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100">
                <div class="flex justify-between items-center">
                    <button type="button" onclick="window.history.back()" class="px-5 py-2.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none transition-colors duration-200">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </button>
                    <div class="space-x-3">
                        <a href="{{ route('santri.index') }}" class="px-5 py-2.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none transition-colors duration-200">
                            Batal
                        </a>
                        <button type="submit" class="inline-flex items-center px-5 py-2.5 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-[var(--gradient-from)] to-[var(--gradient-to)] hover:from-[var(--color-primary-600)] hover:to-[var(--color-primary-700)] focus:outline-none shadow-sm transition-all duration-200">
                            <i class="fas fa-save mr-2"></i> Perbarui Data
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection