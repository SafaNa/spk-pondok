@extends('layouts.app')

@section('title', 'Rekomendasi Kepulangan Santri')

@section('content')
    <div class="bg-white shadow-sm rounded-lg">
        <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Rekomendasi Kepulangan Santri
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Daftar rekomendasi kepulangan santri berdasarkan perhitungan SMART
                    </p>
                </div>
                <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3 w-full sm:w-auto">
                    <a href="{{ route('perhitungan.cetak') }}" target="_blank"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none text-center w-full sm:w-auto justify-center">
                        <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Cetak Laporan
                    </a>
                    <a href="{{ route('perhitungan.index') }}"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-[var(--color-primary-600)] hover:bg-[var(--color-primary-700)] focus:outline-none text-center w-full sm:w-auto justify-center">
                        Hitung Baru
                    </a>
                </div>
            </div>
        </div>
        <div class="px-4 py-5 sm:p-6">
            @if($santri->isEmpty())
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada data rekomendasi</h3>
                    <p class="mt-1 text-sm text-gray-500">Mulai dengan menambahkan penilaian untuk santri.</p>
                    <div class="mt-6">
                        <a href="{{ route('perhitungan.index') }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-[var(--color-primary-600)] hover:bg-[var(--color-primary-700)] focus:outline-none">
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                    clip-rule="evenodd" />
                            </svg>
                            Hitung Baru
                        </a>
                    </div>
                </div>
            @else
                <div class="flex flex-col">
                    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Peringkat
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Nama Santri
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                NIS
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Nilai Akhir
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Rekomendasi
                                            </th>
                                            <th scope="col" class="relative px-6 py-3">
                                                <span class="sr-only">Aksi</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($santri as $index => $item)
                                                                <tr class="{{ $index < 3 ? 'bg-[var(--color-primary-50)]' : '' }}">
                                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                                        #{{ $index + 1 }}
                                                                        @if($index < 3)
                                                                            <span class="text-yellow-500">
                                                                                <svg class="inline-block h-5 w-5" xmlns="http://www.w3.org/2000/svg"
                                                                                    viewBox="0 0 20 20" fill="currentColor">
                                                                                    <path
                                                                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                                                </svg>
                                                                            </span>
                                                                        @endif
                                                                    </td>
                                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                                        {{ $item->nama }}
                                                                    </td>
                                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                                        {{ $item->nis }}
                                                                    </td>
                                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                                        <span
                                                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                                                                                                                                                                                {{ $item->nilai_akhir >= 0.7 ? 'bg-[var(--color-primary-100)] text-[var(--color-primary-800)]' :
                                            ($item->nilai_akhir >= 0.4 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                                            {{ number_format($item->nilai_akhir, 2, ',', '.') }}
                                                                        </span>
                                                                    </td>
                                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                                        @if($item->nilai_akhir >= 0.7)
                                                                            <span
                                                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-[var(--color-primary-100)] text-[var(--color-primary-800)]">
                                                                                Direkomendasikan Dipulangkan
                                                                            </span>
                                                                        @elseif($item->nilai_akhir >= 0.4)
                                                                            <span
                                                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                                                Pertimbangkan
                                                                            </span>
                                                                        @else
                                                                            <span
                                                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                                                Tidak Direkomendasikan
                                                                            </span>
                                                                        @endif
                                                                    </td>
                                                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                                        <a href="{{ route('perhitungan.hasil', $item->id) }}"
                                                                            class="text-[var(--color-primary-600)] hover:text-[var(--color-primary-900)]">Detail</a>
                                                                    </td>
                                                                </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 bg-blue-50 border-l-4 border-blue-400 p-4">
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
                                <span class="font-bold">Keterangan Rekomendasi:</span><br>
                                - <span class="font-medium">Direkomendasikan Dipulangkan</span>: Nilai ≥ 0.7<br>
                                - <span class="font-medium">Pertimbangkan</span>: Nilai 0.4 - 0.69<br>
                                - <span class="font-medium">Tidak Direkomendasikan</span>: Nilai &lt; 0.4
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection