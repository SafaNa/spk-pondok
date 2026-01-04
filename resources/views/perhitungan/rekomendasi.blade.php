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
                        Daftar rekomendasi kepulangan santri berdasarkan perhitungan SAW
                    </p>
                </div>
                <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3 w-full sm:w-auto">
                    <form action="{{ route('perhitungan.recalculateBatch') }}" method="POST" class="inline-block"
                        onsubmit="return confirm('Apakah Anda yakin ingin menghitung ulang semua data?');">
                        @csrf
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none text-center w-full sm:w-auto justify-center transition-colors duration-200">
                            <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Hitung Ulang
                        </button>
                    </form>
                    <a href="{{ route('perhitungan.cetak') }}" target="_blank"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none text-center w-full sm:w-auto justify-center transition-colors duration-200">
                        <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Cetak Laporan
                    </a>
                    <a href="{{ route('perhitungan.index') }}"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-[var(--color-primary-600)] hover:bg-[var(--color-primary-700)] focus:outline-none text-center w-full sm:w-auto justify-center transition-colors duration-200">
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
                            <!-- Mobile Card View -->
                            <div class="block sm:hidden space-y-4">
                                @foreach($santri as $index => $item)
                                    <div
                                        class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm {{ $index < 3 ? 'ring-2 ring-yellow-400 bg-yellow-50/50' : '' }}">
                                        <div class="flex justify-between items-start mb-3">
                                            <div class="flex items-center">
                                                <span
                                                    class="inline-flex items-center justify-center p-2 rounded-full {{ $index < 3 ? 'bg-yellow-100 text-yellow-600' : 'bg-gray-100 text-gray-500' }} font-bold text-sm h-8 w-8 mr-3">
                                                    #{{ $index + 1 }}
                                                </span>
                                                <div>
                                                    <h4 class="text-base font-bold text-gray-900">{{ $item->santri->nama }}</h4>
                                                    <p class="text-xs text-gray-500">{{ $item->santri->nis }}</p>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <span class="block text-xl font-bold text-[var(--color-primary-600)]">
                                                    {{ number_format($item->nilai_akhir, 2, ',', '.') }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="mb-2">
                                            <p class="text-sm text-gray-600">
                                                <span class="font-medium">Alasan:</span> {{ $item->alasan ?? '-' }}
                                            </p>
                                        </div>
                                        <div class="mb-4">
                                            @if($item->nilai_akhir >= 0.7)
                                                <div
                                                    class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded inline-block font-medium">
                                                    <i class="fas fa-check-circle mr-1"></i> Direkomendasikan
                                                </div>
                                            @elseif($item->nilai_akhir >= 0.4)
                                                <div
                                                    class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded inline-block font-medium">
                                                    <i class="fas fa-exclamation-circle mr-1"></i> Pertimbangkan
                                                </div>
                                            @else
                                                <div class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded inline-block font-medium">
                                                    <i class="fas fa-times-circle mr-1"></i> Tidak Direkomendasikan
                                                </div>
                                            @endif
                                        </div>

                                        <div class="border-t border-gray-100 pt-3 flex justify-between items-center">
                                            <form action="{{ route('perhitungan.destroy', $item->santri_id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" onclick="confirmDelete(this)"
                                                    class="text-red-600 text-sm hover:text-red-800 font-medium">Hapus</button>
                                            </form>
                                            <a href="{{ route('perhitungan.hasil', $item->santri_id) }}"
                                                class="inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                                                Lihat Detail
                                                <i class="fas fa-arrow-right ml-2 text-xs"></i>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Desktop Table View -->
                            <div class="hidden sm:block shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
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
                                                                @php $rank = ($santri->currentPage() - 1) * $santri->perPage() + $loop->iteration; @endphp
                                                                <tr class="{{ $rank <= 3 ? 'bg-[var(--color-primary-50)]' : '' }}">
                                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                                        #{{ $rank }}
                                                                        @if($rank <= 3)
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
                                                                        {{ $item->santri->nama }}
                                                                    </td>
                                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                                        {{ $item->santri->nis }}
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
                                                                        <div class="flex items-center justify-end space-x-2">
                                                                            <!-- Alasan Button -->
                                                                            <div class="group relative inline-block">
                                                                                <button type="button"
                                                                                    onclick="showAlasan('{{ $item->santri->nama }}', '{{ $item->alasan ?? 'Tidak ada data alasan' }}')"
                                                                                    class="inline-flex items-center p-2 border border-transparent rounded-full shadow-sm text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                                                            stroke-width="2"
                                                                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                                    </svg>
                                                                                </button>
                                                                                <span
                                                                                    class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 text-xs text-white bg-gray-800 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-10">
                                                                                    Lihat Alasan
                                                                                </span>
                                                                            </div>

                                                                            <!-- Detail Button -->
                                                                            <div class="group relative inline-block">
                                                                                <a href="{{ route('perhitungan.hasil', $item->santri_id) }}"
                                                                                    class="inline-flex items-center p-2 border border-transparent rounded-full shadow-sm text-white bg-[var(--color-primary-600)] hover:bg-[var(--color-primary-700)] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--color-primary-500)]">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                                                            stroke-width="2"
                                                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                                                    </svg>
                                                                                </a>
                                                                                <span
                                                                                    class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 text-xs text-white bg-gray-800 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-10">
                                                                                    Lihat Detail
                                                                                </span>
                                                                            </div>

                                                                            <!-- Hapus Button -->
                                                                            <form action="{{ route('perhitungan.destroy', $item->santri_id) }}"
                                                                                method="POST" class="inline group relative">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <button type="button" onclick="confirmDelete(this)"
                                                                                    class="inline-flex items-center p-2 border border-transparent rounded-full shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                                                            stroke-width="2"
                                                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                                    </svg>
                                                                                </button>
                                                                                <span
                                                                                    class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 text-xs text-white bg-gray-800 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-10">
                                                                                    Hapus
                                                                                </span>
                                                                            </form>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                @if($santri->hasPages())
                    <div class="mt-4">
                        {{ $santri->links() }}
                    </div>
                @endif

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
    </div>

    <script>
        function showAlasan(nama, alasan) {
            Swal.fire({
                title: 'Alasan Rekomendasi',
                html: `
                            <p class="text-sm text-gray-600 mb-2">Santri: <strong>${nama}</strong></p>
                            <div class="bg-gray-50 p-4 rounded-md text-left">
                                <p class="text-gray-800">${alasan}</p>
                            </div>
                        `,
                icon: 'info',
                confirmButtonText: 'Tutup',
                confirmButtonColor: 'var(--color-primary-600)'
            });
        }

        function confirmDelete(button) {
            Swal.fire({
                title: 'Hapus Hasil Perhitungan?',
                text: "Data penilaian dan hasil normalisasi untuk santri ini akan dihapus permanen.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    button.closest('form').submit();
                }
            })
        }
    </script>
@endsection