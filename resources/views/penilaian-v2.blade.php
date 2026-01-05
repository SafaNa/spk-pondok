@extends('layouts.app-v2-sidebar')

@section('title', 'Daftar Penilaian - Santri Admin')
@section('mobile_title', 'Penilaian')
@section('breadcrumb', 'Daftar Penilaian')

@section('content')
    <!-- Page Header & Main Actions -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="flex flex-col gap-2">
            <h1 class="text-[#0d141b] dark:text-white text-3xl font-black leading-tight tracking-[-0.033em]">Manajemen
                Penilaian Santri</h1>
            <p class="text-[#4c739a] text-base font-normal">
                Daftar hasil penilaian santri berdasarkan metode SAW untuk rekomendasi kepulangan.
            </p>
        </div>
        <button
            class="flex shrink-0 cursor-pointer items-center justify-center gap-2 overflow-hidden rounded-lg h-10 px-5 bg-primary hover:bg-blue-600 text-white text-sm font-bold leading-normal tracking-[0.015em] transition-all shadow-sm hover:shadow-md">
            <span class="material-symbols-outlined text-[20px]">add</span>
            <span class="truncate">Tambah Penilaian</span>
        </button>
    </div>

    <!-- Filters & Search Card -->
    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-[#e7edf3] dark:border-slate-700 p-5">
        <div class="flex flex-col lg:flex-row gap-4 justify-between items-end lg:items-center">
            <div class="flex flex-col md:flex-row gap-4 w-full lg:w-auto">
                <!-- Search -->
                <div class="relative w-full md:w-64">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-[#4c739a]">
                        <span class="material-symbols-outlined">search</span>
                    </div>
                    <input
                        class="block w-full p-2.5 pl-10 text-sm text-[#0d141b] dark:text-white bg-[#f6f7f8] dark:bg-slate-800 border border-[#e7edf3] dark:border-slate-700 rounded-lg focus:ring-primary focus:border-primary placeholder-[#4c739a]"
                        placeholder="Cari nama santri..." type="text" />
                </div>
                <!-- Filter Periode -->
                <div class="relative w-full md:w-48">
                    <select
                        class="block w-full p-2.5 text-sm text-[#0d141b] dark:text-white bg-[#f6f7f8] dark:bg-slate-800 border border-[#e7edf3] dark:border-slate-700 rounded-lg focus:ring-primary focus:border-primary appearance-none cursor-pointer">
                        <option selected value="">Semua Periode</option>
                        <option value="genap2024">Sem. Genap 2023/2024</option>
                        <option value="ganjil2023">Sem. Ganjil 2023/2024</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-[#4c739a]">
                        <span class="material-symbols-outlined">expand_more</span>
                    </div>
                </div>
                <!-- Filter Santri -->
                <div class="relative w-full md:w-48">
                    <select
                        class="block w-full p-2.5 text-sm text-[#0d141b] dark:text-white bg-[#f6f7f8] dark:bg-slate-800 border border-[#e7edf3] dark:border-slate-700 rounded-lg focus:ring-primary focus:border-primary appearance-none cursor-pointer">
                        <option selected value="">Semua Santri</option>
                        <option value="1">Abdullah</option>
                        <option value="2">Ahmad</option>
                        <option value="3">Budi</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-[#4c739a]">
                        <span class="material-symbols-outlined">expand_more</span>
                    </div>
                </div>
            </div>
            <!-- Apply Filter Button -->
            <button
                class="w-full lg:w-auto h-[42px] px-4 bg-[#f6f7f8] dark:bg-slate-800 border border-[#e7edf3] dark:border-slate-700 hover:border-primary dark:hover:border-primary text-[#0d141b] dark:text-white rounded-lg text-sm font-medium flex items-center justify-center gap-2 transition-colors">
                <span class="material-symbols-outlined text-[20px]">filter_list</span>
                Filter
            </button>
        </div>
    </div>

    <!-- Data Table Section -->
    <div
        class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-[#e7edf3] dark:border-slate-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-[#4c739a]">
                <thead
                    class="text-xs uppercase bg-[#f6f7f8] dark:bg-slate-800 text-[#0d141b] dark:text-white border-b border-[#e7edf3] dark:border-slate-700">
                    <tr>
                        <th class="px-6 py-4 font-bold tracking-wider" scope="col">Santri Name</th>
                        <th class="px-6 py-4 font-bold tracking-wider" scope="col">Kriteria</th>
                        <th class="px-6 py-4 font-bold tracking-wider" scope="col">Periode</th>
                        <th class="px-6 py-4 font-bold tracking-wider" scope="col">Nilai (SAW)</th>
                        <th class="px-6 py-4 font-bold tracking-wider text-right" scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#e7edf3] dark:divide-slate-700">
                    <!-- Row 1 -->
                    <tr
                        class="bg-white dark:bg-slate-900 hover:bg-[#f6f7f8] dark:hover:bg-slate-800 transition-colors group">
                        <td class="px-6 py-4 font-medium text-[#0d141b] dark:text-white whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div
                                    class="h-8 w-8 rounded-full bg-primary/20 text-primary flex items-center justify-center text-xs font-bold">
                                    AF</div>
                                <span>Abdullah Fulan</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col gap-1">
                                <span class="text-xs font-semibold text-[#0d141b] dark:text-white">Kedisiplinan (C1)</span>
                                <span class="text-xs opacity-75">Hafalan (C2)</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span
                                class="inline-flex items-center rounded-md bg-blue-50 dark:bg-blue-900/30 px-2 py-1 text-xs font-medium text-blue-700 dark:text-blue-300 ring-1 ring-inset ring-blue-700/10 dark:ring-blue-700/30">
                                Genap 2023/2024
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-12 bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
                                    <div class="bg-emerald-500 h-1.5 rounded-full" style="width: 85%"></div>
                                </div>
                                <span class="text-emerald-600 dark:text-emerald-400 font-bold">0.85</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button
                                    class="p-2 text-[#4c739a] hover:text-primary bg-transparent hover:bg-primary/10 rounded-lg transition-colors"
                                    title="Edit">
                                    <span class="material-symbols-outlined text-[20px]">edit</span>
                                </button>
                                <button
                                    class="p-2 text-[#4c739a] hover:text-red-600 bg-transparent hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors"
                                    title="Delete">
                                    <span class="material-symbols-outlined text-[20px]">delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <!-- Row 2 -->
                    <tr
                        class="bg-white dark:bg-slate-900 hover:bg-[#f6f7f8] dark:hover:bg-slate-800 transition-colors group">
                        <td class="px-6 py-4 font-medium text-[#0d141b] dark:text-white whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div
                                    class="h-8 w-8 rounded-full bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 flex items-center justify-center text-xs font-bold">
                                    AZ</div>
                                <span>Ahmad Zaki</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col gap-1">
                                <span class="text-xs font-semibold text-[#0d141b] dark:text-white">Kedisiplinan (C1)</span>
                                <span class="text-xs opacity-75">Kebersihan (C3)</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span
                                class="inline-flex items-center rounded-md bg-blue-50 dark:bg-blue-900/30 px-2 py-1 text-xs font-medium text-blue-700 dark:text-blue-300 ring-1 ring-inset ring-blue-700/10 dark:ring-blue-700/30">
                                Genap 2023/2024
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-12 bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
                                    <div class="bg-yellow-500 h-1.5 rounded-full" style="width: 65%"></div>
                                </div>
                                <span class="text-yellow-600 dark:text-yellow-400 font-bold">0.65</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button
                                    class="p-2 text-[#4c739a] hover:text-primary bg-transparent hover:bg-primary/10 rounded-lg transition-colors"
                                    title="Edit">
                                    <span class="material-symbols-outlined text-[20px]">edit</span>
                                </button>
                                <button
                                    class="p-2 text-[#4c739a] hover:text-red-600 bg-transparent hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors"
                                    title="Delete">
                                    <span class="material-symbols-outlined text-[20px]">delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <!-- Row 3 -->
                    <tr
                        class="bg-white dark:bg-slate-900 hover:bg-[#f6f7f8] dark:hover:bg-slate-800 transition-colors group">
                        <td class="px-6 py-4 font-medium text-[#0d141b] dark:text-white whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div
                                    class="h-8 w-8 rounded-full bg-orange-100 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400 flex items-center justify-center text-xs font-bold">
                                    BS</div>
                                <span>Budi Santoso</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col gap-1">
                                <span class="text-xs font-semibold text-[#0d141b] dark:text-white">Akhlak (C4)</span>
                                <span class="text-xs opacity-75">Hafalan (C2)</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span
                                class="inline-flex items-center rounded-md bg-gray-100 dark:bg-gray-800 px-2 py-1 text-xs font-medium text-gray-600 dark:text-gray-400 ring-1 ring-inset ring-gray-500/10 dark:ring-gray-500/30">
                                Ganjil 2023/2024
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-12 bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
                                    <div class="bg-red-500 h-1.5 rounded-full" style="width: 42%"></div>
                                </div>
                                <span class="text-red-600 dark:text-red-400 font-bold">0.42</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button
                                    class="p-2 text-[#4c739a] hover:text-primary bg-transparent hover:bg-primary/10 rounded-lg transition-colors"
                                    title="Edit">
                                    <span class="material-symbols-outlined text-[20px]">edit</span>
                                </button>
                                <button
                                    class="p-2 text-[#4c739a] hover:text-red-600 bg-transparent hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors"
                                    title="Delete">
                                    <span class="material-symbols-outlined text-[20px]">delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <!-- Row 4 -->
                    <tr
                        class="bg-white dark:bg-slate-900 hover:bg-[#f6f7f8] dark:hover:bg-slate-800 transition-colors group">
                        <td class="px-6 py-4 font-medium text-[#0d141b] dark:text-white whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div
                                    class="h-8 w-8 rounded-full bg-teal-100 dark:bg-teal-900/30 text-teal-600 dark:text-teal-400 flex items-center justify-center text-xs font-bold">
                                    MI</div>
                                <span>Muhammad Iqbal</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col gap-1">
                                <span class="text-xs font-semibold text-[#0d141b] dark:text-white">Kedisiplinan (C1)</span>
                                <span class="text-xs opacity-75">Hafalan (C2)</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span
                                class="inline-flex items-center rounded-md bg-blue-50 dark:bg-blue-900/30 px-2 py-1 text-xs font-medium text-blue-700 dark:text-blue-300 ring-1 ring-inset ring-blue-700/10 dark:ring-blue-700/30">
                                Genap 2023/2024
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-12 bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
                                    <div class="bg-emerald-500 h-1.5 rounded-full" style="width: 92%"></div>
                                </div>
                                <span class="text-emerald-600 dark:text-emerald-400 font-bold">0.92</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button
                                    class="p-2 text-[#4c739a] hover:text-primary bg-transparent hover:bg-primary/10 rounded-lg transition-colors"
                                    title="Edit">
                                    <span class="material-symbols-outlined text-[20px]">edit</span>
                                </button>
                                <button
                                    class="p-2 text-[#4c739a] hover:text-red-600 bg-transparent hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors"
                                    title="Delete">
                                    <span class="material-symbols-outlined text-[20px]">delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer -->
        <div
            class="bg-white dark:bg-slate-900 px-6 py-4 border-t border-[#e7edf3] dark:border-slate-700 flex flex-col md:flex-row items-center justify-between gap-4">
            <span class="text-sm text-[#4c739a] text-center md:text-left">
                Showing <span class="font-semibold text-[#0d141b] dark:text-white">1</span> to <span
                    class="font-semibold text-[#0d141b] dark:text-white">4</span> of <span
                    class="font-semibold text-[#0d141b] dark:text-white">50</span> entries
            </span>
            <div class="inline-flex -space-x-px text-sm">
                <button
                    class="flex items-center justify-center px-3 h-8 ms-0 leading-tight text-[#4c739a] bg-[#f6f7f8] dark:bg-slate-800 border border-[#e7edf3] dark:border-slate-700 rounded-s-lg hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-[#0d141b] dark:hover:text-white">
                    <span class="material-symbols-outlined text-lg">chevron_left</span>
                </button>
                <button
                    class="flex items-center justify-center px-3 h-8 leading-tight text-white bg-primary border border-primary hover:bg-blue-600">
                    1
                </button>
                <button
                    class="flex items-center justify-center px-3 h-8 leading-tight text-[#4c739a] bg-[#f6f7f8] dark:bg-slate-800 border border-[#e7edf3] dark:border-slate-700 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-[#0d141b] dark:hover:text-white">
                    2
                </button>
                <button
                    class="flex items-center justify-center px-3 h-8 leading-tight text-[#4c739a] bg-[#f6f7f8] dark:bg-slate-800 border border-[#e7edf3] dark:border-slate-700 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-[#0d141b] dark:hover:text-white">
                    3
                </button>
                <button
                    class="flex items-center justify-center px-3 h-8 leading-tight text-[#4c739a] bg-[#f6f7f8] dark:bg-slate-800 border border-[#e7edf3] dark:border-slate-700 rounded-e-lg hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-[#0d141b] dark:hover:text-white">
                    <span class="material-symbols-outlined text-lg">chevron_right</span>
                </button>
            </div>
        </div>
    </div>
@endsection