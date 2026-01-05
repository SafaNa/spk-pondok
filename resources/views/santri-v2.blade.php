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
            <button
                class="flex items-center gap-2 h-10 px-4 rounded-lg border border-[#e7edf3] dark:border-slate-700 bg-white dark:bg-slate-800 text-[#0d141b] dark:text-white text-sm font-medium hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors shadow-sm">
                <span class="material-symbols-outlined text-[20px]">file_upload</span>
                <span class="hidden sm:inline">Import</span>
            </button>
            <button
                class="flex items-center gap-2 h-10 px-4 rounded-lg border border-[#e7edf3] dark:border-slate-700 bg-white dark:bg-slate-800 text-[#0d141b] dark:text-white text-sm font-medium hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors shadow-sm">
                <span class="material-symbols-outlined text-[20px]">file_download</span>
                <span class="hidden sm:inline">Export</span>
            </button>
            <button
                class="flex items-center gap-2 h-10 px-4 rounded-lg bg-primary hover:bg-blue-600 text-white text-sm font-bold shadow-md transition-colors">
                <span class="material-symbols-outlined text-[20px]">add</span>
                <span>Add New Santri</span>
            </button>
        </div>
    </div>

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
                <div class="relative group w-full md:w-auto">
                    <button
                        class="flex w-full md:w-auto items-center justify-between gap-2 h-10 px-3 rounded-lg border border-[#e7edf3] dark:border-slate-700 bg-white dark:bg-slate-800 text-[#4c739a] text-sm font-medium hover:border-primary transition-colors">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[20px]">filter_list</span>
                            <span>Filter Status</span>
                        </div>
                        <span class="material-symbols-outlined text-[18px]">expand_more</span>
                    </button>
                </div>
                <div class="relative group w-full md:w-auto">
                    <button
                        class="flex w-full md:w-auto items-center justify-between gap-2 h-10 px-3 rounded-lg border border-[#e7edf3] dark:border-slate-700 bg-white dark:bg-slate-800 text-[#4c739a] text-sm font-medium hover:border-primary transition-colors">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[20px]">wc</span>
                            <span>Gender</span>
                        </div>
                        <span class="material-symbols-outlined text-[18px]">expand_more</span>
                    </button>
                </div>
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
                    <!-- Row 1 -->
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors group">
                        <td class="p-4 text-sm font-medium text-[#0d141b] dark:text-white">2023001</td>
                        <td class="p-4 text-sm font-medium text-[#0d141b] dark:text-white flex items-center gap-3">
                            <div
                                class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-100 text-blue-600 font-bold text-xs">
                                AF</div>
                            Ahmad Fulan
                        </td>
                        <td class="p-4 text-sm text-[#4c739a]">Laki-laki</td>
                        <td class="p-4">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                Aktif
                            </span>
                        </td>
                        <td class="p-4">
                            <div
                                class="flex items-center justify-center gap-2 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity">
                                <button
                                    class="p-1.5 rounded-md hover:bg-slate-100 dark:hover:bg-slate-700 text-[#4c739a] hover:text-primary transition-colors"
                                    title="View Details">
                                    <span class="material-symbols-outlined text-[20px]">visibility</span>
                                </button>
                                <button
                                    class="p-1.5 rounded-md hover:bg-slate-100 dark:hover:bg-slate-700 text-[#4c739a] hover:text-primary transition-colors"
                                    title="Edit">
                                    <span class="material-symbols-outlined text-[20px]">edit</span>
                                </button>
                                <button
                                    class="p-1.5 rounded-md hover:bg-red-50 dark:hover:bg-red-900/20 text-[#4c739a] hover:text-red-600 transition-colors"
                                    title="Delete">
                                    <span class="material-symbols-outlined text-[20px]">delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <!-- Row 2 -->
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors group">
                        <td class="p-4 text-sm font-medium text-[#0d141b] dark:text-white">2023002</td>
                        <td class="p-4 text-sm font-medium text-[#0d141b] dark:text-white flex items-center gap-3">
                            <div
                                class="flex h-8 w-8 items-center justify-center rounded-full bg-pink-100 text-pink-600 font-bold text-xs">
                                SA</div>
                            Siti Aminah
                        </td>
                        <td class="p-4 text-sm text-[#4c739a]">Perempuan</td>
                        <td class="p-4">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                Aktif
                            </span>
                        </td>
                        <td class="p-4">
                            <div
                                class="flex items-center justify-center gap-2 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity">
                                <button
                                    class="p-1.5 rounded-md hover:bg-slate-100 dark:hover:bg-slate-700 text-[#4c739a] hover:text-primary transition-colors"
                                    title="View Details">
                                    <span class="material-symbols-outlined text-[20px]">visibility</span>
                                </button>
                                <button
                                    class="p-1.5 rounded-md hover:bg-slate-100 dark:hover:bg-slate-700 text-[#4c739a] hover:text-primary transition-colors"
                                    title="Edit">
                                    <span class="material-symbols-outlined text-[20px]">edit</span>
                                </button>
                                <button
                                    class="p-1.5 rounded-md hover:bg-red-50 dark:hover:bg-red-900/20 text-[#4c739a] hover:text-red-600 transition-colors"
                                    title="Delete">
                                    <span class="material-symbols-outlined text-[20px]">delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <!-- Row 3 -->
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors group">
                        <td class="p-4 text-sm font-medium text-[#0d141b] dark:text-white">2023005</td>
                        <td class="p-4 text-sm font-medium text-[#0d141b] dark:text-white flex items-center gap-3">
                            <div
                                class="flex h-8 w-8 items-center justify-center rounded-full bg-amber-100 text-amber-600 font-bold text-xs">
                                BS</div>
                            Budi Santoso
                        </td>
                        <td class="p-4 text-sm text-[#4c739a]">Laki-laki</td>
                        <td class="p-4">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                                Cuti
                            </span>
                        </td>
                        <td class="p-4">
                            <div
                                class="flex items-center justify-center gap-2 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity">
                                <button
                                    class="p-1.5 rounded-md hover:bg-slate-100 dark:hover:bg-slate-700 text-[#4c739a] hover:text-primary transition-colors"
                                    title="View Details">
                                    <span class="material-symbols-outlined text-[20px]">visibility</span>
                                </button>
                                <button
                                    class="p-1.5 rounded-md hover:bg-slate-100 dark:hover:bg-slate-700 text-[#4c739a] hover:text-primary transition-colors"
                                    title="Edit">
                                    <span class="material-symbols-outlined text-[20px]">edit</span>
                                </button>
                                <button
                                    class="p-1.5 rounded-md hover:bg-red-50 dark:hover:bg-red-900/20 text-[#4c739a] hover:text-red-600 transition-colors"
                                    title="Delete">
                                    <span class="material-symbols-outlined text-[20px]">delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <!-- Row 4 -->
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors group">
                        <td class="p-4 text-sm font-medium text-[#0d141b] dark:text-white">2023008</td>
                        <td class="p-4 text-sm font-medium text-[#0d141b] dark:text-white flex items-center gap-3">
                            <div
                                class="flex h-8 w-8 items-center justify-center rounded-full bg-rose-100 text-rose-600 font-bold text-xs">
                                DL</div>
                            Dewi Lestari
                        </td>
                        <td class="p-4 text-sm text-[#4c739a]">Perempuan</td>
                        <td class="p-4">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                Tidak Aktif
                            </span>
                        </td>
                        <td class="p-4">
                            <div
                                class="flex items-center justify-center gap-2 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity">
                                <button
                                    class="p-1.5 rounded-md hover:bg-slate-100 dark:hover:bg-slate-700 text-[#4c739a] hover:text-primary transition-colors"
                                    title="View Details">
                                    <span class="material-symbols-outlined text-[20px]">visibility</span>
                                </button>
                                <button
                                    class="p-1.5 rounded-md hover:bg-slate-100 dark:hover:bg-slate-700 text-[#4c739a] hover:text-primary transition-colors"
                                    title="Edit">
                                    <span class="material-symbols-outlined text-[20px]">edit</span>
                                </button>
                                <button
                                    class="p-1.5 rounded-md hover:bg-red-50 dark:hover:bg-red-900/20 text-[#4c739a] hover:text-red-600 transition-colors"
                                    title="Delete">
                                    <span class="material-symbols-outlined text-[20px]">delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <!-- Row 5 -->
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors group">
                        <td class="p-4 text-sm font-medium text-[#0d141b] dark:text-white">2023010</td>
                        <td class="p-4 text-sm font-medium text-[#0d141b] dark:text-white flex items-center gap-3">
                            <div
                                class="flex h-8 w-8 items-center justify-center rounded-full bg-indigo-100 text-indigo-600 font-bold text-xs">
                                MI</div>
                            Muhammad Ilham
                        </td>
                        <td class="p-4 text-sm text-[#4c739a]">Laki-laki</td>
                        <td class="p-4">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                Aktif
                            </span>
                        </td>
                        <td class="p-4">
                            <div
                                class="flex items-center justify-center gap-2 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity">
                                <button
                                    class="p-1.5 rounded-md hover:bg-slate-100 dark:hover:bg-slate-700 text-[#4c739a] hover:text-primary transition-colors"
                                    title="View Details">
                                    <span class="material-symbols-outlined text-[20px]">visibility</span>
                                </button>
                                <button
                                    class="p-1.5 rounded-md hover:bg-slate-100 dark:hover:bg-slate-700 text-[#4c739a] hover:text-primary transition-colors"
                                    title="Edit">
                                    <span class="material-symbols-outlined text-[20px]">edit</span>
                                </button>
                                <button
                                    class="p-1.5 rounded-md hover:bg-red-50 dark:hover:bg-red-900/20 text-[#4c739a] hover:text-red-600 transition-colors"
                                    title="Delete">
                                    <span class="material-symbols-outlined text-[20px]">delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <div
            class="flex flex-col sm:flex-row items-center justify-between gap-4 p-4 border-t border-[#e7edf3] dark:border-slate-800">
            <p class="text-sm text-[#4c739a]">
                Showing <span class="font-medium text-[#0d141b] dark:text-white">1</span> to <span
                    class="font-medium text-[#0d141b] dark:text-white">5</span> of <span
                    class="font-medium text-[#0d141b] dark:text-white">48</span> entries
            </p>
            <div class="flex items-center gap-1">
                <button
                    class="flex items-center justify-center size-9 rounded-lg border border-[#e7edf3] dark:border-slate-700 bg-white dark:bg-slate-800 text-[#4c739a] hover:bg-slate-50 dark:hover:bg-slate-700 disabled:opacity-50">
                    <span class="material-symbols-outlined text-[20px]">chevron_left</span>
                </button>
                <button
                    class="flex items-center justify-center size-9 rounded-lg bg-primary text-white font-medium text-sm">1</button>
                <button
                    class="flex items-center justify-center size-9 rounded-lg border border-[#e7edf3] dark:border-slate-700 bg-white dark:bg-slate-800 text-[#4c739a] hover:bg-slate-50 dark:hover:bg-slate-700 font-medium text-sm">2</button>
                <button
                    class="flex items-center justify-center size-9 rounded-lg border border-[#e7edf3] dark:border-slate-700 bg-white dark:bg-slate-800 text-[#4c739a] hover:bg-slate-50 dark:hover:bg-slate-700 font-medium text-sm">3</button>
                <span class="flex items-center justify-center size-9 text-[#4c739a]">...</span>
                <button
                    class="flex items-center justify-center size-9 rounded-lg border border-[#e7edf3] dark:border-slate-700 bg-white dark:bg-slate-800 text-[#4c739a] hover:bg-slate-50 dark:hover:bg-slate-700 font-medium text-sm">10</button>
                <button
                    class="flex items-center justify-center size-9 rounded-lg border border-[#e7edf3] dark:border-slate-700 bg-white dark:bg-slate-800 text-[#4c739a] hover:bg-slate-50 dark:hover:bg-slate-700">
                    <span class="material-symbols-outlined text-[20px]">chevron_right</span>
                </button>
            </div>
        </div>
    </div>
@endsection