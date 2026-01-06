@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="max-w-4xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold">Riwayat Pelanggaran</h1>
                    <p class="text-gray-600">{{ $santri->nama }} ({{ $santri->nis }})</p>
                </div>
                <a href="{{ route('santri.index') }}" class="text-gray-600 hover:text-gray-800">
                    ← Kembali
                </a>
            </div>

            @if($violations->count() === 0)
                <div class="bg-white shadow-md rounded-lg p-6 text-center">
                    <p class="text-gray-500">Tidak ada catatan pelanggaran</p>
                </div>
            @else
                <!-- Summary Cards -->
                <div class="grid grid-cols-3 gap-4 mb-6">
                    <div class="bg-white shadow rounded-lg p-4">
                        <p class="text-sm text-gray-500">Total Pelanggaran</p>
                        <p class="text-2xl font-bold">{{ $violations->count() }}</p>
                    </div>
                    <div class="bg-white shadow rounded-lg p-4">
                        <p class="text-sm text-gray-500">Sanksi Belum Selesai</p>
                        <p class="text-2xl font-bold text-red-600">
                            {{ $violations->where('status_sanksi', 'belum_selesai')->count() }}</p>
                    </div>
                    <div class="bg-white shadow rounded-lg p-4">
                        <p class="text-sm text-gray-500">Sanksi Selesai</p>
                        <p class="text-2xl font-bold text-green-600">
                            {{ $violations->where('status_sanksi', 'selesai')->count() }}</p>
                    </div>
                </div>

                <!-- Timeline per Periode -->
                @foreach($violationsByPeriode as $periodeId => $periodeViolations)
                    @php
                        $periode = $periodeViolations->first()->periode;
                    @endphp
                    <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
                        <div class="px-6 py-4 bg-gray-50 border-b">
                            <h2 class="text-lg font-semibold">{{ $periode->nama }}</h2>
                        </div>
                        <div class="p-6">
                            <!-- Timeline -->
                            <div class="relative border-l-2 border-gray-200 ml-3">
                                @foreach($periodeViolations as $violation)
                                    <div class="mb-8 ml-6">
                                        <!-- Timeline dot -->
                                        <div class="absolute -left-3 mt-1.5">
                                            @if($violation->status_sanksi === 'selesai')
                                                <div class="w-5 h-5 bg-green-500 rounded-full border-2 border-white"></div>
                                            @else
                                                <div class="w-5 h-5 bg-red-500 rounded-full border-2 border-white"></div>
                                            @endif
                                        </div>

                                        <!-- Content -->
                                        <div class="bg-gray-50 rounded-lg p-4">
                                            <div class="flex justify-between items-start mb-2">
                                                <div>
                                                    <p class="font-medium text-gray-900">
                                                        {{ $violation->jenisPelanggaran->nama_pelanggaran }}</p>
                                                    <p class="text-sm text-gray-500">
                                                        {{ $violation->tanggal_kejadian->format('d F Y') }} ·
                                                        {{ $violation->jenisPelanggaran->departemen->singkatan }}
                                                    </p>
                                                </div>
                                                @php
                                                    $kategori = $violation->jenisPelanggaran->kategoriPelanggaran;
                                                    $badgeColor = match ($kategori->kode_kategori) {
                                                        'R' => 'bg-yellow-100 text-yellow-800',
                                                        'S' => 'bg-orange-100 text-orange-800',
                                                        'B' => 'bg-red-100 text-red-800',
                                                        default => 'bg-gray-100 text-gray-800'
                                                    };
                                                @endphp
                                                <span class="px-2 py-1 text-xs font-semibold rounded {{ $badgeColor }}">
                                                    {{ $kategori->nama_kategori }}
                                                </span>
                                            </div>

                                            <div class="border-t border-gray-200 mt-2 pt-2">
                                                <p class="text-sm text-gray-700"><strong>Sanksi:</strong> {{ $violation->sanksi }}</p>
                                                @if($violation->catatan)
                                                    <p class="text-sm text-gray-600 mt-1"><strong>Catatan:</strong>
                                                        {{ $violation->catatan }}</p>
                                                @endif
                                            </div>

                                            <div class="flex justify-between items-center mt-3">
                                                @if($violation->status_sanksi === 'selesai')
                                                    <span class="text-xs text-green-600">
                                                        ✓ Selesai · {{ $violation->tanggal_verifikasi->format('d/m/Y') }}
                                                    </span>
                                                @else
                                                    <span class="text-xs text-red-600">
                                                        ⚠ Belum Selesai
                                                    </span>
                                                @endif
                                                <a href="{{ route('pelanggaran.show', $violation->id) }}"
                                                    class="text-xs text-blue-600 hover:text-blue-800">
                                                    Lihat Detail →
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
@endsection