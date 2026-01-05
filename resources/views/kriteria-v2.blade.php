@extends('layouts.app-v2-sidebar')

@section('title', 'Kriteria Management - Santri Admin')
@section('mobile_title', 'Kriteria')
@section('breadcrumb', 'Kriteria Management')

@section('content')
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
        <div class="max-w-2xl">
            <h1 class="text-3xl font-bold tracking-tight text-[#0d141b] dark:text-white mb-2">Manajemen Kriteria & Bobot
            </h1>
            <p class="text-[#4c739a]">Define the evaluation criteria for Santri selection. Ensure the total weight
                distribution equals 100% for valid SAW calculation.</p>
        </div>
        <button
            class="flex items-center gap-2 bg-primary hover:bg-blue-600 text-white px-4 py-2.5 rounded-lg text-sm font-semibold shadow-sm transition-all active:scale-95 whitespace-nowrap">
            <span class="material-symbols-outlined text-[20px]">add</span>
            Add New Kriteria
        </button>
    </div>

    <!-- Weight Allocation Progress Bar (Alert) -->
    <div
        class="bg-white dark:bg-slate-900 rounded-xl border border-orange-200 dark:border-orange-900/30 p-5 shadow-sm relative overflow-hidden">
        <div class="absolute top-0 left-0 w-1 h-full bg-orange-500"></div>
        <div class="flex flex-col gap-3 relative z-10">
            <div class="flex justify-between items-end">
                <div>
                    <p class="text-sm font-semibold text-[#0d141b] dark:text-white flex items-center gap-2">
                        <span class="material-symbols-outlined text-orange-500">warning</span>
                        Total Weight Allocation
                    </p>
                    <p class="text-xs text-[#4c739a] mt-1">Target: 100% (15% remaining to be allocated)</p>
                </div>
                <p class="text-2xl font-bold text-[#0d141b] dark:text-white tabular-nums">85%</p>
            </div>
            <div class="h-3 w-full bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                <div class="h-full bg-orange-500 rounded-full transition-all duration-500 ease-out" style="width: 85%">
                </div>
            </div>
        </div>
    </div>

    <!-- Layout Grid: Table & Subkriteria Panel -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        <!-- Left Column: Criteria Table -->
        <div class="lg:col-span-7 flex flex-col gap-6">
            <div
                class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-[#e7edf3] dark:border-slate-700 overflow-hidden">
                <div
                    class="px-6 py-4 border-b border-[#e7edf3] dark:border-slate-700 flex justify-between items-center bg-slate-50/50 dark:bg-slate-800/50">
                    <h3 class="font-semibold text-[#0d141b] dark:text-white">Criteria List</h3>
                    <span
                        class="text-xs font-medium px-2.5 py-1 rounded-full bg-slate-100 dark:bg-slate-700 text-[#4c739a]">4
                        Items</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr
                                class="border-b border-[#e7edf3] dark:border-slate-700 text-xs uppercase tracking-wider text-[#4c739a]">
                                <th class="px-6 py-4 font-semibold w-1/3">Criteria Name</th>
                                <th class="px-6 py-4 font-semibold">Type</th>
                                <th class="px-6 py-4 font-semibold">Weight</th>
                                <th class="px-6 py-4 font-semibold text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#e7edf3] dark:divide-slate-700">
                            <!-- Active Row -->
                            <tr class="bg-primary/5 dark:bg-primary/10 group cursor-pointer transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="size-2 bg-primary rounded-full"></div>
                                        <p class="font-medium text-[#0d141b] dark:text-white text-sm">Nilai Akhlak</p>
                                    </div>
                                    <p class="text-xs text-[#4c739a] ml-5 mt-0.5">Code: C1</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                        Benefit
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-semibold text-[#0d141b] dark:text-white">30%</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2 opacity-100">
                                        <button
                                            class="p-1.5 text-[#4c739a] hover:text-primary transition-colors rounded hover:bg-slate-100 dark:hover:bg-slate-700"
                                            title="Edit">
                                            <span class="material-symbols-outlined text-[18px]">edit</span>
                                        </button>
                                        <button
                                            class="p-1.5 text-[#4c739a] hover:text-red-500 transition-colors rounded hover:bg-red-50 dark:hover:bg-red-900/20"
                                            title="Delete">
                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                        </button>
                                        <button
                                            class="p-1.5 text-primary bg-white dark:bg-slate-800 shadow-sm border border-[#e7edf3] dark:border-slate-600 rounded hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors"
                                            title="Manage Subkriteria">
                                            <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <!-- Row 2 -->
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="size-2 bg-transparent rounded-full"></div>
                                        <p class="font-medium text-[#0d141b] dark:text-white text-sm">Jumlah Pelanggaran</p>
                                    </div>
                                    <p class="text-xs text-[#4c739a] ml-5 mt-0.5">Code: C2</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300">
                                        Cost
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-medium text-[#4c739a]">20%</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button
                                            class="p-1.5 text-[#4c739a] hover:text-primary transition-colors rounded hover:bg-slate-100 dark:hover:bg-slate-700">
                                            <span class="material-symbols-outlined text-[18px]">edit</span>
                                        </button>
                                        <button
                                            class="p-1.5 text-[#4c739a] hover:text-red-500 transition-colors rounded hover:bg-red-50 dark:hover:bg-red-900/20">
                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                        </button>
                                        <button
                                            class="p-1.5 text-[#4c739a] hover:text-primary transition-colors rounded hover:bg-slate-100 dark:hover:bg-slate-700">
                                            <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <!-- Row 3 -->
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="size-2 bg-transparent rounded-full"></div>
                                        <p class="font-medium text-[#0d141b] dark:text-white text-sm">Nilai Akademik</p>
                                    </div>
                                    <p class="text-xs text-[#4c739a] ml-5 mt-0.5">Code: C3</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                        Benefit
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-medium text-[#4c739a]">25%</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button
                                            class="p-1.5 text-[#4c739a] hover:text-primary transition-colors rounded hover:bg-slate-100 dark:hover:bg-slate-700">
                                            <span class="material-symbols-outlined text-[18px]">edit</span>
                                        </button>
                                        <button
                                            class="p-1.5 text-[#4c739a] hover:text-red-500 transition-colors rounded hover:bg-red-50 dark:hover:bg-red-900/20">
                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                        </button>
                                        <button
                                            class="p-1.5 text-[#4c739a] hover:text-primary transition-colors rounded hover:bg-slate-100 dark:hover:bg-slate-700">
                                            <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <!-- Row 4 -->
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="size-2 bg-transparent rounded-full"></div>
                                        <p class="font-medium text-[#0d141b] dark:text-white text-sm">Keaktifan
                                            Ekstrakurikuler</p>
                                    </div>
                                    <p class="text-xs text-[#4c739a] ml-5 mt-0.5">Code: C4</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                        Benefit
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-medium text-[#4c739a]">10%</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button
                                            class="p-1.5 text-[#4c739a] hover:text-primary transition-colors rounded hover:bg-slate-100 dark:hover:bg-slate-700">
                                            <span class="material-symbols-outlined text-[18px]">edit</span>
                                        </button>
                                        <button
                                            class="p-1.5 text-[#4c739a] hover:text-red-500 transition-colors rounded hover:bg-red-50 dark:hover:bg-red-900/20">
                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                        </button>
                                        <button
                                            class="p-1.5 text-[#4c739a] hover:text-primary transition-colors rounded hover:bg-slate-100 dark:hover:bg-slate-700">
                                            <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div
                    class="px-6 py-4 border-t border-[#e7edf3] dark:border-slate-700 bg-slate-50 dark:bg-slate-800/30 flex justify-between items-center text-sm">
                    <span class="text-[#4c739a]">Showing 4 of 4 criteria</span>
                </div>
            </div>
        </div>

        <!-- Right Column: Subkriteria Management Panel -->
        <div class="lg:col-span-5">
            <div
                class="sticky top-24 bg-white dark:bg-slate-900 rounded-xl shadow-lg border border-primary/20 dark:border-primary/20 flex flex-col h-[600px] overflow-hidden">
                <!-- Panel Header -->
                <div
                    class="px-6 py-5 border-b border-[#e7edf3] dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 flex flex-col gap-1">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold uppercase tracking-wider text-primary mb-1">Managing
                            Sub-criteria</span>
                        <button class="text-[#4c739a] hover:text-[#0d141b] dark:hover:text-white" title="Close Panel">
                            <span class="material-symbols-outlined text-[20px]">close</span>
                        </button>
                    </div>
                    <h3 class="text-xl font-bold text-[#0d141b] dark:text-white flex items-center gap-2">
                        Nilai Akhlak (C1)
                        <span class="material-symbols-outlined text-slate-300 text-[20px]">info</span>
                    </h3>
                    <p class="text-sm text-[#4c739a]">Define the crisp values for this criterion.</p>
                </div>

                <!-- Add Subkriteria Form -->
                <div class="p-6 bg-slate-50 dark:bg-slate-800/20 border-b border-[#e7edf3] dark:border-slate-700">
                    <label class="block text-xs font-medium text-[#0d141b] dark:text-white mb-2">Add New Subkriteria</label>
                    <div class="flex gap-2">
                        <input
                            class="flex-1 rounded-lg border-[#e7edf3] dark:border-slate-600 bg-white dark:bg-slate-800 text-sm focus:ring-primary focus:border-primary"
                            placeholder="Description (e.g. Sangat Baik)" type="text" />
                        <input
                            class="w-20 rounded-lg border-[#e7edf3] dark:border-slate-600 bg-white dark:bg-slate-800 text-sm focus:ring-primary focus:border-primary"
                            placeholder="Val" type="number" />
                        <button
                            class="bg-[#0d141b] dark:bg-white text-white dark:text-[#0d141b] p-2 rounded-lg hover:opacity-90 transition-opacity flex items-center justify-center">
                            <span class="material-symbols-outlined text-[20px]">add</span>
                        </button>
                    </div>
                </div>

                <!-- List of Subkriteria -->
                <div class="flex-1 overflow-y-auto p-2">
                    <div class="flex flex-col gap-1">
                        <!-- Item 1 -->
                        <div
                            class="group flex items-center justify-between p-3 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 border border-transparent hover:border-[#e7edf3] dark:hover:border-slate-700 transition-all">
                            <div class="flex items-center gap-3">
                                <div
                                    class="size-8 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold text-sm">
                                    5</div>
                                <div>
                                    <p class="text-sm font-medium text-[#0d141b] dark:text-white">Sangat Baik</p>
                                    <p class="text-xs text-[#4c739a]">Value Weight: 5</p>
                                </div>
                            </div>
                            <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button
                                    class="p-1.5 text-[#4c739a] hover:text-primary hover:bg-white dark:hover:bg-slate-700 rounded-md shadow-sm">
                                    <span class="material-symbols-outlined text-[16px]">edit</span>
                                </button>
                                <button
                                    class="p-1.5 text-[#4c739a] hover:text-red-500 hover:bg-white dark:hover:bg-slate-700 rounded-md shadow-sm">
                                    <span class="material-symbols-outlined text-[16px]">delete</span>
                                </button>
                            </div>
                        </div>
                        <!-- Item 2 -->
                        <div
                            class="group flex items-center justify-between p-3 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 border border-transparent hover:border-[#e7edf3] dark:hover:border-slate-700 transition-all">
                            <div class="flex items-center gap-3">
                                <div
                                    class="size-8 rounded-full bg-slate-100 dark:bg-slate-800 text-[#4c739a] flex items-center justify-center font-bold text-sm">
                                    4</div>
                                <div>
                                    <p class="text-sm font-medium text-[#0d141b] dark:text-white">Baik</p>
                                    <p class="text-xs text-[#4c739a]">Value Weight: 4</p>
                                </div>
                            </div>
                            <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button
                                    class="p-1.5 text-[#4c739a] hover:text-primary hover:bg-white dark:hover:bg-slate-700 rounded-md shadow-sm">
                                    <span class="material-symbols-outlined text-[16px]">edit</span>
                                </button>
                                <button
                                    class="p-1.5 text-[#4c739a] hover:text-red-500 hover:bg-white dark:hover:bg-slate-700 rounded-md shadow-sm">
                                    <span class="material-symbols-outlined text-[16px]">delete</span>
                                </button>
                            </div>
                        </div>
                        <!-- Item 3 -->
                        <div
                            class="group flex items-center justify-between p-3 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 border border-transparent hover:border-[#e7edf3] dark:hover:border-slate-700 transition-all">
                            <div class="flex items-center gap-3">
                                <div
                                    class="size-8 rounded-full bg-slate-100 dark:bg-slate-800 text-[#4c739a] flex items-center justify-center font-bold text-sm">
                                    3</div>
                                <div>
                                    <p class="text-sm font-medium text-[#0d141b] dark:text-white">Cukup</p>
                                    <p class="text-xs text-[#4c739a]">Value Weight: 3</p>
                                </div>
                            </div>
                            <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button
                                    class="p-1.5 text-[#4c739a] hover:text-primary hover:bg-white dark:hover:bg-slate-700 rounded-md shadow-sm">
                                    <span class="material-symbols-outlined text-[16px]">edit</span>
                                </button>
                                <button
                                    class="p-1.5 text-[#4c739a] hover:text-red-500 hover:bg-white dark:hover:bg-slate-700 rounded-md shadow-sm">
                                    <span class="material-symbols-outlined text-[16px]">delete</span>
                                </button>
                            </div>
                        </div>
                        <!-- Item 4 -->
                        <div
                            class="group flex items-center justify-between p-3 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 border border-transparent hover:border-[#e7edf3] dark:hover:border-slate-700 transition-all">
                            <div class="flex items-center gap-3">
                                <div
                                    class="size-8 rounded-full bg-slate-100 dark:bg-slate-800 text-[#4c739a] flex items-center justify-center font-bold text-sm">
                                    2</div>
                                <div>
                                    <p class="text-sm font-medium text-[#0d141b] dark:text-white">Kurang</p>
                                    <p class="text-xs text-[#4c739a]">Value Weight: 2</p>
                                </div>
                            </div>
                            <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button
                                    class="p-1.5 text-[#4c739a] hover:text-primary hover:bg-white dark:hover:bg-slate-700 rounded-md shadow-sm">
                                    <span class="material-symbols-outlined text-[16px]">edit</span>
                                </button>
                                <button
                                    class="p-1.5 text-[#4c739a] hover:text-red-500 hover:bg-white dark:hover:bg-slate-700 rounded-md shadow-sm">
                                    <span class="material-symbols-outlined text-[16px]">delete</span>
                                </button>
                            </div>
                        </div>
                        <!-- Item 5 -->
                        <div
                            class="group flex items-center justify-between p-3 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 border border-transparent hover:border-[#e7edf3] dark:hover:border-slate-700 transition-all">
                            <div class="flex items-center gap-3">
                                <div
                                    class="size-8 rounded-full bg-slate-100 dark:bg-slate-800 text-[#4c739a] flex items-center justify-center font-bold text-sm">
                                    1</div>
                                <div>
                                    <p class="text-sm font-medium text-[#0d141b] dark:text-white">Sangat Kurang</p>
                                    <p class="text-xs text-[#4c739a]">Value Weight: 1</p>
                                </div>
                            </div>
                            <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button
                                    class="p-1.5 text-[#4c739a] hover:text-primary hover:bg-white dark:hover:bg-slate-700 rounded-md shadow-sm">
                                    <span class="material-symbols-outlined text-[16px]">edit</span>
                                </button>
                                <button
                                    class="p-1.5 text-[#4c739a] hover:text-red-500 hover:bg-white dark:hover:bg-slate-700 rounded-md shadow-sm">
                                    <span class="material-symbols-outlined text-[16px]">delete</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="p-4 border-t border-[#e7edf3] dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 text-center">
                    <p class="text-xs text-[#4c739a]">Total 5 subkriteria defined.</p>
                </div>
            </div>
        </div>
    </div>
@endsection