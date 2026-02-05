@extends('layouts.app')

@section('title', 'Jenjang Pendidikan - Santri Admin')
@section('mobile_title', 'Jenjang')
@section('breadcrumb', 'Jenjang Pendidikan')

@section('content')
    <!-- Page Heading -->
    <div class="rounded-2xl p-4 sm:p-6 border border-blue-100 mb-6"
        style="background: linear-gradient(135deg, #eff6ffff 20%, #eef2ffb3 50%, #faf5ff99 80%);">
        <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-4">
            <div class="flex flex-col gap-1">
                <h1 class="text-[#0d141b] dark:text-white text-2xl font-black tracking-tight">
                    Jenjang Pendidikan</h1>
                <p class="text-[#4c739a] text-sm sm:text-base font-normal">
                    Kelola data jenjang pendidikan (Formal & Diniyah) yang tersedia di pondok.
                </p>
            </div>
            <button onclick="openModal('addModal')"
                class="group flex cursor-pointer items-center justify-center gap-2 rounded-xl h-11 px-6 bg-primary hover:bg-primary/90 hover:shadow-xl hover:shadow-primary/30 transition-all text-white text-sm font-bold shadow-lg transform hover:-translate-y-0.5 whitespace-nowrap">
                <span
                    class="material-symbols-outlined text-[20px] group-hover:rotate-90 transition-transform duration-300">add</span>
                <span>Tambah Jenjang</span>
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

    <!-- Data List -->
    <div class="flex flex-col gap-4">
        <div
            class="bg-white dark:bg-slate-800 rounded-xl border border-[#e7edf3] dark:border-slate-700 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-slate-50 dark:bg-slate-700/50 border-b border-[#e7edf3] dark:border-slate-700 text-xs uppercase tracking-wider text-[#4c739a] font-semibold">
                            <th class="px-6 py-4 whitespace-nowrap w-20">No</th>
                            <th class="px-6 py-4 whitespace-nowrap">Nama Jenjang</th>
                            <th class="px-6 py-4 whitespace-nowrap">Tipe</th>
                            <th class="px-6 py-4 text-right whitespace-nowrap">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        @forelse($levels as $level)
                            <tr class="group hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors">
                                <td class="px-6 py-4 text-sm text-[#4c739a]">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-bold text-[#0d141b] dark:text-white">{{ $level->name }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center rounded-full {{ $level->type == 'formal' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400' : 'bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400' }} px-2.5 py-0.5 text-xs font-medium">
                                        {{ ucfirst($level->type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button
                                            onclick="openEditModal('{{ $level->id }}', '{{ $level->name }}', '{{ $level->type }}')"
                                            class="p-1.5 hover:bg-slate-200 dark:hover:bg-slate-600 rounded text-[#4c739a] hover:text-primary transition-colors">
                                            <span class="material-symbols-outlined text-[18px]">edit</span>
                                        </button>

                                        <div x-data>
                                            <form action="{{ route('education-levels.destroy', $level->id) }}" method="POST"
                                                class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    @click.prevent="$store.deleteModal.open($el.closest('form'), 'Yakin ingin menghapus jenjang {{ $level->name }}?')"
                                                    type="button"
                                                    class="p-1.5 hover:bg-red-50 dark:hover:bg-red-900/20 rounded text-[#4c739a] hover:text-red-600 transition-colors">
                                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-[#4c739a]">
                                    <span class="material-symbols-outlined text-3xl mb-2">school</span>
                                    <p>Belum ada data jenjang pendidikan</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <div id="addModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center">
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-xl w-full max-w-lg mx-4 overflow-hidden">
            <div class="px-6 py-4 border-b border-[#e7edf3] dark:border-slate-700 flex items-center justify-between">
                <h3 class="text-lg font-bold text-[#0d141b] dark:text-white">Tambah Jenjang Baru</h3>
                <button onclick="closeModal('addModal')" class="text-[#4c739a] hover:text-[#0d141b]">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form action="{{ route('education-levels.store') }}" method="POST">
                @csrf
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-[#0d141b] dark:text-white mb-1">Nama Jenjang <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="name" required
                            class="w-full rounded-lg border-[#e7edf3] dark:border-slate-600 bg-white dark:bg-slate-700 text-[#0d141b] dark:text-white focus:border-primary focus:ring-primary"
                            placeholder="Contoh: SMP, SMA, Wustha">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#0d141b] dark:text-white mb-1">Tipe <span
                                class="text-red-500">*</span></label>
                        <select name="type" required
                            class="w-full rounded-lg border-[#e7edf3] dark:border-slate-600 bg-white dark:bg-slate-700 text-[#0d141b] dark:text-white focus:border-primary focus:ring-primary">
                            <option value="formal">Formal</option>
                            <option value="religious">Diniyah (Salaf)</option>
                        </select>
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
    <div id="editModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center">
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-xl w-full max-w-lg mx-4 overflow-hidden">
            <div class="px-6 py-4 border-b border-[#e7edf3] dark:border-slate-700 flex items-center justify-between">
                <h3 class="text-lg font-bold text-[#0d141b] dark:text-white">Edit Jenjang</h3>
                <button onclick="closeModal('editModal')" class="text-[#4c739a] hover:text-[#0d141b]">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-[#0d141b] dark:text-white mb-1">Nama Jenjang <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="name" id="edit_name" required
                            class="w-full rounded-lg border-[#e7edf3] dark:border-slate-600 bg-white dark:bg-slate-700 text-[#0d141b] dark:text-white focus:border-primary focus:ring-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#0d141b] dark:text-white mb-1">Tipe <span
                                class="text-red-500">*</span></label>
                        <select name="type" id="edit_type" required
                            class="w-full rounded-lg border-[#e7edf3] dark:border-slate-600 bg-white dark:bg-slate-700 text-[#0d141b] dark:text-white focus:border-primary focus:ring-primary">
                            <option value="formal">Formal</option>
                            <option value="religious">Diniyah (Salaf)</option>
                        </select>
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

        function openEditModal(id, name, type) {
            document.getElementById('editForm').action = '/education-levels/' + id;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_type').value = type;
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