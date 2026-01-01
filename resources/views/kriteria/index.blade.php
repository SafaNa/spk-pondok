@extends('layouts.app')

@section('title', 'Data Kriteria')

@section('content')
    <div class="bg-white shadow-sm rounded-lg">
        <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Data Kriteria
                </h3>
                <a href="{{ route('kriteria.create') }}"
                    class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-[var(--color-primary-600)] hover:bg-[var(--color-primary-700)] focus:outline-none w-full sm:w-auto">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                            clip-rule="evenodd" />
                    </svg>
                    Tambah Kriteria
                </a>
            </div>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <div class="mb-6">
                @if($totalBobot < 100)
                    <div class="rounded-md bg-yellow-50 p-4 border border-yellow-200">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">Total Bobot Belum Lengkap</h3>
                                <div class="mt-2 text-sm text-yellow-700">
                                    <p>Total bobot saat ini: <strong>{{ $totalBobot }}%</strong>. Tersedia:
                                        <strong>{{ 100 - $totalBobot }}%</strong>.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif($totalBobot == 100)
                    <div class="rounded-md bg-green-50 p-4 border border-green-200">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-circle text-green-400"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-green-800">Total Bobot Lengkap (100%)</h3>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="rounded-md bg-red-50 p-4 border border-red-200">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-times-circle text-red-400"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Total Bobot Melebihi Batas</h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <p>Total bobot saat ini: <strong>{{ $totalBobot }}%</strong>. Harap kurangi bobot kriteria.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            @if(session('success'))
                <div class="mb-4 rounded-md bg-green-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">
                                {{ session('success') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 rounded-md bg-red-50 p-4 border border-red-200">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-500"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-800">
                                {{ session('error') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <!-- Mobile Card View -->
                        <div class="block sm:hidden space-y-4">
                            @forelse($kriteria as $item)
                                <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                                    <div class="flex justify-between items-start mb-2">
                                        <div>
                                            <span
                                                class="text-xs font-semibold text-gray-500 bg-gray-100 px-2 py-1 rounded">{{ $item->kode_kriteria }}</span>
                                            <h4 class="text-lg font-medium text-gray-900 mt-1">{{ $item->nama_kriteria }}</h4>
                                        </div>
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $item->jenis == 'benefit' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ ucfirst($item->jenis) }}
                                        </span>
                                    </div>

                                    <div class="flex justify-between items-center mb-4 text-sm">
                                        <span class="text-gray-500">Bobot: <span
                                                class="font-medium text-gray-900">{{ $item->bobot }}%</span></span>
                                        <a href="{{ route('kriteria.subkriteria.index', $item->id) }}"
                                            class="inline-flex items-center text-blue-600 hover:text-blue-800 bg-blue-50 px-3 py-1 rounded-full text-xs font-medium">
                                            {{ $item->subkriteria->count() }} Subkriteria
                                        </a>
                                    </div>

                                    <div class="border-t border-gray-100 pt-3 flex justify-end space-x-4">
                                        <a href="{{ route('kriteria.subkriteria.index', $item->id) }}"
                                            class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                            <i class="fas fa-list-ul mr-1"></i> Subkriteria
                                        </a>
                                        <a href="{{ route('kriteria.edit', $item->id) }}"
                                            class="text-[var(--color-primary-600)] hover:text-[var(--color-primary-800)] text-sm font-medium">
                                            <i class="fas fa-edit mr-1"></i> Edit
                                        </a>
                                        <form action="{{ route('kriteria.destroy', $item->id) }}" method="POST"
                                            class="inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                                <i class="fas fa-trash mr-1"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8 text-gray-500">
                                    Tidak ada data kriteria
                                </div>
                            @endforelse
                        </div>

                        <div class="hidden sm:block shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Kode
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Nama Kriteria
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Bobot
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Jenis
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Subkriteria
                                        </th>
                                        <th scope="col" class="relative px-6 py-3">
                                            <span class="sr-only">Aksi</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($kriteria as $item)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $item->kode_kriteria }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $item->nama_kriteria }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $item->bobot }}%
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $item->jenis == 'benefit' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ ucfirst($item->jenis) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <a href="{{ route('kriteria.subkriteria.index', $item->id) }}"
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 hover:bg-blue-200 transition-colors duration-200">
                                                    {{ $item->subkriteria->count() }} Subkriteria
                                                </a>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                                <a href="{{ route('kriteria.subkriteria.index', $item->id) }}"
                                                    class="text-blue-600 hover:text-blue-800" title="Kelola Subkriteria">
                                                    <i class="fas fa-list-ul"></i>
                                                </a>
                                                <a href="{{ route('kriteria.edit', $item->id) }}"
                                                    class="text-[var(--color-primary-600)] hover:text-[var(--color-primary-800)]"
                                                    title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('kriteria.destroy', $item->id) }}" method="POST"
                                                    class="inline delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-800" title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6"
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                                Tidak ada data kriteria
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection