@extends('layouts.app')

@section('title', 'Kategori Pelanggaran')
@section('breadcrumb', 'Kategori')
@section('breadcrumb_parent', 'Pelanggaran')
@section('breadcrumb_parent_route', 'violations.index')
@section('mobile_title', 'Kategori Pelanggaran')

@section('content')
    <div class="flex flex-col gap-6">
        {{-- Header --}}
        <div class="rounded-2xl p-4 sm:p-6 border border-blue-100 mb-6"
            style="background: linear-gradient(135deg, #eff6ffff 20%, #eef2ffb3 50%, #faf5ff99 80%);">
            <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-4">
                <div class="flex flex-col gap-1">
                    <h2 class="text-[#0d141b] dark:text-white text-2xl font-black tracking-tight">Kategori Pelanggaran</h2>
                    <p class="text-[#4c739a] text-sm sm:text-base font-normal">Manajemen kategori pelanggaran di pondok
                        pesantren</p>
                </div>
                <button onclick="openModal('createModal')"
                    class="group flex items-center justify-center gap-2 rounded-xl px-5 h-11 bg-primary hover:bg-primary/90 text-white text-sm font-bold shadow-lg hover:shadow-xl hover:shadow-primary/30 transform hover:-translate-y-0.5 transition-all duration-200">
                    <span
                        class="material-symbols-outlined text-[20px] group-hover:rotate-90 transition-transform duration-300">add</span>
                    <span>Tambah Kategori</span>
                </button>
            </div>
        </div>

        {{-- Alerts --}}
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform -translate-y-2"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform -translate-y-2"
                class="rounded-xl p-4 border border-green-200 bg-green-50 dark:bg-green-900/10 dark:border-green-800 flex items-start gap-4">
                <div
                    class="flex-shrink-0 flex items-center justify-center w-8 h-8 rounded-full bg-green-100 dark:bg-green-800 text-green-600 dark:text-green-300">
                    <span class="material-symbols-outlined text-[20px]">check_circle</span>
                </div>
                <div class="flex-1 pt-1">
                    <p class="font-semibold text-green-800 dark:text-green-200 text-sm">Berhasil!</p>
                    <p class="text-green-700 dark:text-green-300 text-sm mt-0.5">{{ session('success') }}</p>
                </div>
                <button @click="show = false"
                    class="flex-shrink-0 text-green-500 hover:text-green-700 dark:text-green-400 dark:hover:text-green-200 transition-colors">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div x-data="{ show: true }" x-show="show" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform -translate-y-2"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform -translate-y-2"
                class="rounded-xl p-4 border border-red-200 bg-red-50 dark:bg-red-900/10 dark:border-red-800 flex items-start gap-4">
                <div
                    class="flex-shrink-0 flex items-center justify-center w-8 h-8 rounded-full bg-red-100 dark:bg-red-800 text-red-600 dark:text-red-300">
                    <span class="material-symbols-outlined text-[20px]">error</span>
                </div>
                <div class="flex-1 pt-1">
                    <p class="font-semibold text-red-800 dark:text-red-200 text-sm">Gagal!</p>
                    <p class="text-red-700 dark:text-red-300 text-sm mt-0.5">{{ session('error') }}</p>
                </div>
                <button @click="show = false"
                    class="flex-shrink-0 text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-200 transition-colors">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>
        @endif

        {{-- Table Card --}}
        <div
            class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-[#e7edf3] dark:border-slate-700 overflow-hidden">
            <div
                class="px-6 py-4 border-b border-[#e7edf3] dark:border-slate-700 flex justify-between items-center bg-slate-50/50 dark:bg-slate-800/50">
                <h3 class="font-semibold text-[#0d141b] dark:text-white">Daftar Kategori</h3>
                <span class="text-xs font-medium px-2.5 py-1 rounded-full bg-slate-100 dark:bg-slate-700 text-[#4c739a]">
                    {{ $violationCategories->count() }} Data
                </span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="border-b border-[#e7edf3] dark:border-slate-700 text-xs uppercase tracking-wider text-[#4c739a] bg-slate-50/50 dark:bg-slate-800/20">
                            <th class="px-6 py-4 font-semibold whitespace-nowrap">Nama Kategori</th>
                            <th class="px-6 py-4 font-semibold whitespace-nowrap text-center">Bobot Poin</th>
                            <th class="px-6 py-4 font-semibold whitespace-nowrap text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#e7edf3] dark:divide-slate-700">
                           @forelse($violationCategories as $category)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors group">
                                <td class="px-6 py-4">
                                    <span class="font-semibold text-[#0d141b] dark:text-white">{{ $category->name }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-purple-50 text-purple-700">
                                        {{ $category->points }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div
                                        class="flex items-center justify-end gap-2 opacit-0 group-hover:opacity-100 transition-opacity">
                                        <button
                                            onclick="openEditModal('{{ $category->id }}', '{{ $category->name }}', '{{ $category->points }}')"
                                            class="p-2 text-[#4c739a] hover:text-blue-600 transition-colors rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20"
                                            title="Edit">
                                            <span class="material-symbols-outlined text-[20px]">edit</span>
                                        </button>
                                        <form action="{{ route('violation-categories.destroy', $category->id) }}" method="POST"
                                            class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                @click.prevent="$store.deleteModal.open($el.closest('form'), 'Yakin ingin menghapus kategori {{ $category->name }}?')"
                                                type="button"
                                                class="p-2 text-[#4c739a] hover:text-red-600 transition-colors rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20"
                                                title="Hapus">
                                                <span class="material-symbols-outlined text-[20px]">delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-[#4c739a]">
                                    <div class="flex flex-col items-center justify-center">
                                        <div
                                            class="w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mb-4">
                                            <span class="material-symbols-outlined text-3xl opacity-50">category</span>
                                        </div>
                                        <p class="font-medium text-lg text-[#0d141b] dark:text-white mb-1">Belum ada data
                                            kategori</p>
                                        <p class="text-sm mb-4">Silakan tambahkan kategori pelanggaran baru</p>
                                        <button onclick="openModal('createModal')"
                                            class="text-primary hover:underline text-sm font-medium">Tambah Kategori</button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-[#e7edf3] dark:border-slate-700">
                {{ $violationCategories->links() }}
            </div>
        </div>
    </div>

    {{-- Create Modal --}}
    <x-modal id="createModal" title="Tambah Kategori Pelanggaran">
        <form action="{{ route('violation-categories.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Nama Kategori</label>
                <input type="text" name="name" required
                    class="w-full rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:ring-primary focus:border-primary">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Bobot Poin</label>
                <input type="number" name="points" required min="0" value="0"
                    class="w-full rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:ring-primary focus:border-primary">
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeModal('createModal')"
                    class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-600 dark:hover:bg-slate-700">
                    Batal
                </button>
                <button type="submit"
                    class="px-4 py-2 text-sm font-medium text-white bg-primary rounded-lg hover:bg-primary/90">
                    Simpan
                </button>
            </div>
        </form>
    </x-modal>

    {{-- Edit Modal --}}
    <x-modal id="editModal" title="Edit Kategori Pelanggaran">
        <form id="editForm" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Nama Kategori</label>
                <input type="text" name="name" id="edit_nama" required
                    class="w-full rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:ring-primary focus:border-primary">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Bobot Poin</label>
                <input type="number" name="points" id="edit_bobot_poin" required min="0" value="0"
                    class="w-full rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:ring-primary focus:border-primary">
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeModal('editModal')"
                    class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-600 dark:hover:bg-slate-700">
                    Batal
                </button>
                <button type="submit"
                    class="px-4 py-2 text-sm font-medium text-white bg-primary rounded-lg hover:bg-primary/90">
                    Update
                </button>
            </div>
        </form>
    </x-modal>

    <script>
        function openEditModal(id, nama, bobot) {
            document.getElementById('editForm').action = '/violation-categories/' + id;
            document.getElementById('edit_nama').value = nama;
            document.getElementById('edit_bobot_poin').value = bobot;
            openModal('editModal');
        }
    </script>
@endsection