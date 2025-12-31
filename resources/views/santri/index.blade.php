@extends('layouts.app')

@section('title', 'Data Santri')

@section('content')
    <div class="bg-white shadow-sm rounded-lg">
        <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
            <div class="flex justify-between items-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Data Santri
                </h3>
                <div>
                    <a href="{{ route('santri.export') }}" target="_blank"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none mr-3">
                        <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Export Excel
                    </a>
                    <a href="{{ route('santri.create') }}"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-[var(--color-primary-600)] hover:bg-[var(--color-primary-700)] focus:outline-none">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                clip-rule="evenodd" />
                        </svg>
                        Tambah Santri
                    </a>
                </div>
            </div>
        </div>
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex flex-col">
                    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                            <!-- Mobile Card View -->
                            <div class="block sm:hidden space-y-4">
                                @forelse($santri as $item)
                                    <div
                                        class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm relative overflow-hidden">
                                        <div class="absolute top-0 right-0 p-4">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $item->status == 'aktif' ? 'bg-[var(--color-primary-100)] text-[var(--color-primary-800)]' : 'bg-red-100 text-red-800' }}">
                                                {{ ucfirst($item->status) }}
                                            </span>
                                        </div>
                                        <div class="flex items-center mb-4">
                                            <div
                                                class="flex-shrink-0 h-12 w-12 bg-[var(--color-primary-100)] rounded-full flex items-center justify-center">
                                                <span
                                                    class="text-[var(--color-primary-600)] font-medium text-lg">{{ substr($item->nama, 0, 1) }}</span>
                                            </div>
                                            <div class="ml-4 pr-16">
                                                <div class="text-base font-medium text-gray-900 truncate">{{ $item->nama }}
                                                </div>
                                                <div class="text-sm text-gray-500">{{ $item->nis }}</div>
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-2 gap-4 mb-4 text-sm">
                                            <div>
                                                <span class="block text-gray-500 text-xs">Jenis Kelamin</span>
                                                <span
                                                    class="font-medium {{ $item->jenis_kelamin == 'L' ? 'text-blue-600' : 'text-pink-600' }}">
                                                    {{ $item->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                                </span>
                                            </div>
                                            <div>
                                                <span class="block text-gray-500 text-xs">TTL</span>
                                                <span class="font-medium text-gray-900">{{ $item->tempat_lahir }}</span>
                                            </div>
                                        </div>
                                        <div class="border-t border-gray-100 pt-3 flex justify-end space-x-3">
                                            <a href="{{ route('santri.edit', $item->id) }}"
                                                class="inline-flex items-center text-sm font-medium text-[var(--color-primary-600)] hover:text-[var(--color-primary-700)]">
                                                <i class="fas fa-edit mr-1"></i> Edit
                                            </a>
                                            <form action="{{ route('santri.destroy', $item->id) }}" method="POST"
                                                class="inline-block delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="inline-flex items-center text-sm font-medium text-red-600 hover:text-red-700">
                                                    <i class="fas fa-trash-alt mr-1"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-8 text-gray-500">
                                        Tidak ada data santri
                                    </div>
                                @endforelse
                            </div>

                            <!-- Desktop Table View -->
                            <div class="hidden sm:block shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Nama
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                NIS
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Jenis Kelamin
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Status
                                            </th>
                                            <th scope="col" class="relative px-6 py-3">
                                                <span class="sr-only">Aksi</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse($santri as $item)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div
                                                            class="flex-shrink-0 h-10 w-10 bg-[var(--color-primary-100)] rounded-full flex items-center justify-center">
                                                            <span
                                                                class="text-[var(--color-primary-600)] font-medium">{{ substr($item->nama, 0, 1) }}</span>
                                                        </div>
                                                        <div class="ml-4">
                                                            <div class="text-sm font-medium text-gray-900">{{ $item->nama }}
                                                            </div>
                                                            <div class="text-sm text-gray-500">{{ $item->tempat_lahir }},
                                                                {{ $item->tanggal_lahir?->format('d/m/Y') }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">{{ $item->nis }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span
                                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $item->jenis_kelamin == 'L' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                                                        {{ $item->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span
                                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $item->status == 'aktif' ? 'bg-[var(--color-primary-100)] text-[var(--color-primary-800)]' : 'bg-red-100 text-red-800' }}">
                                                        {{ ucfirst($item->status) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <a href="{{ route('santri.edit', $item->id) }}"
                                                        class="text-[var(--color-primary-600)] hover:text-[var(--color-primary-900)] mr-3">Edit</a>
                                                    <form action="{{ route('santri.destroy', $item->id) }}" method="POST"
                                                        class="inline-block delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="text-red-600 hover:text-red-900">Hapus</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5"
                                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                                    Tidak ada data santri
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
    </div>
@endsection