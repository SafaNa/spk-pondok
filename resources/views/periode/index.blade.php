@extends('layouts.app')

@section('title', 'Manajemen Periode')

@section('content')
    <div x-data="{ 
                                    createModalOpen: false, 
                                    editModalOpen: false,
                                    activateModalOpen: false,
                                    editId: '',
                                    editNama: '',
                                    editKeterangan: '',
                                    activateId: '',
                                    activateNama: '',
                                    openEditModal(id, nama, keterangan) {
                                        this.editId = id;
                                        this.editNama = nama;
                                        this.editKeterangan = keterangan;
                                        this.editModalOpen = true;
                                    },
                                    openActivateModal(id, nama) {
                                        this.activateId = id;
                                        this.activateNama = nama;
                                        this.activateModalOpen = true;
                                    }
                                }">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Data Periode Penilaian</h1>
                <p class="mt-2 text-sm text-gray-700">Daftar periode penilaian santri</p>
            </div>
            <button @click="createModalOpen = true"
                class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-[var(--color-primary-600)] hover:bg-[var(--color-primary-700)] focus:outline-none shadow-sm transition-all duration-200 w-full sm:w-auto">
                <i class="fas fa-plus mr-2"></i> Tambah Periode
            </button>
        </div>

        @if (session('success'))
            <div class="rounded-md bg-green-50 p-4 mb-6 border border-green-200" x-data="{ show: true }" x-show="show"
                x-init="setTimeout(() => show = false, 3000)">
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
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="rounded-md bg-red-50 p-4 mb-6 border border-red-200">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="bg-white shadow-sm rounded-lg border border-gray-200">
            <ul role="list" class="divide-y divide-gray-200">
                @foreach ($periodes as $periode)
                    <li class="relative">
                        <div
                            class="px-6 py-5 flex flex-col sm:flex-row items-start sm:items-center justify-between hover:bg-gray-50 transition-colors duration-150 {{ $loop->first ? 'rounded-t-lg' : '' }} {{ $loop->last ? 'rounded-b-lg' : '' }} space-y-4 sm:space-y-0">
                            <div class="flex items-center space-x-4 w-full sm:w-auto">
                                <div class="flex-shrink-0">
                                    <span
                                        class="inline-flex items-center justify-center h-12 w-12 rounded-full {{ $periode->is_active ? 'bg-[var(--color-primary-100)] text-[var(--color-primary-600)]' : 'bg-gray-100 text-gray-500' }}">
                                        <i class="fas fa-calendar-alt text-lg"></i>
                                    </span>
                                </div>
                                <div class="min-w-0 flex-1">
                                    @if ($periode->is_active)
                                    <h3
                                        class="text-base font-semibold text-[var(--color-primary-600)] group-hover:text-[var(--color-primary-600)] transition-colors truncate">
                                        {{ $periode->nama }}
                                    </h3>
                                    <p class="text-sm text-[var(--color-primary-500)] line-clamp-1">
                                        {{ $periode->keterangan ?? 'Tidak ada keterangan' }}
                                    </p>
                                    @else
                                    <h3
                                        class="text-base font-semibold text-gray-600 group-hover:text-[var(--color-primary-600)] transition-colors truncate">
                                        {{ $periode->nama }}
                                    </h3>
                                    <p class="text-sm text-gray-500 line-clamp-1">
                                        {{ $periode->keterangan ?? 'Tidak ada keterangan' }}
                                    </p>
                                    @endif
                                </div>
                            </div>
                            <div
                                class="flex items-center justify-between sm:justify-end space-x-4 w-full sm:w-auto mt-2 sm:mt-0">
                                @if ($periode->is_active)
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[var(--color-primary-100)] text-[var(--color-primary-600)]">
                                        <span class="w-1.5 h-1.5 mr-1.5 bg-[var(--color-primary-500)] rounded-full"></span> Aktif
                                    </span>
                                @else
                                    <button type="button"
                                        @click="openActivateModal('{{ $periode->id }}', '{{ addslashes($periode->nama) }}')"
                                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-full shadow-sm text-yellow-700 bg-yellow-50 hover:bg-yellow-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors">
                                        <i class="fas fa-check-circle mr-1.5"></i> Set Aktif
                                    </button>
                                @endif

                                <div class="relative" x-data="{ open: false }">
                                    <button @click="open = !open" @click.away="open = false"
                                        class="text-gray-400 hover:text-gray-600 focus:outline-none p-2 rounded-full hover:bg-gray-100">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <div x-show="open"
                                        class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-10"
                                        style="display: none;" x-transition:enter="transition ease-out duration-100"
                                        x-transition:enter-start="transform opacity-0 scale-95"
                                        x-transition:enter-end="transform opacity-100 scale-100"
                                        x-transition:leave="transition ease-in duration-75"
                                        x-transition:leave-start="transform opacity-100 scale-100"
                                        x-transition:leave-end="transform opacity-0 scale-95">
                                        <button
                                            @click="openEditModal('{{ $periode->id }}', '{{ addslashes($periode->nama) }}', '{{ addslashes($periode->keterangan ?? '') }}'); open = false"
                                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            Edit
                                        </button>
                                        @if(!$periode->is_active)
                                            <form action="{{ route('periode.destroy', $periode->id) }}" method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus periode ini? Data riwayat mungkin akan hilang.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                                    Hapus
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>

        @if($periodes->hasPages())
            <div class="mt-4">
                {{ $periodes->links() }}
            </div>
        @endif

        <!-- Create Modal -->
        <div x-show="createModalOpen" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
            aria-modal="true" style="display: none;">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-black/30 backdrop-blur-sm transition-opacity" aria-hidden="true"
                    @click="createModalOpen = false"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div
                    class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full relative z-10">
                    <form action="{{ route('periode.store') }}" method="POST">
                        @csrf
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Tambah Periode Baru
                                </h3>
                                <button type="button" @click="createModalOpen = false"
                                    class="text-gray-400 hover:text-gray-500 focus:outline-none">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <label for="nama" class="block text-sm font-medium text-gray-700">Nama Periode</label>
                                    <input type="text" name="nama" id="nama" required placeholder="Contoh: Evaluasi 2024"
                                        class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm py-2.5 px-3 focus:outline-none focus:ring-1 focus:ring-[var(--color-primary-500)] focus:border-[var(--color-primary-500)] sm:text-sm transition duration-200">
                                </div>
                                <div>
                                    <label for="keterangan"
                                        class="block text-sm font-medium text-gray-700">Keterangan</label>
                                    <textarea name="keterangan" id="keterangan" rows="3" placeholder="Deskripsi singkat..."
                                        class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm py-2.5 px-3 focus:outline-none focus:ring-1 focus:ring-[var(--color-primary-500)] focus:border-[var(--color-primary-500)] sm:text-sm transition duration-200"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[var(--color-primary-600)] text-base font-medium text-white hover:bg-[var(--color-primary-700)] focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                                Simpan
                            </button>
                            <button type="button" @click="createModalOpen = false"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <div x-show="editModalOpen" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
            aria-modal="true" style="display: none;">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-black/30 backdrop-blur-sm transition-opacity" aria-hidden="true"
                    @click="editModalOpen = false"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div
                    class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full relative z-10">
                    <form :action="'/periode/' + editId" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Edit Periode</h3>
                                <button type="button" @click="editModalOpen = false"
                                    class="text-gray-400 hover:text-gray-500 focus:outline-none">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <label for="edit_nama" class="block text-sm font-medium text-gray-700">Nama
                                        Periode</label>
                                    <input type="text" name="nama" id="edit_nama" x-model="editNama" required
                                        class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm py-2.5 px-3 focus:outline-none focus:ring-1 focus:ring-[var(--color-primary-500)] focus:border-[var(--color-primary-500)] sm:text-sm transition duration-200">
                                </div>
                                <div>
                                    <label for="edit_keterangan"
                                        class="block text-sm font-medium text-gray-700">Keterangan</label>
                                    <textarea name="keterangan" id="edit_keterangan" x-model="editKeterangan" rows="3"
                                        class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm py-2.5 px-3 focus:outline-none focus:ring-1 focus:ring-[var(--color-primary-500)] focus:border-[var(--color-primary-500)] sm:text-sm transition duration-200"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[var(--color-primary-600)] text-base font-medium text-white hover:bg-[var(--color-primary-700)] focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                                Simpan Perubahan
                            </button>
                            <button type="button" @click="editModalOpen = false"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Activate Modal -->
        <div x-show="activateModalOpen" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title"
            role="dialog" aria-modal="true" style="display: none;">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-black/30 backdrop-blur-sm transition-opacity" aria-hidden="true"
                    @click="activateModalOpen = false"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div
                    class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full relative z-10">
                    <form :action="'/periode/' + activateId + '/activate'" method="POST">
                        @csrf
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div
                                    class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <i class="fas fa-check text-green-600"></i>
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                        Aktifkan Periode
                                    </h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500">
                                            Apakah Anda yakin ingin mengaktifkan periode <span
                                                class="font-bold text-gray-800" x-text="activateNama"></span>?
                                            Periode lain yang sedang aktif akan dinonaktifkan secara otomatis.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                                Ya, Aktifkan
                            </button>
                            <button type="button" @click="activateModalOpen = false"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection