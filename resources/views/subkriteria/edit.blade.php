@extends('layouts.app')

@section('title', 'Edit Subkriteria - ' . $kriteria->nama_kriteria)

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">
                    <a href="{{ route('kriteria.subkriteria.index', $kriteria->id) }}"
                        class="text-[var(--color-primary-500)] hover:text-[var(--color-primary-600)]">
                        <i class="fas fa-arrow-left mr-2"></i>
                    </a>
                    Edit Subkriteria - {{ $subkriteria->nama_subkriteria }}
                </h1>
                <p class="mt-1 text-gray-600">Perbarui data subkriteria untuk kriteria {{ $kriteria->nama_kriteria }}</p>
            </div>
        </div>

        <!-- Form -->
        <div class="glass-card rounded-2xl p-6 shadow-xl">
            <form
                action="{{ route('kriteria.subkriteria.update', ['kriteria' => $kriteria->id, 'subkriteria' => $subkriteria->id]) }}"
                method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label for="nama_subkriteria" class="block text-sm font-medium text-gray-700 mb-1.5">Nama
                            Subkriteria</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-tag text-[var(--color-primary-500)]"></i>
                            </div>
                            <input type="text" name="nama_subkriteria" id="nama_subkriteria" required
                                class="pl-10 py-3 text-base block w-full rounded-lg border-gray-300 shadow-sm focus:border-[var(--color-primary-500)] focus:ring focus:ring-[var(--color-primary-200)] focus:ring-opacity-50 transition duration-200"
                                placeholder="Contoh: Sangat Baik, Baik, Cukup, Kurang" style="height: 46px;"
                                value="{{ old('nama_subkriteria', $subkriteria->nama_subkriteria) }}">
                        </div>
                        @error('nama_subkriteria')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="nilai" class="block text-sm font-medium text-gray-700 mb-1.5">Nilai</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-sort-numeric-up text-[var(--color-primary-500)]"></i>
                            </div>
                            <input type="number" step="0.01" min="0" name="nilai" id="nilai" required
                                class="pl-10 py-3 text-base block w-full rounded-lg border-gray-300 shadow-sm focus:border-[var(--color-primary-500)] focus:ring focus:ring-[var(--color-primary-200)] focus:ring-opacity-50 transition duration-200"
                                placeholder="Contoh: 1, 2, 3, 4, 5" style="height: 46px;"
                                value="{{ old('nilai', $subkriteria->nilai) }}">
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Nilai yang lebih tinggi menunjukkan preferensi lebih tinggi
                        </p>
                        @error('nilai')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group md:col-span-2">
                        <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-1.5">Keterangan</label>
                        <div class="relative">
                            <div class="absolute top-3 left-3">
                                <i class="fas fa-info-circle text-[var(--color-primary-500)]"></i>
                            </div>
                            <textarea id="keterangan" name="keterangan" rows="3"
                                class="pl-10 py-3 text-base block w-full rounded-lg border-gray-300 shadow-sm focus:border-[var(--color-primary-500)] focus:ring focus:ring-[var(--color-primary-200)] focus:ring-opacity-50 transition duration-200"
                                placeholder="Deskripsi atau penjelasan tambahan tentang subkriteria"
                                style="min-height: 100px;">{{ old('keterangan', $subkriteria->keterangan) }}</textarea>
                        </div>
                        @error('keterangan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-100">
                    <div class="flex justify-between items-center">
                        <a href="{{ route('kriteria.subkriteria.index', $kriteria->id) }}"
                            class="px-5 py-2.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--color-primary-500)] transition-colors duration-200">
                            <i class="fas fa-times mr-2"></i> Batal
                        </a>
                        <div class="space-x-3">
                            <button type="button" onclick="window.history.back()"
                                class="px-5 py-2.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--color-primary-500)] transition-colors duration-200">
                                <i class="fas fa-arrow-left mr-2"></i> Kembali
                            </button>
                            <button type="submit"
                                class="inline-flex items-center px-5 py-2.5 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-[var(--gradient-from)] to-[var(--gradient-to)] hover:from-[var(--color-primary-600)] hover:to-[var(--color-primary-700)] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--color-primary-500)] shadow-sm transition-all duration-200">
                                <i class="fas fa-save mr-2"></i> Perbarui Subkriteria
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Back to Subkriteria List -->
    <div class="mt-6">
        <a href="{{ route('kriteria.subkriteria.index', $kriteria->id) }}"
            class="inline-flex items-center text-sm font-medium text-[var(--color-primary-600)] hover:text-[var(--color-primary-800)]">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Subkriteria
        </a>
    </div>

    @push('scripts')
        <script>
            // Auto-format nilai input
            document.getElementById('nilai').addEventListener('input', function (e) {
                let value = parseFloat(e.target.value);
                if (isNaN(value)) {
                    e.target.value = '';
                } else {
                    e.target.value = value.toFixed(2);
                }
            });
        </script>
    @endpush
@endsection