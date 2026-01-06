@extends('layouts.app')

@section('title', 'Data Santri - Santri Admin')
@section('mobile_title', 'Santri Management')
@section('breadcrumb', 'Data Santri')

@section('content')
    <!-- Page Heading -->
    <!-- Page Heading -->
    <div class="rounded-2xl p-4 sm:p-6 border border-blue-100 mb-6"
        style="background: linear-gradient(135deg, #eff6ffff 20%, #eef2ffb3 50%, #faf5ff99 80%);">
        <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-4">
            <div class="flex flex-col gap-1">
                <h1 class="text-[#0d141b] dark:text-white text-2xl font-black tracking-tight">Data Santri</h1>
                <p class="text-[#4c739a] text-sm sm:text-base font-normal">Manage student records for homecoming
                    recommendations</p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <button onclick="toggleImportModal()"
                    class="flex items-center gap-2 h-10 sm:h-11 px-4 rounded-xl border border-[#e7edf3] dark:border-slate-700 bg-white dark:bg-slate-800 text-[#0d141b] dark:text-white text-sm font-medium hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors shadow-sm flex-1 sm:flex-none justify-center">
                    <span class="material-symbols-outlined text-[20px]">upload_file</span>
                    <span>Import</span>
                </button>
                <a href="{{ route('santri.export') }}"
                    class="flex items-center gap-2 h-10 sm:h-11 px-4 rounded-xl border border-[#e7edf3] dark:border-slate-700 bg-white dark:bg-slate-800 text-[#0d141b] dark:text-white text-sm font-medium hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors shadow-sm flex-1 sm:flex-none justify-center">
                    <span class="material-symbols-outlined text-[20px]">file_download</span>
                    <span>Export</span>
                </a>
                <a href="{{ route('santri.create') }}"
                    class="group flex items-center gap-2 h-10 sm:h-11 px-5 rounded-xl bg-primary hover:bg-primary/90 hover:shadow-xl hover:shadow-primary/30 text-white text-sm font-bold shadow-lg transition-all transform hover:-translate-y-0.5 flex-1 sm:flex-none justify-center">
                    <span
                        class="material-symbols-outlined text-[20px] group-hover:rotate-90 transition-transform duration-300">add</span>
                    <span class="whitespace-nowrap">Add New Santri</span>
                </a>
            </div>
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
        <form action="{{ route('santri.index') }}" method="GET"
            class="flex flex-col md:flex-row gap-4 items-center justify-between">
            <!-- Search -->
            <div class="w-full md:w-96">
                <div
                    class="relative flex items-center w-full h-10 rounded-lg focus-within:shadow-lg bg-[#f6f7f8] dark:bg-slate-800 overflow-hidden ring-1 ring-transparent focus-within:ring-primary transition-all">
                    <div class="grid place-items-center h-full w-12 text-[#4c739a]">
                        <span class="material-symbols-outlined">search</span>
                    </div>
                    <input
                        class="peer h-full w-full outline-none text-sm text-gray-700 dark:text-slate-200 pr-2 bg-transparent placeholder-gray-500"
                        name="search" value="{{ request('search') }}" id="search" placeholder="Search by Name or NIS..."
                        type="text" />
                </div>
            </div>
            <!-- Filters -->
            <div class="grid grid-cols-2 w-full md:w-auto gap-3">
                <select name="status" onchange="this.form.submit()"
                    class="h-10 px-3 rounded-lg border border-[#e7edf3] dark:border-slate-700 bg-white dark:bg-slate-800 text-[#4c739a] text-sm font-medium w-full focus:ring-primary focus:border-primary">
                    <option value="">All Status</option>
                    <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="non-aktif" {{ request('status') == 'non-aktif' ? 'selected' : '' }}>Non-Aktif</option>
                    <option value="lulus" {{ request('status') == 'lulus' ? 'selected' : '' }}>Lulus</option>
                    <option value="drop-out" {{ request('status') == 'drop-out' ? 'selected' : '' }}>Drop Out</option>
                </select>
                <select name="gender" onchange="this.form.submit()"
                    class="h-10 px-3 rounded-lg border border-[#e7edf3] dark:border-slate-700 bg-white dark:bg-slate-800 text-[#4c739a] text-sm font-medium w-full focus:ring-primary focus:border-primary">
                    <option value="">All Gender</option>
                    <option value="L" {{ request('gender') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ request('gender') == 'P' ? 'selected' : '' }}>Perempuan</option>
                </select>
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div
        class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-[#e7edf3] dark:border-slate-800 overflow-hidden flex flex-col">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#f8fafc] dark:bg-slate-800/50 border-b border-[#e7edf3] dark:border-slate-700">
                        <th class="p-4 text-xs font-semibold tracking-wide text-[#4c739a] uppercase w-24 whitespace-nowrap">
                            NIS</th>
                        <th class="p-4 text-xs font-semibold tracking-wide text-[#4c739a] uppercase whitespace-nowrap">Nama
                            Lengkap</th>
                        <th class="p-4 text-xs font-semibold tracking-wide text-[#4c739a] uppercase w-32 whitespace-nowrap">
                            Jenis Kelamin</th>
                        <th class="p-4 text-xs font-semibold tracking-wide text-[#4c739a] uppercase w-32 whitespace-nowrap">
                            Status</th>
                        <th
                            class="p-4 text-xs font-semibold tracking-wide text-[#4c739a] uppercase w-40 text-center whitespace-nowrap">
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
                            <td class="p-4 text-sm font-medium text-[#0d141b] dark:text-white whitespace-nowrap">{{ $s->nis }}
                            </td>
                            <td class="p-4 text-sm font-medium text-[#0d141b] dark:text-white flex items-center gap-3">
                                <div
                                    class="flex h-8 w-8 items-center justify-center rounded-full bg-{{ $color }}-100 text-{{ $color }}-600 font-bold text-xs ring-1 ring-{{ $color }}-600/20">
                                    {{ $initials }}
                                </div>
                                <span class="whitespace-nowrap">{{ $s->nama }}</span>
                            </td>
                            <td class="p-4 text-sm text-[#4c739a] whitespace-nowrap">
                                {{ $s->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                            </td>
                            <td class="p-4 whitespace-nowrap">
                                @if($s->status == 'aktif')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 border border-green-200 dark:border-green-800">Aktif</span>
                                @elseif($s->status == 'non-aktif')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 border border-red-200 dark:border-red-800">Non-Aktif</span>
                                @elseif($s->status == 'lulus')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400 border border-blue-200 dark:border-blue-800">Lulus</span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400 border border-yellow-200 dark:border-yellow-800">{{ ucfirst($s->status) }}</span>
                                @endif
                            </td>
                            <td class="p-4 whitespace-nowrap">
                                <div
                                    class="flex items-center justify-center gap-2 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('santri.show', $s) }}"
                                        class="p-1.5 rounded-md hover:bg-slate-100 dark:hover:bg-slate-700 text-[#4c739a] hover:text-primary transition-colors"
                                        title="View Details">
                                        <span class="material-symbols-outlined text-[20px]">visibility</span>
                                    </a>
                                    <a href="{{ route('santri.edit', $s) }}"
                                        class="p-1.5 rounded-md hover:bg-slate-100 dark:hover:bg-slate-700 text-[#4c739a] hover:text-primary transition-colors"
                                        title="Edit">
                                        <span class="material-symbols-outlined text-[20px]">edit</span>
                                    </a>
                                    <form action="{{ route('santri.destroy', $s) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            @click.prevent="$store.deleteModal.open($el.closest('form'), 'Yakin ingin menghapus santri {{ $s->nama }}?')"
                                            type="button"
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

    <!-- Import Modal -->
    <div id="importModal" class="fixed inset-0 z-50 hidden transition-opacity duration-300" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" onclick="toggleImportModal()"></div>

        <!-- Modal Panel -->
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div
                class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-slate-900 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-[#e7edf3] dark:border-slate-800">

                <!-- Modal Header -->
                <div
                    class="bg-white dark:bg-slate-900 px-4 py-4 sm:p-6 sm:pb-4 border-b border-[#e7edf3] dark:border-slate-800">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-10 w-10 items-center justify-center rounded-full bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400">
                                <span class="material-symbols-outlined">upload_file</span>
                            </div>
                            <h3 class="text-lg font-semibold leading-6 text-[#0d141b] dark:text-white" id="modal-title">
                                Import Data Santri
                            </h3>
                        </div>
                        <button onclick="toggleImportModal()"
                            class="text-[#4c739a] hover:text-[#0d141b] dark:hover:text-white transition-colors">
                            <span class="material-symbols-outlined">close</span>
                        </button>
                    </div>
                </div>

                <!-- Modal Body -->
                <form action="{{ route('santri.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="px-4 py-5 sm:p-6 bg-slate-50 dark:bg-slate-900/50">
                        <div class="space-y-4">
                            <p class="text-sm text-[#4c739a]">
                                Upload file Excel (.xlsx, .xls) containing student data. Ensure the columns match the
                                format: <code
                                    class="text-xs bg-slate-200 dark:bg-slate-800 px-1 py-0.5 rounded text-[#0d141b] dark:text-white">nis, nama, jenis_kelamin, ...</code>.
                            </p>

                            <!-- Template Download Link -->
                            <a href="{{ route('santri.template') }}"
                                class="group flex items-center gap-3 p-3 rounded-lg border border-primary/20 bg-primary/5 hover:bg-primary/10 transition-colors">
                                <span class="material-symbols-outlined text-primary">download</span>
                                <div class="flex flex-col">
                                    <span class="text-sm font-semibold text-primary">Download Template Excel</span>
                                    <span class="text-xs text-[#4c739a]">Use this template to format your data
                                        correctly.</span>
                                    <!-- Debug Info -->
                                    <span class="text-[10px] text-red-500 hidden sm:hidden">Route:
                                        {{ route('santri.template') }}</span>
                                </div>
                            </a>

                            <!-- File Input -->
                            <div class="mt-4">
                                <label
                                    class="block text-sm font-medium leading-6 text-[#0d141b] dark:text-white mb-2">Select
                                    Excel File</label>
                                <input type="file" name="file" required
                                    class="block w-full text-sm text-[#4c739a]
                                                            file:mr-4 file:py-2 file:px-4
                                                            file:rounded-full file:border-0
                                                            file:text-xs file:font-semibold
                                                            file:bg-primary file:text-white
                                                            file:cursor-pointer hover:file:bg-blue-600
                                                            cursor-pointer border border-[#e7edf3] dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 focus:outline-none" />
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div
                        class="bg-white dark:bg-slate-900 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 border-t border-[#e7edf3] dark:border-slate-800">
                        <button type="submit"
                            class="inline-flex w-full justify-center rounded-xl bg-primary px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-600 sm:ml-3 sm:w-auto transition-colors">
                            Upload Data
                        </button>
                        <button type="button" onclick="toggleImportModal()"
                            class="mt-3 inline-flex w-full justify-center rounded-xl bg-white dark:bg-slate-800 px-3 py-2 text-sm font-semibold text-[#0d141b] dark:text-white shadow-sm ring-1 ring-inset ring-[#e7edf3] dark:ring-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 sm:mt-0 sm:w-auto transition-colors">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleImportModal() {
            const modal = document.getElementById('importModal');
            if (modal.classList.contains('hidden')) {
                modal.classList.remove('hidden');
                setTimeout(() => {
                    modal.querySelector('.transform').classList.remove('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
                    modal.querySelector('.transform').classList.add('opacity-100', 'translate-y-0', 'sm:scale-100');
                }, 10);
            } else {
                modal.querySelector('.transform').classList.remove('opacity-100', 'translate-y-0', 'sm:scale-100');
                modal.querySelector('.transform').classList.add('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
                setTimeout(() => {
                    modal.classList.add('hidden');
                }, 300);
            }
        }
    </script>
@endsection