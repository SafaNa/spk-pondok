@extends('layouts.app')

@section('title', 'Periode Penilaian - Santri Admin')
@section('mobile_title', 'Periode')
@section('breadcrumb', 'Periode Penilaian')

@section('content')
    <!-- Page Heading -->
    <!-- Page Heading -->
    <div class="rounded-2xl p-4 sm:p-6 border border-blue-100 mb-6"
        style="background: linear-gradient(135deg, #eff6ffff 20%, #eef2ffb3 50%, #faf5ff99 80%);">
        <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-4">
            <div class="flex flex-col gap-1">
                <h1 class="text-[#0d141b] dark:text-white text-2xl font-black tracking-tight">
                    Periode Penilaian</h1>
                <p class="text-[#4c739a] text-sm sm:text-base font-normal">
                    Kelola siklus penilaian kelayakan mudik santri. Periode aktif akan digunakan sebagai acuan perhitungan
                    metode SAW saat ini.
                </p>
            </div>
            <button onclick="openModal('addModal')"
                class="group flex cursor-pointer items-center justify-center gap-2 rounded-xl h-11 px-6 bg-primary hover:bg-primary/90 hover:shadow-xl hover:shadow-primary/30 transition-all text-white text-sm font-bold shadow-lg transform hover:-translate-y-0.5 whitespace-nowrap">
                <span
                    class="material-symbols-outlined text-[20px] group-hover:rotate-90 transition-transform duration-300">add</span>
                <span>Tambah Periode</span>
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

    <!-- Active Period Section -->
    <div class="flex flex-col gap-4">
        <h2 class="text-[#0d141b] dark:text-white text-[20px] font-bold leading-tight">Periode Aktif</h2>
        @if($activePeriode)
            <div
                class="bg-white dark:bg-slate-800 rounded-xl p-6 border border-primary/20 shadow-[0_4px_20px_-4px_rgba(19,127,236,0.1)] relative overflow-hidden group">
                <div
                    class="absolute -right-10 -top-10 w-40 h-40 bg-primary/5 rounded-full blur-3xl group-hover:bg-primary/10 transition-all">
                </div>

                <div class="flex flex-col md:flex-row gap-6 items-start md:items-center justify-between relative z-10">
                    <div class="flex flex-col gap-3">
                        <div class="flex items-center gap-3">
                            <span
                                class="inline-flex items-center gap-1 rounded-full bg-green-100 dark:bg-green-900/30 px-2.5 py-0.5 text-xs font-semibold text-green-700 dark:text-green-400 border border-green-200 dark:border-green-800">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                                Aktif
                            </span>
                        </div>
                        <div>
                            <h3 class="text-[#0d141b] dark:text-white text-xl md:text-2xl font-bold leading-tight mb-1">
                                {{ $activePeriode->name }}
                            </h3>
                            @if($activePeriode->description)
                                <p class="text-[#4c739a] text-sm max-w-xl">{{ $activePeriode->description }}</p>
                            @endif
                        </div>
                    </div>

                    <button
                        onclick="openEditModal('{{ $activePeriode->id }}', '{{ $activePeriode->name }}', '{{ $activePeriode->description }}')"
                        class="flex items-center justify-center gap-2 rounded-lg h-10 px-4 bg-white dark:bg-slate-700 border border-[#e7edf3] dark:border-slate-600 text-[#0d141b] dark:text-white text-sm font-medium hover:bg-slate-50 dark:hover:bg-slate-600 hover:text-primary transition-all">
                        <span class="material-symbols-outlined text-[18px]">edit</span>
                        Edit
                    </button>
                </div>
            </div>
        @else
            <div
                class="bg-slate-50 dark:bg-slate-800 rounded-xl p-6 border-2 border-dashed border-slate-200 dark:border-slate-700 text-center">
                <span class="material-symbols-outlined text-4xl text-[#4c739a] mb-2">event_busy</span>
                <p class="text-[#4c739a]">Belum ada periode aktif. Pilih salah satu periode dari riwayat dan aktifkan.</p>
            </div>
        @endif
    </div>

    <!-- History Section -->
    <div class="flex flex-col gap-4">
        <div class="flex items-center justify-between">
            <h2 class="text-[#0d141b] dark:text-white text-[20px] font-bold leading-tight">Riwayat Periode</h2>
        </div>

        <div
            class="bg-white dark:bg-slate-800 rounded-xl border border-[#e7edf3] dark:border-slate-700 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-slate-50 dark:bg-slate-700/50 border-b border-[#e7edf3] dark:border-slate-700 text-xs uppercase tracking-wider text-[#4c739a] font-semibold">
                            <th class="px-6 py-4 whitespace-nowrap w-20">No</th>
                            <th class="px-6 py-4 whitespace-nowrap">Nama Periode</th>
                            <th class="px-6 py-4 whitespace-nowrap">Keterangan</th>
                            <th class="px-6 py-4 text-center whitespace-nowrap">Status</th>
                            <th class="px-6 py-4 text-right whitespace-nowrap">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        @forelse($periods as $periode)
                            <tr class="group hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors">
                                <td class="px-6 py-4 text-sm text-[#4c739a]">
                                    @if(method_exists($periods, 'firstItem'))
                                        {{ $periods->firstItem() + $loop->index }}
                                    @else
                                        {{ $loop->iteration }}
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-bold text-[#0d141b] dark:text-white">{{ $periode->name }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="text-sm text-[#4c739a]">{{ $periode->description ? Str::limit($periode->description, 50) : '-' }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="inline-flex items-center rounded-full {{ $periode->is_active ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 border border-green-200 dark:border-green-800' : 'bg-slate-100 dark:bg-slate-700 text-[#4c739a]' }} px-2.5 py-0.5 text-xs font-medium">
                                        {{ $periode->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        @if(!$periode->is_active)
                                            <form action="{{ route('periods.activate', $periode) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit"
                                                    class="text-xs font-semibold text-primary hover:text-primary/80 hover:underline px-2">
                                                    Aktifkan
                                                </button>
                                            </form>
                                        @endif
                                        <button
                                            onclick="openEditModal('{{ $periode->id }}', '{{ $periode->name }}', '{{ $periode->description }}')"
                                            class="p-1.5 hover:bg-slate-200 dark:hover:bg-slate-600 rounded text-[#4c739a] hover:text-primary transition-colors">
                                            <span class="material-symbols-outlined text-[18px]">edit</span>
                                        </button>
                                        <form action="{{ route('periods.destroy', $periode->id) }}" method="POST"
                                            class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                @click.prevent="$store.deleteModal.open($el.closest('form'), 'Yakin ingin menghapus periode {{ $periode->nama }}?')"
                                                type="button"
                                                class="p-1.5 hover:bg-red-50 dark:hover:bg-red-900/20 rounded text-[#4c739a] hover:text-red-600 transition-colors">
                                                <span class="material-symbols-outlined text-[18px]">delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-[#4c739a]">
                                    <span class="material-symbols-outlined text-3xl mb-2">inbox</span>
                                    <p>Belum ada periode</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($periods->hasPages())
                <div class="px-6 py-4 border-t border-[#e7edf3] dark:border-slate-700 flex items-center justify-between">
                    <span class="text-sm text-[#4c739a]">Menampilkan {{ $periods->firstItem() }}-{{ $periods->lastItem() }}
                        dari {{ $periods->total() }} periode</span>
                    <div class="flex gap-2">
                        @if($periods->onFirstPage())
                            <button
                                class="px-3 py-1 text-sm rounded border border-[#e7edf3] dark:border-slate-700 text-[#4c739a] disabled:opacity-50"
                                disabled>Sebelumnya</button>
                        @else
                            <a href="{{ $periods->previousPageUrl() }}"
                                class="px-3 py-1 text-sm rounded border border-[#e7edf3] dark:border-slate-700 text-[#0d141b] dark:text-white hover:bg-slate-50 dark:hover:bg-slate-700">Sebelumnya</a>
                        @endif
                        @if($periods->hasMorePages())
                            <a href="{{ $periods->nextPageUrl() }}"
                                class="px-3 py-1 text-sm rounded border border-[#e7edf3] dark:border-slate-700 text-[#0d141b] dark:text-white hover:bg-slate-50 dark:hover:bg-slate-700">Selanjutnya</a>
                        @else
                            <button
                                class="px-3 py-1 text-sm rounded border border-[#e7edf3] dark:border-slate-700 text-[#4c739a] disabled:opacity-50"
                                disabled>Selanjutnya</button>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Add Modal -->
    <div id="addModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center">
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-xl w-full max-w-lg mx-4 overflow-hidden">
            <div class="px-6 py-4 border-b border-[#e7edf3] dark:border-slate-700 flex items-center justify-between">
                <h3 class="text-lg font-bold text-[#0d141b] dark:text-white">Tambah Periode Baru</h3>
                <button onclick="closeModal('addModal')" class="text-[#4c739a] hover:text-[#0d141b]">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form action="{{ route('periods.store') }}" method="POST">
                @csrf
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-[#0d141b] dark:text-white mb-1">Nama Periode <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="name" required
                            class="w-full rounded-lg border-[#e7edf3] dark:border-slate-600 bg-white dark:bg-slate-700 text-[#0d141b] dark:text-white focus:border-primary focus:ring-primary"
                            placeholder="Contoh: Mudik Idul Fitri 1445H">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#0d141b] dark:text-white mb-1">Keterangan</label>
                        <textarea name="description" rows="3"
                            class="w-full rounded-lg border-[#e7edf3] dark:border-slate-600 bg-white dark:bg-slate-700 text-[#0d141b] dark:text-white focus:border-primary focus:ring-primary"
                            placeholder="Deskripsi singkat periode..."></textarea>
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
                <h3 class="text-lg font-bold text-[#0d141b] dark:text-white">Edit Periode</h3>
                <button onclick="closeModal('editModal')" class="text-[#4c739a] hover:text-[#0d141b]">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-[#0d141b] dark:text-white mb-1">Nama Periode <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="name" id="edit_name" required
                            class="w-full rounded-lg border-[#e7edf3] dark:border-slate-600 bg-white dark:bg-slate-700 text-[#0d141b] dark:text-white focus:border-primary focus:ring-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#0d141b] dark:text-white mb-1">Keterangan</label>
                        <textarea name="description" id="edit_description" rows="3"
                            class="w-full rounded-lg border-[#e7edf3] dark:border-slate-600 bg-white dark:bg-slate-700 text-[#0d141b] dark:text-white focus:border-primary focus:ring-primary"></textarea>
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

        function openEditModal(id, name, description) {
            document.getElementById('editForm').action = '/periods/' + id;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_description').value = description || '';
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