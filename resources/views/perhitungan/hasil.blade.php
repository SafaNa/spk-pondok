@extends('layouts.app')

@section('title', 'Hasil Perhitungan')

@section('content')
    <div class="bg-white shadow-sm rounded-lg">
        <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-4 sm:space-y-0">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Hasil Perhitungan SMART
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Detail perhitungan untuk {{ $santri->nama }} ({{ $santri->nis }})
                    </p>
                </div>
                <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
                    <a href="{{ route('perhitungan.rekomendasi') }}"
                        class="inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-[var(--color-primary-600)] hover:bg-[var(--color-primary-700)] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--color-primary-500)]">
                        Lihat Rekomendasi
                    </a>
                    <a href="{{ route('perhitungan.index') }}"
                        class="inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Kembali
                    </a>
                </div>
            </div>
        </div>

        @if($isComplete)
            <div class="px-4 py-5 sm:p-6">
                <div class="mb-8">
                    <h4 class="text-md font-medium text-gray-900 mb-4">Ringkasan</h4>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <dl class="grid grid-cols-1 gap-5 sm:grid-cols-3">
                            <div class="px-4 py-5 bg-white shadow rounded-lg overflow-hidden sm:p-6">
                                <dt class="text-sm font-medium text-gray-500 truncate">Nama Santri</dt>
                                <dd class="mt-1 text-2xl font-semibold text-gray-900">{{ $santri->nama }}</dd>
                            </div>
                            <div class="px-4 py-5 bg-white shadow rounded-lg overflow-hidden sm:p-6">
                                <dt class="text-sm font-medium text-gray-500 truncate">NIS</dt>
                                <dd class="mt-1 text-2xl font-semibold text-gray-900">{{ $santri->nis }}</dd>
                            </div>
                            <div class="px-4 py-5 bg-white shadow rounded-lg overflow-hidden sm:p-6">
                                <dt class="text-sm font-medium text-gray-500 truncate">Nilai Akhir</dt>
                                <dd class="mt-1 text-2xl font-semibold text-[var(--color-primary-600)]">
                                    {{ number_format($perhitungan['nilai_akhir'], 2, ',', '.') }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <div class="mb-8">
                    <h4 class="text-md font-medium text-gray-900 mb-4">Detail Perhitungan</h4>
                    <div class="flex flex-col">
                        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                                <!-- Mobile Card View -->
                                <div class="block sm:hidden space-y-4">
                                    @foreach($perhitungan['detail'] as $detail)
                                        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                                            <h5 class="text-sm font-bold text-gray-900 mb-2">{{ $detail['kriteria'] }}
                                                <span class="text-xs font-normal text-gray-500">
                                                    ({{ ucfirst($detail['jenis']) }})
                                                </span>
                                            </h5>
                                            <div class="grid grid-cols-2 gap-2 text-sm mb-2">
                                                <div>
                                                    <span class="block text-xs text-gray-500">Bobot</span>
                                                    <span class="font-medium">{{ $detail['bobot'] }}%</span>
                                                </div>
                                                <div>
                                                    <span class="block text-xs text-gray-500">Bobot Norm.</span>
                                                    <span
                                                        class="font-medium">{{ number_format($detail['bobot_ternormalisasi'], 2, ',', '.') }}</span>
                                                </div>
                                                <div>
                                                    <span class="block text-xs text-gray-500">Nilai</span>
                                                    <span class="font-medium">{{ $detail['nilai'] }}</span>
                                                </div>
                                                <div>
                                                    <span class="block text-xs text-gray-500">Utility</span>
                                                    <span
                                                        class="font-medium">{{ number_format($detail['utility'], 2, ',', '.') }}</span>
                                                </div>
                                            </div>
                                            <div
                                                class="border-t border-gray-100 pt-2 flex justify-between items-center text-sm font-medium">
                                                <span class="text-gray-900">Total Kontribusi</span>
                                                <span
                                                    class="text-[var(--color-primary-600)]">{{ number_format($detail['total'], 2, ',', '.') }}</span>
                                            </div>
                                        </div>
                                    @endforeach

                                    <div
                                        class="bg-[var(--color-primary-50)] rounded-lg p-4 flex justify-between items-center border border-[var(--color-primary-200)]">
                                        <span class="font-medium text-gray-900">Total Nilai Akhir</span>
                                        <span
                                            class="text-lg font-bold text-[var(--color-primary-700)]">{{ number_format($perhitungan['nilai_akhir'], 2, ',', '.') }}</span>
                                    </div>
                                </div>

                                <div class="hidden sm:block shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col"
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Kriteria
                                                </th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Bobot
                                                </th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Bobot Ternormalisasi
                                                </th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Nilai
                                                </th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Utility
                                                </th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Total
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($perhitungan['detail'] as $detail)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                        <span>{{ $detail['kriteria'] }} <span class="text-xs text-gray-500">(Bobot:
                                                                {{ $detail['bobot'] }}%)</span></span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {{ $detail['bobot'] }}%
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {{ number_format($detail['bobot_ternormalisasi'], 2, ',', '.') }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {{ $detail['nilai'] }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {{ number_format($detail['utility'], 2, ',', '.') }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                        {{ number_format($detail['total'], 2, ',', '.') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <tr class="bg-gray-50">
                                                <td colspan="5"
                                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-right">
                                                    Total Nilai Akhir:
                                                </td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm font-bold text-[var(--color-primary-600)]">
                                                    {{ number_format($perhitungan['nilai_akhir'], 2, ',', '.') }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-blue-50 border-l-4 border-blue-400 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h.01a1 1 0 100-2H10V9z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700">
                                <span class="font-bold">Keterangan:</span>
                                Nilai akhir yang lebih tinggi menunjukkan bahwa santri memiliki potensi lebih besar untuk
                                dipulangkan.
                                Nilai berkisar antara 0-1, di mana 1 adalah nilai tertinggi.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mt-8">
                    <h4 class="text-md font-medium text-gray-900 mb-4">Rincian Langkah Perhitungan</h4>
                    <div class="space-y-6">
                        @foreach($perhitungan['detail'] as $detail)
                            <div class="bg-white border border-[var(--color-primary-200)] rounded-lg shadow-sm overflow-hidden">
                                <!-- Header Kriteria -->
                                <div
                                    class="bg-[var(--color-primary-50)] px-4 py-3 border-b border-[var(--color-primary-100)] flex justify-between items-center">
                                    <h5 class="font-bold text-gray-900 text-sm sm:text-base">{{ $detail['kriteria'] }}</h5>
                                    <span
                                        class="px-2 py-1 text-xs font-medium rounded-full {{ $detail['jenis'] == 'benefit' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ ucfirst($detail['jenis']) }}
                                    </span>
                                </div>

                                <div class="p-4 space-y-4">
                                    <!-- Data Grid -->
                                    <div>
                                        <h6 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Data Awal</h6>
                                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-sm">
                                            <div class="bg-gray-50 p-2 rounded border border-gray-100">
                                                <span class="block text-xs text-gray-500">Nilai</span>
                                                <span class="font-medium text-gray-900">{{ $detail['nilai'] }}</span>
                                            </div>
                                            <div class="bg-gray-50 p-2 rounded border border-gray-100">
                                                <span class="block text-xs text-gray-500">Min / Max</span>
                                                <span class="font-medium text-gray-900">{{ $detail['min'] }} /
                                                    {{ $detail['max'] }}</span>
                                            </div>
                                            <div class="bg-gray-50 p-2 rounded border border-gray-100">
                                                <span class="block text-xs text-gray-500">Bobot</span>
                                                <span class="font-medium text-gray-900">{{ $detail['bobot'] }}%</span>
                                            </div>
                                            <div class="bg-gray-50 p-2 rounded border border-gray-100">
                                                <span class="block text-xs text-gray-500">Bobot Norm.</span>
                                                <span
                                                    class="font-medium text-gray-900">{{ number_format($detail['bobot_ternormalisasi'], 2) }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Utility Calculation -->
                                    <div>
                                        <h6 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Perhitungan
                                            Utility</h6>
                                        <div class="bg-gray-50 rounded-lg p-3 border border-gray-200 text-sm space-y-2">
                                            <div class="flex flex-col sm:flex-row sm:items-baseline gap-1">
                                                <span class="text-gray-500 w-20 flex-shrink-0">Rumus:</span>
                                                <code
                                                    class="text-[var(--color-primary-700)] bg-white px-1 py-0.5 rounded border border-gray-200 self-start">
                                                                        @if($detail['jenis'] == 'benefit')
                                                                            (Nilai - Min) / (Max - Min)
                                                                        @else
                                                                            (Max - Nilai) / (Max - Min)
                                                                        @endif
                                                                    </code>
                                            </div>
                                            <div class="flex flex-col sm:flex-row sm:items-baseline gap-1">
                                                <span class="text-gray-500 w-20 flex-shrink-0">Substitusi:</span>
                                                <span class="font-mono text-gray-700">
                                                    @if($detail['jenis'] == 'benefit')
                                                        ({{ $detail['nilai'] }} - {{ $detail['min'] }}) / ({{ $detail['max'] }} -
                                                        {{ $detail['min'] }})
                                                    @else
                                                        ({{ $detail['max'] }} - {{ $detail['nilai'] }}) / ({{ $detail['max'] }} -
                                                        {{ $detail['min'] }})
                                                    @endif
                                                </span>
                                            </div>
                                            <div
                                                class="flex flex-col sm:flex-row sm:items-baseline gap-1 pt-1 border-t border-gray-200">
                                                <span class="text-gray-500 w-20 flex-shrink-0 font-medium">Hasil:</span>
                                                <strong class="text-gray-900">{{ number_format($detail['utility'], 2) }}</strong>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Final Result -->
                                    <div>
                                        <h6 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Kontribusi
                                            Akhir</h6>
                                        <div
                                            class="bg-[var(--color-primary-50)] rounded-lg p-3 border border-[var(--color-primary-200)] flex justify-between items-center">
                                            <div class="text-sm">
                                                <span class="text-[var(--color-primary-800)] inline-block mr-1">Utility</span>
                                                <span class="text-gray-400 mx-1">×</span>
                                                <span class="text-[var(--color-primary-800)] inline-block">Bobot Norm.</span>
                                            </div>
                                            <div class="text-lg font-bold text-[var(--color-primary-700)]">
                                                {{ number_format($detail['total'], 2) }}
                                            </div>
                                        </div>
                                        <div class="text-right text-xs text-gray-500 mt-1">
                                            {{ number_format($detail['utility'], 2) }} ×
                                            {{ number_format($detail['bobot_ternormalisasi'], 2) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @else
            <div class="px-4 py-5 sm:p-6">
                <!-- Warning State -->
                <div class="rounded-md bg-yellow-50 p-4 border border-yellow-200">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <!-- Heroicon name: solid/exclamation -->
                            <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">
                                Data Penilaian Belum Lengkap
                            </h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                <p>
                                    Santri ini belum memiliki nilai untuk semua kriteria. Silakan input nilai terlebih dahulu
                                    untuk melihat hasil perhitungan.
                                </p>
                            </div>
                            <div class="mt-4">
                                <div class="-mx-2 -my-1.5 flex">
                                    <a href="{{ route('perhitungan.index') }}"
                                        class="bg-yellow-50 px-2 py-1.5 rounded-md text-sm font-medium text-yellow-800 hover:bg-yellow-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-yellow-50 focus:ring-yellow-600">
                                        Input Nilai Sekarang
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection