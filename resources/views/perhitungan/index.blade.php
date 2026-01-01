@extends('layouts.app')

@section('title', 'Perhitungan SMART')

@section('content')
    <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
            Perhitungan Metode SMART
        </h3>
        <p class="mt-1 text-sm text-gray-500">
            Masukkan penilaian untuk menghitung keputusan kepulangan santri
        </p>
    </div>
    <div class="px-4 py-5 sm:p-6">
        @if(!$activePeriode)
            <div class="mb-6 rounded-md bg-red-50 p-4 border border-red-200">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-400"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Tidak Ada Periode Aktif</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <p>Saat ini tidak ada periode penilaian yang berstatus <strong>Aktif</strong>. Silakan aktifkan
                                periode terlebih dahulu di menu <strong>Periode</strong> untuk melakukan perhitungan.</p>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="mb-6 rounded-md bg-blue-50 p-4 border border-blue-200">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-calendar-check text-blue-400"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Periode Aktif: {{ $activePeriode->nama }}</h3>
                        <div class="mt-1 text-sm text-blue-700">
                            <p>Penilaian yang Anda masukkan akan disimpan untuk periode ini.</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('perhitungan.hitung') }}" method="POST">
            @csrf
            <div class="space-y-6">
                <div class="form-group">
                    <label for="santri_id" class="block text-sm font-medium text-gray-700 mb-1.5">Pilih Santri</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-user-graduate text-[var(--color-primary-500)]"></i>
                        </div>
                        <select id="santri_id" name="santri_id" required {{ !$activePeriode ? 'disabled' : '' }}
                            class="pl-10 py-3 text-base block w-full rounded-lg border-gray-300 shadow-sm focus:border-[var(--color-primary-500)] focus:ring focus:ring-[var(--color-primary-200)] focus:ring-opacity-50 transition duration-200 disabled:bg-gray-100 disabled:text-gray-500"
                            style="height: 46px; padding-top: 0.5rem; padding-bottom: 0.5rem;">
                            <option value="">-- Pilih Santri --</option>
                            @foreach($santri as $s)
                                <option value="{{ $s->id }}">{{ $s->nama }} ({{ $s->nis }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                @foreach($kriteria as $kriteriaItem)
                    <div class="border border-[var(--color-primary-400)] rounded-md p-4">
                        <h4 class="text-md font-medium text-gray-900 mb-3">
                            {{ $kriteriaItem->nama_kriteria }}
                            <span class="text-sm font-normal text-gray-500">(Bobot: {{ $kriteriaItem->bobot }}%)</span>
                        </h4>
                        <div class="space-y-2">
                            @foreach($kriteriaItem->subkriteria as $subkriteria)
                                <div class="flex items-center">
                                    <input id="nilai_{{ $kriteriaItem->id }}_{{ $subkriteria->id }}"
                                        name="nilai[{{ $kriteriaItem->id }}]" type="radio" value="{{ $subkriteria->id }}"
                                        class="h-4 w-4 text-[var(--color-primary-600)] border-[var(--color-primary-400)] disabled:bg-gray-200"
                                        required {{ !$activePeriode ? 'disabled' : '' }}>
                                    <label for="nilai_{{ $kriteriaItem->id }}_{{ $subkriteria->id }}"
                                        class="ml-3 block text-sm font-medium text-gray-700">
                                        {{ $subkriteria->nama_subkriteria }}
                                        <span class="text-xs text-gray-500">(Nilai: {{ $subkriteria->nilai }})</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                <div class="pt-5">
                    <div class="flex justify-end">
                        <a href="{{ route('dashboard') }}"
                            class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none">
                            Kembali
                        </a>
                        <button type="submit" {{ !$activePeriode ? 'disabled' : '' }}
                            class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-[var(--color-primary-600)] hover:bg-[var(--color-primary-700)] focus:outline-none disabled:bg-gray-400 disabled:cursor-not-allowed">
                            Hitung
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    </div>
@endsection