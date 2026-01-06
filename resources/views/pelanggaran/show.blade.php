@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="max-w-4xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">Detail Pelanggaran</h1>
                <a href="{{ route('pelanggaran.index') }}" class="text-gray-600 hover:text-gray-800">
                    ← Kembali
                </a>
            </div>

            <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
                <div class="px-6 py-4 bg-gray-50 border-b">
                    <h2 class="text-lg font-semibold">Informasi Santri</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">NIS</p>
                            <p class="font-medium">{{ $violation->santri->nis }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Nama</p>
                            <p class="font-medium">{{ $violation->santri->nama }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
                <div class="px-6 py-4 bg-gray-50 border-b">
                    <h2 class="text-lg font-semibold">Detail Pelanggaran</h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Tanggal Kejadian</p>
                            <p class="font-medium">{{ $violation->tanggal_kejadian->format('d F Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Tanggal Input</p>
                            <p class="font-medium">{{ $violation->tanggal_input->format('d F Y H:i') }}</p>
                        </div>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Periode</p>
                        <p class="font-medium">{{ $violation->periode->nama }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Departemen</p>
                        <p class="font-medium">
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded">
                                {{ $violation->jenisPelanggaran->departemen->singkatan }}
                            </span>
                            {{ $violation->jenisPelanggaran->departemen->nama_departemen }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Jenis Pelanggaran</p>
                        <p class="font-medium">{{ $violation->jenisPelanggaran->nama_pelanggaran }}</p>
                        <p class="text-sm text-gray-600">{{ $violation->jenisPelanggaran->deskripsi }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Kategori</p>
                        @php
                            $kategori = $violation->jenisPelanggaran->kategoriPelanggaran;
                            $badgeColor = match ($kategori->kode_kategori) {
                                'R' => 'bg-yellow-100 text-yellow-800',
                                'S' => 'bg-orange-100 text-orange-800',
                                'B' => 'bg-red-100 text-red-800',
                                default => 'bg-gray-100 text-gray-800'
                            };
                        @endphp
                        <p>
                            <span class="px-3 py-1 text-sm font-semibold rounded {{ $badgeColor }}">
                                {{ $kategori->nama_kategori }}
                            </span>
                            <span class="text-sm text-gray-600 ml-2">({{ $kategori->bobot_poin }} poin)</span>
                        </p>
                    </div>

                    <div class="bg-gray-50 p-4 rounded">
                        <p class="text-sm text-gray-500 mb-2">Sanksi</p>
                        <p class="text-gray-800">{{ $violation->sanksi }}</p>
                    </div>

                    @if($violation->catatan)
                        <div class="bg-blue-50 p-4 rounded">
                            <p class="text-sm text-gray-500 mb-2">Catatan</p>
                            <p class="text-gray-800">{{ $violation->catatan }}</p>
                        </div>
                    @endif

                    <div>
                        <p class="text-sm text-gray-500">Dicatat Oleh</p>
                        <p class="font-medium">{{ $violation->createdBy->name }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b">
                    <h2 class="text-lg font-semibold">Status Sanksi</h2>
                </div>
                <div class="p-6">
                    @if($violation->status_sanksi === 'selesai')
                        <div class="flex items-center justify-between bg-green-50 p-4 rounded">
                            <div>
                                <p class="font-semibold text-green-800">Sanksi Selesai</p>
                                <p class="text-sm text-gray-600">Diverifikasi pada:
                                    {{ $violation->tanggal_verifikasi->format('d F Y H:i') }}</p>
                                <p class="text-sm text-gray-600">Diverifikasi oleh: {{ $violation->verifiedBy->name }}</p>
                            </div>
                            <span class="px-4 py-2 bg-green-500 text-white rounded-lg font-semibold">
                                ✓ Selesai
                            </span>
                        </div>
                    @else
                        <div class="flex items-center justify-between bg-red-50 p-4 rounded">
                            <div>
                                <p class="font-semibold text-red-800">Sanksi Belum Diselesaikan</p>
                                <p class="text-sm text-gray-600">Santri harus menyelesaikan sanksi terlebih dahulu</p>
                            </div>
                            <div class="flex gap-2">
                                <span class="px-4 py-2 bg-red-500 text-white rounded-lg font-semibold">
                                    Belum Selesai
                                </span>
                                <form action="{{ route('pelanggaran.verify', $violation->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600"
                                        onclick="return confirm('Tandai sanksi sebagai selesai?')">
                                        Tandai Selesai
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection