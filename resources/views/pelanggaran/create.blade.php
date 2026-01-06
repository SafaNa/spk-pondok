@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="max-w-2xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">Catat Pelanggaran Santri</h1>
                <a href="{{ route('pelanggaran.index') }}" class="text-gray-600 hover:text-gray-800">
                    ← Kembali
                </a>
            </div>

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white shadow-md rounded-lg p-6">
                <form action="{{ route('pelanggaran.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="santri_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Santri <span class="text-red-500">*</span>
                        </label>
                        <select name="santri_id" id="santri_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('santri_id') border-red-500 @enderror">
                            <option value="">-- Pilih Santri --</option>
                            @foreach($santri as $s)
                                <option value="{{ $s->id }}" {{ old('santri_id') == $s->id ? 'selected' : '' }}>
                                    {{ $s->nis }} - {{ $s->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('santri_id')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="jenis_pelanggaran_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Jenis Pelanggaran <span class="text-red-500">*</span>
                        </label>
                        <select name="jenis_pelanggaran_id" id="jenis_pelanggaran_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('jenis_pelanggaran_id') border-red-500 @enderror">
                            <option value="">-- Pilih Jenis Pelanggaran --</option>
                            @foreach($jenisPelanggaran as $jp)
                                <option value="{{ $jp->id }}" data-kategori="{{ $jp->kategoriPelanggaran->nama_kategori }}"
                                    data-sanksi="{{ $jp->sanksi_default }}" {{ old('jenis_pelanggaran_id') == $jp->id ? 'selected' : '' }}>
                                    {{ $jp->nama_pelanggaran }} ({{ $jp->kategoriPelanggaran->nama_kategori }})
                                </option>
                            @endforeach
                        </select>
                        @error('jenis_pelanggaran_id')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div id="sanksi-preview" class="mb-4 hidden">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                            <p class="text-sm font-medium text-gray-700 mb-1">Kategori: <span id="kategori-text"></span></p>
                            <p class="text-sm font-medium text-gray-700 mb-1">Sanksi Default:</p>
                            <p class="text-sm text-gray-600" id="sanksi-text"></p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="tanggal_kejadian" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Kejadian <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal_kejadian" id="tanggal_kejadian"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('tanggal_kejadian') border-red-500 @enderror"
                            value="{{ old('tanggal_kejadian', date('Y-m-d')) }}" required>
                        @error('tanggal_kejadian')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="catatan" class="block text-sm font-medium text-gray-700 mb-2">
                            Catatan Tambahan
                        </label>
                        <textarea name="catatan" id="catatan" rows="4"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Catatan atau keterangan tambahan...">{{ old('catatan') }}</textarea>
                    </div>

                    <div class="bg-gray-50 p-3 rounded mb-4">
                        <p class="text-sm text-gray-600">
                            <strong>Periode Aktif:</strong> {{ $periode->nama }}
                        </p>
                    </div>

                    <div class="flex justify-end gap-2">
                        <a href="{{ route('pelanggaran.index') }}"
                            class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                            Batal
                        </a>
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('jenis_pelanggaran_id').addEventListener('change', function () {
            const selected = this.options[this.selectedIndex];
            const preview = document.getElementById('sanksi-preview');

            if (this.value) {
                const kategori = selected.getAttribute('data-kategori');
                const sanksi = selected.getAttribute('data-sanksi');

                document.getElementById('kategori-text').textContent = kategori;
                document.getElementById('sanksi-text').textContent = sanksi;
                preview.classList.remove('hidden');
            } else {
                preview.classList.add('hidden');
            }
        });
    </script>
@endsection