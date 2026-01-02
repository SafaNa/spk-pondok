@extends('layouts.app')

@section('title', 'Data Santri')

@section('content')
    <div class="bg-white shadow-sm rounded-lg">
        <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Data Santri
                </h3>
                <div class="w-full sm:w-auto">
                    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                        <a href="{{ route('santri.export') }}" target="_blank"
                            class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 w-full sm:w-auto">
                            <i class="fas fa-file-export mr-2 text-gray-500"></i>
                            Export Excel
                        </a>
                        <button onclick="document.getElementById('modal-import').classList.remove('hidden')"
                            class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 w-full sm:w-auto">
                            <i class="fas fa-file-import mr-2 text-gray-500"></i> Import Excel
                        </button>
                        <a href="{{ route('santri.create') }}"
                            class="inline-flex items-center justify-center rounded-md border border-transparent bg-[var(--color-primary-600)] px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-[var(--color-primary-700)] focus:outline-none focus:ring-2 focus:ring-[var(--color-primary-500)] focus:ring-offset-2 w-full sm:w-auto">
                            <i class="fas fa-plus mr-2"></i> Tambah Santri
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
                                                        class="text-[var(--color-primary-600)] font-medium text-base">{{ collect(explode(' ', $item->nama))->map(fn($word) => substr($word, 0, 1))->take(2)->implode('') }}</span>
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
                                                                    class="text-[var(--color-primary-600)] font-medium text-sm">{{ collect(explode(' ', $item->nama))->map(fn($word) => substr($word, 0, 1))->take(2)->implode('') }}</span>
                                                            </div>
                                                            <div class="ml-4">
                                                                <div class="text-sm font-medium text-gray-900">{{ $item->nama }}
                                                                </div>
                                                                <div class="text-sm text-gray-500">{{ $item->tempat_lahir }},
                                                                    {{ $item->tanggal_lahir?->format('d/m/Y') }}
                                                                </div>
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
                        <!-- Pagination -->
                        @if($santri->hasPages())
                            <div class="mt-4 px-4 sm:px-0">
                                {{ $santri->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <!-- Import Modal -->
        <div id="modal-import" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog"
            aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-black/30 backdrop-blur-sm transition-opacity" aria-hidden="true"
                    onclick="document.getElementById('modal-import').classList.add('hidden')"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div
                    class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full relative z-10">
                    <form action="{{ route('santri.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div
                                    class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <i class="fas fa-file-excel text-green-600"></i>
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Import Data
                                        Santri</h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500 mb-4">
                                            Upload file Excel (.xlsx, .xls) berisi data santri. Pastikan format kolom
                                            sesuai: nis, nama, jenis_kelamin, tempat_lahir, tanggal_lahir, alamat,
                                            nama_ortu, no_hp_ortu.
                                            <br><a href="{{ route('santri.template') }}"
                                                class="text-indigo-600 hover:text-indigo-500 font-medium mt-1 inline-block">Download
                                                Template Excel</a>
                                        </p>
                                        <input type="file" name="file" required class="block w-full text-sm text-gray-500
                                                                        file:mr-4 file:py-2 file:px-4
                                                                        file:rounded-full file:border-0
                                                                        file:text-sm file:font-semibold
                                                                        file:bg-green-50 file:text-green-700
                                                                        hover:file:bg-green-100
                                                                    " />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[var(--color-primary-600)] text-base font-medium text-white hover:bg-[var(--color-primary-700)] focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                                Upload
                            </button>
                            <button type="button" onclick="document.getElementById('modal-import').classList.add('hidden')"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
@endsection