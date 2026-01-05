@extends('layouts.app-v2-sidebar')

@section('title', 'Data Santri - Santri Admin')
@section('mobile_title', 'Santri Management')
@section('breadcrumb', 'Data Santri')

@section('content')
    <!-- Page Heading -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div class="flex flex-col gap-1">
            <h1 class="text-[#0d141b] dark:text-white text-3xl font-black tracking-tight">Data Santri</h1>
            <p class="text-[#4c739a] text-base font-normal">Manage student records for homecoming recommendations</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('santri.template') }}"
                class="flex items-center gap-2 h-10 px-4 rounded-lg border border-[#e7edf3] dark:border-slate-700 bg-white dark:bg-slate-800 text-[#0d141b] dark:text-white text-sm font-medium hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors shadow-sm">
                <span class="material-symbols-outlined text-[20px]">file_download</span>
                <span class="hidden sm:inline">Template</span>
            </a>
            <a href="{{ route('santri.export') }}"
                class="flex items-center gap-2 h-10 px-4 rounded-lg border border-[#e7edf3] dark:border-slate-700 bg-white dark:bg-slate-800 text-[#0d141b] dark:text-white text-sm font-medium hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors shadow-sm">
                <span class="material-symbols-outlined text-[20px]">file_download</span>
                <span class="hidden sm:inline">Export</span>
            </a>
            <button onclick="openModal('addModal')"
                class="flex items-center gap-2 h-10 px-4 rounded-lg bg-primary hover:bg-blue-600 text-white text-sm font-bold shadow-md transition-colors">
                <span class="material-symbols-outlined text-[20px]">add</span>
                <span>Add New Santri</span>
            </button>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div
            class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 px-4 py-3 rounded-lg flex items-center gap-3">
            <span class="material-symbols-outlined">check_circle</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div
            class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 px-4 py-3 rounded-lg flex items-center gap-3">
            <span class="material-symbols-outlined">error</span>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Filter & Search Bar -->
    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-[#e7edf3] dark:border-slate-800 p-4">
        <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
            <!-- Search -->
            <div class="w-full md:w-96">
                <div
                    class="relative flex items-center w-full h-10 rounded-lg focus-within:shadow-lg bg-[#f6f7f8] dark:bg-slate-800 overflow-hidden ring-1 ring-transparent focus-within:ring-primary transition-all">
                    <div class="grid place-items-center h-full w-12 text-[#4c739a]">
                        <span class="material-symbols-outlined">search</span>
                    </div>
                    <input
                        class="peer h-full w-full outline-none text-sm text-gray-700 dark:text-slate-200 pr-2 bg-transparent placeholder-gray-500"
                        id="search" placeholder="Search by Name or NIS..." type="text" />
                </div>
            </div>
            <!-- Filters -->
            <div class="flex w-full md:w-auto items-center gap-3">
                <select
                    class="h-10 px-3 rounded-lg border border-[#e7edf3] dark:border-slate-700 bg-white dark:bg-slate-800 text-[#4c739a] text-sm font-medium">
                    <option value="">All Status</option>
                    <option value="aktif">Aktif</option>
                    <option value="non-aktif">Non-Aktif</option>
                    <option value="lulus">Lulus</option>
                    <option value="drop-out">Drop Out</option>
                </select>
                <select
                    class="h-10 px-3 rounded-lg border border-[#e7edf3] dark:border-slate-700 bg-white dark:bg-slate-800 text-[#4c739a] text-sm font-medium">
                    <option value="">All Gender</option>
                    <option value="L">Laki-laki</option>
                    <option value="P">Perempuan</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div
        class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-[#e7edf3] dark:border-slate-800 overflow-hidden flex flex-col">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#f8fafc] dark:bg-slate-800/50 border-b border-[#e7edf3] dark:border-slate-700">
                        <th class="p-4 text-xs font-semibold tracking-wide text-[#4c739a] uppercase w-24">NIS</th>
                        <th class="p-4 text-xs font-semibold tracking-wide text-[#4c739a] uppercase">Nama Lengkap</th>
                        <th class="p-4 text-xs font-semibold tracking-wide text-[#4c739a] uppercase w-32">Jenis Kelamin</th>
                        <th class="p-4 text-xs font-semibold tracking-wide text-[#4c739a] uppercase w-32">Status</th>
                        <th class="p-4 text-xs font-semibold tracking-wide text-[#4c739a] uppercase w-40 text-center">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#e7edf3] dark:divide-slate-700">
                    @forelse($santri as $s)
                        @php
                            $initials = strtoupper(substr($s->nama, 0, 1) . (str_contains($s->nama, ' ') ? substr($s->nama, strpos($s->nama, ' ') + 1, 1) : substr($s->nama, 1, 1)));
                            $colors = ['blue', 'pink', 'amber', 'rose', 'indigo', 'green', 'purple', 'cyan', 'orange', 'teal'];
                            $colorIndex = crc32($s->id) % count($colors);
                            $color = $colors[$colorIndex];
                        @endphp
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors group">
                            <td class="p-4 text-sm font-medium text-[#0d141b] dark:text-white">{{ $s->nis }}</td>
                            <td class="p-4 text-sm font-medium text-[#0d141b] dark:text-white flex items-center gap-3">
                                <div
                                    class="flex h-8 w-8 items-center justify-center rounded-full bg-{{ $color }}-100 text-{{ $color }}-600 font-bold text-xs">
                                    {{ $initials }}
                                </div>
                                {{ $s->nama }}
                            </td>
                            <td class="p-4 text-sm text-[#4c739a]">{{ $s->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                            </td>
                            <td class="p-4">
                                @if($s->status == 'aktif')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">Aktif</span>
                                @elseif($s->status == 'non-aktif')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">Non-Aktif</span>
                                @elseif($s->status == 'lulus')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">Lulus</span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">{{ ucfirst($s->status) }}</span>
                                @endif
                            </td>
                            <td class="p-4">
                                <div
                                    class="flex items-center justify-center gap-2 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('santri.show', $s) }}"
                                        class="p-1.5 rounded-md hover:bg-slate-100 dark:hover:bg-slate-700 text-[#4c739a] hover:text-primary transition-colors"
                                        title="View Details">
                                        <span class="material-symbols-outlined text-[20px]">visibility</span>
                                    </a>
                                    <button
                                        onclick="openEditModal('{{ $s->id }}', '{{ $s->nis }}', '{{ $s->nama }}', '{{ $s->jenis_kelamin }}', '{{ $s->tempat_lahir }}', '{{ $s->tanggal_lahir->format('Y-m-d') }}', '{{ $s->alamat }}', '{{ $s->nama_ortu }}', '{{ $s->no_hp_ortu }}', '{{ $s->status }}')"
                                        class="p-1.5 rounded-md hover:bg-slate-100 dark:hover:bg-slate-700 text-[#4c739a] hover:text-primary transition-colors"
                                        title="Edit">
                                        <span class="material-symbols-outlined text-[20px]">edit</span>
                                    </button>
                                    <form action="{{ route('santri.destroy', $s) }}" method="POST" class="inline"
                                        onsubmit="return confirm('Yakin ingin menghapus santri ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="p-1.5 rounded-md hover:bg-red-50 dark:hover:bg-red-900/20 text-[#4c739a] hover:text-red-600 transition-colors"
                                            title="Delete">
                                            <span class="material-symbols-outlined text-[20px]">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-[#4c739a]">
                                <span class="material-symbols-outlined text-4xl mb-2">person_off</span>
                                <p>Belum ada data santri</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($santri->hasPages())
            <div
                class="flex flex-col sm:flex-row items-center justify-between gap-4 p-4 border-t border-[#e7edf3] dark:border-slate-800">
                <p class="text-sm text-[#4c739a]">
                    Showing <span class="font-medium text-[#0d141b] dark:text-white">{{ $santri->firstItem() }}</span> to
                    <span class="font-medium text-[#0d141b] dark:text-white">{{ $santri->lastItem() }}</span> of
                    <span class="font-medium text-[#0d141b] dark:text-white">{{ $santri->total() }}</span> entries
                </p>
                <div class="flex items-center gap-1">
                    @if($santri->onFirstPage())
                        <button disabled
                            class="flex items-center justify-center size-9 rounded-lg border border-[#e7edf3] dark:border-slate-700 bg-white dark:bg-slate-800 text-[#4c739a] disabled:opacity-50">
                            <span class="material-symbols-outlined text-[20px]">chevron_left</span>
                        </button>
                    @else
                        <a href="{{ $santri->previousPageUrl() }}"
                            class="flex items-center justify-center size-9 rounded-lg border border-[#e7edf3] dark:border-slate-700 bg-white dark:bg-slate-800 text-[#4c739a] hover:bg-slate-50 dark:hover:bg-slate-700">
                            <span class="material-symbols-outlined text-[20px]">chevron_left</span>
                        </a>
                    @endif

                    @foreach($santri->getUrlRange(max(1, $santri->currentPage() - 2), min($santri->lastPage(), $santri->currentPage() + 2)) as $page => $url)
                        @if($page == $santri->currentPage())
                            <span
                                class="flex items-center justify-center size-9 rounded-lg bg-primary text-white font-medium text-sm">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}"
                                class="flex items-center justify-center size-9 rounded-lg border border-[#e7edf3] dark:border-slate-700 bg-white dark:bg-slate-800 text-[#4c739a] hover:bg-slate-50 dark:hover:bg-slate-700 font-medium text-sm">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if($santri->hasMorePages())
                        <a href="{{ $santri->nextPageUrl() }}"
                            class="flex items-center justify-center size-9 rounded-lg border border-[#e7edf3] dark:border-slate-700 bg-white dark:bg-slate-800 text-[#4c739a] hover:bg-slate-50 dark:hover:bg-slate-700">
                            <span class="material-symbols-outlined text-[20px]">chevron_right</span>
                        </a>
                    @else
                        <button disabled
                            class="flex items-center justify-center size-9 rounded-lg border border-[#e7edf3] dark:border-slate-700 bg-white dark:bg-slate-800 text-[#4c739a] disabled:opacity-50">
                            <span class="material-symbols-outlined text-[20px]">chevron_right</span>
                        </button>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <!-- Add Modal -->
    <div id="addModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center overflow-y-auto">
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-xl w-full max-w-2xl mx-4 my-8 overflow-hidden">
            <div class="px-6 py-4 border-b border-[#e7edf3] dark:border-slate-700 flex items-center justify-between">
                <h3 class="text-lg font-bold text-[#0d141b] dark:text-white">Tambah Santri Baru</h3>
                <button onclick="closeModal('addModal')" class="text-[#4c739a] hover:text-[#0d141b]">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form action="{{ route('santri.store') }}" method="POST">
                @csrf
                <div class="p-6 space-y-4 max-h-[60vh] overflow-y-auto">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-[#0d141b] dark:text-white mb-1">NIS <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="nis" required
                                class="w-full rounded-lg border-[#e7edf3] dark:border-slate-600 bg-white dark:bg-slate-700 text-[#0d141b] dark:text-white focus:border-primary focus:ring-primary">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-[#0d141b] dark:text-white mb-1">Nama <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="nama" required
                                class="w-full rounded-lg border-[#e7edf3] dark:border-slate-600 bg-white dark:bg-slate-700 text-[#0d141b] dark:text-white focus:border-primary focus:ring-primary">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-[#0d141b] dark:text-white mb-1">Jenis Kelamin <span
                                    class="text-red-500">*</span></label>
                            <select name="jenis_kelamin" required
                                class="w-full rounded-lg border-[#e7edf3] dark:border-slate-600 bg-white dark:bg-slate-700 text-[#0d141b] dark:text-white focus:border-primary focus:ring-primary">
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-[#0d141b] dark:text-white mb-1">Status <span
                                    class="text-red-500">*</span></label>
                            <select name="status" required
                                class="w-full rounded-lg border-[#e7edf3] dark:border-slate-600 bg-white dark:bg-slate-700 text-[#0d141b] dark:text-white focus:border-primary focus:ring-primary">
                                <option value="aktif">Aktif</option>
                                <option value="non-aktif">Non-Aktif</option>
                                <option value="lulus">Lulus</option>
                                <option value="drop-out">Drop Out</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-[#0d141b] dark:text-white mb-1">Tempat Lahir <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="tempat_lahir" required
                                class="w-full rounded-lg border-[#e7edf3] dark:border-slate-600 bg-white dark:bg-slate-700 text-[#0d141b] dark:text-white focus:border-primary focus:ring-primary">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-[#0d141b] dark:text-white mb-1">Tanggal Lahir <span
                                    class="text-red-500">*</span></label>
                            <input type="date" name="tanggal_lahir" required
                                class="w-full rounded-lg border-[#e7edf3] dark:border-slate-600 bg-white dark:bg-slate-700 text-[#0d141b] dark:text-white focus:border-primary focus:ring-primary">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#0d141b] dark:text-white mb-1">Alamat <span
                                class="text-red-500">*</span></label>
                        <textarea name="alamat" required rows="2"
                            class="w-full rounded-lg border-[#e7edf3] dark:border-slate-600 bg-white dark:bg-slate-700 text-[#0d141b] dark:text-white focus:border-primary focus:ring-primary"></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-[#0d141b] dark:text-white mb-1">Nama Orang Tua
                                <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_ortu" required
                                class="w-full rounded-lg border-[#e7edf3] dark:border-slate-600 bg-white dark:bg-slate-700 text-[#0d141b] dark:text-white focus:border-primary focus:ring-primary">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-[#0d141b] dark:text-white mb-1">No HP Orang Tua
                                <span class="text-red-500">*</span></label>
                            <input type="text" name="no_hp_ortu" required
                                class="w-full rounded-lg border-[#e7edf3] dark:border-slate-600 bg-white dark:bg-slate-700 text-[#0d141b] dark:text-white focus:border-primary focus:ring-primary">
                        </div>
                    </div>
                </div>
                <div
                    class="px-6 py-4 border-t border-[#e7edf3] dark:border-slate-700 flex justify-end gap-3 bg-slate-50 dark:bg-slate-700/50">
                    <button type="button" onclick="closeModal('addModal')"
                        class="px-4 py-2 rounded-lg border border-[#e7edf3] dark:border-slate-600 text-[#0d141b] dark:text-white hover:bg-slate-100 dark:hover:bg-slate-600">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 rounded-lg bg-primary text-white hover:bg-blue-600">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center overflow-y-auto">
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-xl w-full max-w-2xl mx-4 my-8 overflow-hidden">
            <div class="px-6 py-4 border-b border-[#e7edf3] dark:border-slate-700 flex items-center justify-between">
                <h3 class="text-lg font-bold text-[#0d141b] dark:text-white">Edit Santri</h3>
                <button onclick="closeModal('editModal')" class="text-[#4c739a] hover:text-[#0d141b]">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="p-6 space-y-4 max-h-[60vh] overflow-y-auto">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-[#0d141b] dark:text-white mb-1">NIS <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="nis" id="edit_nis" required
                                class="w-full rounded-lg border-[#e7edf3] dark:border-slate-600 bg-white dark:bg-slate-700 text-[#0d141b] dark:text-white focus:border-primary focus:ring-primary">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-[#0d141b] dark:text-white mb-1">Nama <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="nama" id="edit_nama" required
                                class="w-full rounded-lg border-[#e7edf3] dark:border-slate-600 bg-white dark:bg-slate-700 text-[#0d141b] dark:text-white focus:border-primary focus:ring-primary">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-[#0d141b] dark:text-white mb-1">Jenis Kelamin <span
                                    class="text-red-500">*</span></label>
                            <select name="jenis_kelamin" id="edit_jenis_kelamin" required
                                class="w-full rounded-lg border-[#e7edf3] dark:border-slate-600 bg-white dark:bg-slate-700 text-[#0d141b] dark:text-white focus:border-primary focus:ring-primary">
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-[#0d141b] dark:text-white mb-1">Status <span
                                    class="text-red-500">*</span></label>
                            <select name="status" id="edit_status" required
                                class="w-full rounded-lg border-[#e7edf3] dark:border-slate-600 bg-white dark:bg-slate-700 text-[#0d141b] dark:text-white focus:border-primary focus:ring-primary">
                                <option value="aktif">Aktif</option>
                                <option value="non-aktif">Non-Aktif</option>
                                <option value="lulus">Lulus</option>
                                <option value="drop-out">Drop Out</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-[#0d141b] dark:text-white mb-1">Tempat Lahir <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="tempat_lahir" id="edit_tempat_lahir" required
                                class="w-full rounded-lg border-[#e7edf3] dark:border-slate-600 bg-white dark:bg-slate-700 text-[#0d141b] dark:text-white focus:border-primary focus:ring-primary">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-[#0d141b] dark:text-white mb-1">Tanggal Lahir <span
                                    class="text-red-500">*</span></label>
                            <input type="date" name="tanggal_lahir" id="edit_tanggal_lahir" required
                                class="w-full rounded-lg border-[#e7edf3] dark:border-slate-600 bg-white dark:bg-slate-700 text-[#0d141b] dark:text-white focus:border-primary focus:ring-primary">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#0d141b] dark:text-white mb-1">Alamat <span
                                class="text-red-500">*</span></label>
                        <textarea name="alamat" id="edit_alamat" required rows="2"
                            class="w-full rounded-lg border-[#e7edf3] dark:border-slate-600 bg-white dark:bg-slate-700 text-[#0d141b] dark:text-white focus:border-primary focus:ring-primary"></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-[#0d141b] dark:text-white mb-1">Nama Orang Tua
                                <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_ortu" id="edit_nama_ortu" required
                                class="w-full rounded-lg border-[#e7edf3] dark:border-slate-600 bg-white dark:bg-slate-700 text-[#0d141b] dark:text-white focus:border-primary focus:ring-primary">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-[#0d141b] dark:text-white mb-1">No HP Orang Tua
                                <span class="text-red-500">*</span></label>
                            <input type="text" name="no_hp_ortu" id="edit_no_hp_ortu" required
                                class="w-full rounded-lg border-[#e7edf3] dark:border-slate-600 bg-white dark:bg-slate-700 text-[#0d141b] dark:text-white focus:border-primary focus:ring-primary">
                        </div>
                    </div>
                </div>
                <div
                    class="px-6 py-4 border-t border-[#e7edf3] dark:border-slate-700 flex justify-end gap-3 bg-slate-50 dark:bg-slate-700/50">
                    <button type="button" onclick="closeModal('editModal')"
                        class="px-4 py-2 rounded-lg border border-[#e7edf3] dark:border-slate-600 text-[#0d141b] dark:text-white hover:bg-slate-100 dark:hover:bg-slate-600">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 rounded-lg bg-primary text-white hover:bg-blue-600">Perbarui</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
            document.getElementById(id).classList.add('flex');
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
            document.getElementById(id).classList.remove('flex');
        }

        function openEditModal(id, nis, nama, jenis_kelamin, tempat_lahir, tanggal_lahir, alamat, nama_ortu, no_hp_ortu, status) {
            document.getElementById('editForm').action = '/santri/' + id;
            document.getElementById('edit_nis').value = nis;
            document.getElementById('edit_nama').value = nama;
            document.getElementById('edit_jenis_kelamin').value = jenis_kelamin;
            document.getElementById('edit_tempat_lahir').value = tempat_lahir;
            document.getElementById('edit_tanggal_lahir').value = tanggal_lahir;
            document.getElementById('edit_alamat').value = alamat;
            document.getElementById('edit_nama_ortu').value = nama_ortu;
            document.getElementById('edit_no_hp_ortu').value = no_hp_ortu;
            document.getElementById('edit_status').value = status;
            openModal('editModal');
        }

        document.querySelectorAll('[id$="Modal"]').forEach(modal => {
            modal.addEventListener('click', function (e) {
                if (e.target === this) {
                    closeModal(this.id);
                }
            });
        });
    </script>
@endsection