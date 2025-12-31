@extends('layouts.app')

@section('title', 'Tambah Kriteria')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Tambah Kriteria</h1>
                <p class="mt-1 text-gray-600">Tambah data kriteria baru untuk penilaian santri</p>
            </div>
            <a href="{{ route('kriteria.index') }}"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-[var(--color-primary-700)] bg-[var(--color-primary-100)] hover:bg-[var(--color-primary-200)] focus:outline-none transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>

        <!-- Form -->
        <div class="glass-card rounded-2xl p-6 shadow-xl">
            <form action="{{ route('kriteria.store') }}" method="POST" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label for="kode_kriteria" class="block text-sm font-medium text-gray-700 mb-1.5">Kode
                            Kriteria</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-code text-[var(--color-primary-500)]"></i>
                            </div>
                            <input type="text" name="kode_kriteria" id="kode_kriteria" required
                                class="pl-10 py-3 text-base block w-full rounded-lg border-gray-300 shadow-sm focus:border-[var(--color-primary-500)] focus:ring focus:ring-[var(--color-primary-200)] focus:ring-opacity-50 transition duration-200"
                                placeholder="Contoh: C1, C2, dst" style="height: 46px;">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="nama_kriteria" class="block text-sm font-medium text-gray-700 mb-1.5">Nama
                            Kriteria</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-tag text-[var(--color-primary-500)]"></i>
                            </div>
                            <input type="text" name="nama_kriteria" id="nama_kriteria" required
                                class="pl-10 py-3 text-base block w-full rounded-lg border-gray-300 shadow-sm focus:border-[var(--color-primary-500)] focus:ring focus:ring-[var(--color-primary-200)] focus:ring-opacity-50 transition duration-200"
                                placeholder="Nama kriteria" style="height: 46px;">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="bobot" class="block text-sm font-medium text-gray-700 mb-1.5">Bobot</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-weight text-[var(--color-primary-500)]"></i>
                            </div>
                            <input type="number" step="0.01" min="0" name="bobot" id="bobot" required
                                class="pl-10 py-3 text-base block w-full rounded-lg border-gray-300 shadow-sm focus:border-[var(--color-primary-500)] focus:ring focus:ring-[var(--color-primary-200)] focus:ring-opacity-50 transition duration-200"
                                placeholder="0.00" style="height: 46px;">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="jenis" class="block text-sm font-medium text-gray-700 mb-1.5">Jenis Kriteria</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-sort-amount-up text-[var(--color-primary-500)]"></i>
                            </div>
                            <select id="jenis" name="jenis" required
                                class="pl-10 py-3 text-base block w-full rounded-lg border-gray-300 shadow-sm focus:border-[var(--color-primary-500)] focus:ring focus:ring-[var(--color-primary-200)] focus:ring-opacity-50 transition duration-200"
                                style="height: 46px; padding-top: 0.5rem; padding-bottom: 0.5rem;">
                                <option value="benefit">Benefit (Semakin besar semakin baik)</option>
                                <option value="cost">Cost (Semakin kecil semakin baik)</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group md:col-span-2">
                        <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-1.5">Keterangan</label>
                        <div class="relative">
                            <div class="absolute top-3 left-3">
                                <i class="fas fa-info-circle text-[var(--color-primary-500)]"></i>
                            </div>
                            <textarea id="keterangan" name="keterangan" rows="3"
                                class="pl-10 py-3 text-base block w-full rounded-lg border-gray-300 shadow-sm focus:border-[var(--color-primary-500)] focus:ring focus:ring-[var(--color-primary-200)] focus:ring-opacity-50 transition duration-200"
                                placeholder="Deskripsi atau penjelasan tambahan tentang kriteria"
                                style="min-height: 100px;"></textarea>
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-100">
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('kriteria.index') }}"
                            class="px-5 py-2.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none transition-colors duration-200">
                            Batal
                        </a>
                        <button type="submit"
                            class="inline-flex items-center px-5 py-2.5 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-[var(--gradient-from)] to-[var(--gradient-to)] hover:from-[var(--color-primary-600)] hover:to-[var(--color-primary-700)] focus:outline-none shadow-sm transition-all duration-200">
                            <i class="fas fa-save mr-2"></i> Simpan Kriteria
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection