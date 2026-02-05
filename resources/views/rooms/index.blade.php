@extends('layouts.app')

@section('title', 'Data Kamar - Santri Admin')
@section('mobile_title', 'Kamar')
@section('breadcrumb', 'Data Kamar')

@section('content')
    <!-- Page Heading -->
    <div class="rounded-2xl p-4 sm:p-6 border border-blue-100 mb-6"
        style="background: linear-gradient(135deg, #eff6ffff 20%, #eef2ffb3 50%, #faf5ff99 80%);">
        <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-4">
            <div class="flex flex-col gap-1">
                <h1 class="text-[#0d141b] dark:text-white text-2xl font-black tracking-tight">
                    Data Kamar</h1>
                <p class="text-[#4c739a] text-sm sm:text-base font-normal">
                    Kelola data kamar asrama santri beserta kapasitasnya.
                </p>
            </div>
            <button onclick="openModal('addModal')"
                class="group flex cursor-pointer items-center justify-center gap-2 rounded-xl h-11 px-6 bg-primary hover:bg-primary/90 hover:shadow-xl hover:shadow-primary/30 transition-all text-white text-sm font-bold shadow-lg transform hover:-translate-y-0.5 whitespace-nowrap">
                <span
                    class="material-symbols-outlined text-[20px] group-hover:rotate-90 transition-transform duration-300">add</span>
                <span>Tambah Kamar</span>
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
                            <th class="px-6 py-4 whitespace-nowrap">Nama Kamar</th>
                            <th class="px-6 py-4 whitespace-nowrap">Rayon</th>
                            <th class="px-6 py-4 whitespace-nowrap">Kapasitas</th>
                            <th class="px-6 py-4 text-right whitespace-nowrap">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        @forelse($rooms as $room)
                            <tr class="group hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors">
                                <td class="px-6 py-4 text-sm text-[#4c739a]">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-bold text-[#0d141b] dark:text-white">{{ $room->name }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-[#4c739a]">{{ $room->rayon?->name ?? '-' }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <span class="material-symbols-outlined text-[18px] text-[#4c739a]">group</span>
                                        <span class="text-sm text-[#0d141b] dark:text-white">{{ $room->capacity }} Santri</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button
                                            onclick="openEditModal('{{ $room->id }}', '{{ $room->name }}', '{{ $room->rayon_id }}', '{{ $room->capacity }}')"
                                            class="p-1.5 hover:bg-slate-200 dark:hover:bg-slate-600 rounded text-[#4c739a] hover:text-primary transition-colors">
                                            <span class="material-symbols-outlined text-[18px]">edit</span>
                                        </button>

                                        <div x-data>
                                            <form action="{{ route('rooms.destroy', $room->id) }}" method="POST"
                                                class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    @click.prevent="$store.deleteModal.open($el.closest('form'), 'Yakin ingin menghapus kamar {{ $room->name }}?')"
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
                                    <span class="material-symbols-outlined text-3xl mb-2">hotel</span>
                                    <p>Belum ada data kamar</p>
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
                <h3 class="text-lg font-bold text-[#0d141b] dark:text-white">Tambah Kamar Baru</h3>
                <button onclick="closeModal('addModal')" class="text-[#4c739a] hover:text-[#0d141b]">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form action="{{ route('rooms.store') }}" method="POST">
                @csrf
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-[#0d141b] dark:text-white mb-1">Nama Kamar <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="name" required
                            class="w-full rounded-lg border-[#e7edf3] dark:border-slate-600 bg-white dark:bg-slate-700 text-[#0d141b] dark:text-white focus:border-primary focus:ring-primary"
                            placeholder="Contoh: Kamar 1">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#0d141b] dark:text-white mb-1">Rayon</label>
                        <select name="rayon_id"
                            class="w-full rounded-lg border-[#e7edf3] dark:border-slate-600 bg-white dark:bg-slate-700 text-[#0d141b] dark:text-white focus:border-primary focus:ring-primary">
                            <option value="">Pilih Rayon</option>
                            @foreach ($rayons as $rayon)
                                <option value="{{ $rayon->id }}">{{ $rayon->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#0d141b] dark:text-white mb-1">Kapasitas <span
                                class="text-red-500">*</span></label>
                        <input type="number" name="capacity" required min="0"
                            class="w-full rounded-lg border-[#e7edf3] dark:border-slate-600 bg-white dark:bg-slate-700 text-[#0d141b] dark:text-white focus:border-primary focus:ring-primary"
                            placeholder="0">
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
                <h3 class="text-lg font-bold text-[#0d141b] dark:text-white">Edit Kamar</h3>
                <button onclick="closeModal('editModal')" class="text-[#4c739a] hover:text-[#0d141b]">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-[#0d141b] dark:text-white mb-1">Nama Kamar <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="name" id="edit_name" required
                            class="w-full rounded-lg border-[#e7edf3] dark:border-slate-600 bg-white dark:bg-slate-700 text-[#0d141b] dark:text-white focus:border-primary focus:ring-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#0d141b] dark:text-white mb-1">Rayon</label>
                        <select name="rayon_id" id="edit_rayon_id"
                            class="w-full rounded-lg border-[#e7edf3] dark:border-slate-600 bg-white dark:bg-slate-700 text-[#0d141b] dark:text-white focus:border-primary focus:ring-primary">
                            <option value="">Pilih Rayon</option>
                            @foreach ($rayons as $rayon)
                                <option value="{{ $rayon->id }}">{{ $rayon->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#0d141b] dark:text-white mb-1">Kapasitas <span
                                class="text-red-500">*</span></label>
                        <input type="number" name="capacity" id="edit_capacity" required min="0"
                            class="w-full rounded-lg border-[#e7edf3] dark:border-slate-600 bg-white dark:bg-slate-700 text-[#0d141b] dark:text-white focus:border-primary focus:ring-primary">
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

        function openEditModal(id, name, rayon_id, capacity) {
            document.getElementById('editForm').action = '/rooms/' + id;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_rayon_id').value = rayon_id || '';
            document.getElementById('edit_capacity').value = capacity;
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