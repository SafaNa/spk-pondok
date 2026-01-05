@extends('layouts.app-v2-sidebar')

@section('title', 'Dashboard - Santri Admin')
@section('mobile_title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@section('content')
    <!-- Page Heading -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div class="flex flex-col gap-1">
            <h1 class="text-[#0d141b] dark:text-white text-3xl font-black tracking-tight">Dashboard Overview</h1>
            <p class="text-[#4c739a] text-base font-normal">Monitor santri evaluation progress and manage SAW criteria.</p>
        </div>
        <div class="flex gap-3">
            <button class="inline-flex items-center gap-2 rounded-lg bg-white dark:bg-slate-800 px-4 py-2 text-sm font-medium text-[#0d141b] dark:text-white shadow-sm ring-1 ring-inset ring-[#e7edf3] dark:ring-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 transition-all">
                <span class="material-symbols-outlined text-[20px]">settings</span>
                Configuration
            </button>
            <button class="inline-flex items-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-600 transition-all">
                <span class="material-symbols-outlined text-[20px]">add</span>
                New Assessment
            </button>
        </div>
    </div>

    <!-- KPI Cards Grid -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Card 1: Total Santri -->
        <div class="relative overflow-hidden rounded-xl bg-white dark:bg-slate-900 p-6 shadow-sm ring-1 ring-[#e7edf3] dark:ring-slate-800">
            <dt>
                <div class="absolute rounded-md bg-blue-50 dark:bg-blue-900/20 p-3">
                    <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">groups</span>
                </div>
                <p class="ml-16 truncate text-sm font-medium text-[#4c739a]">Total Santri</p>
            </dt>
            <dd class="ml-16 flex items-baseline pb-1 sm:pb-2">
                <p class="text-2xl font-semibold text-[#0d141b] dark:text-white">1,240</p>
                <p class="ml-2 flex items-baseline text-sm font-semibold text-green-600">
                    <span class="material-symbols-outlined text-xs self-center">arrow_upward</span>
                    12
                </p>
            </dd>
        </div>

        <!-- Card 2: Evaluation Criteria -->
        <div class="relative overflow-hidden rounded-xl bg-white dark:bg-slate-900 p-6 shadow-sm ring-1 ring-[#e7edf3] dark:ring-slate-800">
            <dt>
                <div class="absolute rounded-md bg-purple-50 dark:bg-purple-900/20 p-3">
                    <span class="material-symbols-outlined text-purple-600 dark:text-purple-400">tune</span>
                </div>
                <p class="ml-16 truncate text-sm font-medium text-[#4c739a]">SAW Criteria</p>
            </dt>
            <dd class="ml-16 flex items-baseline pb-1 sm:pb-2">
                <p class="text-2xl font-semibold text-[#0d141b] dark:text-white">5</p>
                <p class="ml-2 truncate text-xs text-[#4c739a]">Active weights</p>
            </dd>
        </div>

        <!-- Card 3: Assessed Santri -->
        <div class="relative overflow-hidden rounded-xl bg-white dark:bg-slate-900 p-6 shadow-sm ring-1 ring-[#e7edf3] dark:ring-slate-800">
            <dt>
                <div class="absolute rounded-md bg-primary/10 p-3">
                    <span class="material-symbols-outlined text-primary">fact_check</span>
                </div>
                <p class="ml-16 truncate text-sm font-medium text-[#4c739a]">Assessed Santri</p>
            </dt>
            <dd class="ml-16 flex items-baseline pb-1 sm:pb-2">
                <p class="text-2xl font-semibold text-[#0d141b] dark:text-white">845</p>
                <div class="absolute bottom-0 inset-x-0 bg-slate-50 dark:bg-slate-800 px-4 py-2 sm:px-6">
                    <div class="text-xs font-medium text-[#4c739a]">68% Completion rate</div>
                    <div class="mt-1 h-1.5 w-full rounded-full bg-slate-200 dark:bg-slate-700">
                        <div class="h-1.5 rounded-full bg-primary" style="width: 68%"></div>
                    </div>
                </div>
            </dd>
        </div>

        <!-- Card 4: Action Required -->
        <div class="relative overflow-hidden rounded-xl bg-white dark:bg-slate-900 p-6 shadow-sm ring-1 ring-[#e7edf3] dark:ring-slate-800">
            <dt>
                <div class="absolute rounded-md bg-orange-50 dark:bg-orange-900/20 p-3">
                    <span class="material-symbols-outlined text-orange-600 dark:text-orange-400">warning</span>
                </div>
                <p class="ml-16 truncate text-sm font-medium text-[#4c739a]">Pending Review</p>
            </dt>
            <dd class="ml-16 flex items-baseline pb-1 sm:pb-2">
                <p class="text-2xl font-semibold text-[#0d141b] dark:text-white">24</p>
                <p class="ml-2 truncate text-xs text-[#4c739a]">Need attention</p>
            </dd>
        </div>
    </div>

    <!-- Charts & Analytics Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column: Top 5 Santri (Bar Chart Visualization) -->
        <div class="lg:col-span-2 rounded-xl bg-white dark:bg-slate-900 shadow-sm ring-1 ring-[#e7edf3] dark:ring-slate-800 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-base font-semibold leading-6 text-[#0d141b] dark:text-white">Top 5 Performing Santri</h3>
                    <p class="mt-1 text-sm text-[#4c739a]">Highest final scores based on SAW calculation.</p>
                </div>
                <button class="text-sm font-medium text-primary hover:text-blue-700">View All</button>
            </div>
            <!-- Chart Implementation using CSS -->
            <div class="relative mt-4 h-64 w-full">
                <div class="absolute inset-0 flex flex-col justify-between text-xs text-[#4c739a]">
                    <div class="border-b border-slate-100 dark:border-slate-800 w-full h-0 flex items-center"><span>1.0</span></div>
                    <div class="border-b border-slate-100 dark:border-slate-800 w-full h-0 flex items-center"><span>0.8</span></div>
                    <div class="border-b border-slate-100 dark:border-slate-800 w-full h-0 flex items-center"><span>0.6</span></div>
                    <div class="border-b border-slate-100 dark:border-slate-800 w-full h-0 flex items-center"><span>0.4</span></div>
                    <div class="border-b border-slate-100 dark:border-slate-800 w-full h-0 flex items-center"><span>0.2</span></div>
                    <div class="border-b border-slate-100 dark:border-slate-800 w-full h-0 flex items-center"><span>0.0</span></div>
                </div>
                <!-- Bars Container -->
                <div class="absolute inset-0 flex items-end justify-around pl-8 pb-6 pt-2">
                    <!-- Bar 1 -->
                    <div class="group relative flex flex-col items-center gap-2 w-16">
                        <div class="w-full bg-primary/90 hover:bg-primary transition-all duration-300 rounded-t-md relative group-hover:shadow-lg" style="height: 92%">
                            <div class="absolute -top-8 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity bg-slate-900 text-white text-xs px-2 py-1 rounded">0.92</div>
                        </div>
                        <span class="text-xs font-medium text-[#4c739a] truncate w-full text-center">Ahmad</span>
                    </div>
                    <!-- Bar 2 -->
                    <div class="group relative flex flex-col items-center gap-2 w-16">
                        <div class="w-full bg-primary/70 hover:bg-primary transition-all duration-300 rounded-t-md relative group-hover:shadow-lg" style="height: 85%">
                            <div class="absolute -top-8 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity bg-slate-900 text-white text-xs px-2 py-1 rounded">0.85</div>
                        </div>
                        <span class="text-xs font-medium text-[#4c739a] truncate w-full text-center">Yusuf</span>
                    </div>
                    <!-- Bar 3 -->
                    <div class="group relative flex flex-col items-center gap-2 w-16">
                        <div class="w-full bg-primary/60 hover:bg-primary transition-all duration-300 rounded-t-md relative group-hover:shadow-lg" style="height: 78%">
                            <div class="absolute -top-8 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity bg-slate-900 text-white text-xs px-2 py-1 rounded">0.78</div>
                        </div>
                        <span class="text-xs font-medium text-[#4c739a] truncate w-full text-center">Fatimah</span>
                    </div>
                    <!-- Bar 4 -->
                    <div class="group relative flex flex-col items-center gap-2 w-16">
                        <div class="w-full bg-primary/50 hover:bg-primary transition-all duration-300 rounded-t-md relative group-hover:shadow-lg" style="height: 72%">
                            <div class="absolute -top-8 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity bg-slate-900 text-white text-xs px-2 py-1 rounded">0.72</div>
                        </div>
                        <span class="text-xs font-medium text-[#4c739a] truncate w-full text-center">Zaid</span>
                    </div>
                    <!-- Bar 5 -->
                    <div class="group relative flex flex-col items-center gap-2 w-16">
                        <div class="w-full bg-primary/40 hover:bg-primary transition-all duration-300 rounded-t-md relative group-hover:shadow-lg" style="height: 65%">
                            <div class="absolute -top-8 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity bg-slate-900 text-white text-xs px-2 py-1 rounded">0.65</div>
                        </div>
                        <span class="text-xs font-medium text-[#4c739a] truncate w-full text-center">Umar</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Recommendation Status (Donut Chart) -->
        <div class="lg:col-span-1 rounded-xl bg-white dark:bg-slate-900 shadow-sm ring-1 ring-[#e7edf3] dark:ring-slate-800 p-6 flex flex-col">
            <h3 class="text-base font-semibold leading-6 text-[#0d141b] dark:text-white mb-4">Recommendation Status</h3>
            <div class="flex-1 flex flex-col items-center justify-center">
                <!-- CSS Pie Chart -->
                <div class="relative w-48 h-48 rounded-full mb-6" style="background: conic-gradient(#137fec 0% 65%, #fbbf24 65% 85%, #9ca3af 85% 100%);">
                    <div class="absolute inset-0 m-auto w-32 h-32 bg-white dark:bg-slate-900 rounded-full flex items-center justify-center flex-col">
                        <span class="text-3xl font-bold text-[#0d141b] dark:text-white">845</span>
                        <span class="text-xs text-[#4c739a]">Total</span>
                    </div>
                </div>
                <!-- Legend -->
                <div class="w-full space-y-3">
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-primary"></span>
                            <span class="text-[#4c739a]">Recommended</span>
                        </div>
                        <span class="font-semibold text-[#0d141b] dark:text-white">65%</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-amber-400"></span>
                            <span class="text-[#4c739a]">Pending</span>
                        </div>
                        <span class="font-semibold text-[#0d141b] dark:text-white">20%</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-gray-400"></span>
                            <span class="text-[#4c739a]">Not Recommended</span>
                        </div>
                        <span class="font-semibold text-[#0d141b] dark:text-white">15%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Section: Quick Actions & Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Quick Action Grid -->
        <div class="lg:col-span-2 grid grid-cols-2 gap-4">
            <a class="group relative flex flex-col items-start justify-between rounded-xl bg-white dark:bg-slate-900 p-6 shadow-sm ring-1 ring-[#e7edf3] dark:ring-slate-800 hover:ring-primary/50 hover:shadow-md transition-all" href="{{ route('santri-v2') }}">
                <div>
                    <div class="mb-4 inline-flex h-10 w-10 items-center justify-center rounded-lg bg-blue-50 dark:bg-blue-900/20 text-blue-600 group-hover:text-white group-hover:bg-blue-600 transition-colors">
                        <span class="material-symbols-outlined">person_add</span>
                    </div>
                    <h4 class="font-semibold text-[#0d141b] dark:text-white">Add New Santri</h4>
                    <p class="mt-1 text-sm text-[#4c739a]">Register new students into the active period.</p>
                </div>
                <span class="mt-4 flex items-center text-sm font-medium text-blue-600 dark:text-blue-400 group-hover:text-blue-700">
                    Go to management <span class="material-symbols-outlined text-sm ml-1">arrow_forward</span>
                </span>
            </a>
            <a class="group relative flex flex-col items-start justify-between rounded-xl bg-white dark:bg-slate-900 p-6 shadow-sm ring-1 ring-[#e7edf3] dark:ring-slate-800 hover:ring-primary/50 hover:shadow-md transition-all" href="#">
                <div>
                    <div class="mb-4 inline-flex h-10 w-10 items-center justify-center rounded-lg bg-purple-50 dark:bg-purple-900/20 text-purple-600 group-hover:text-white group-hover:bg-purple-600 transition-colors">
                        <span class="material-symbols-outlined">checklist</span>
                    </div>
                    <h4 class="font-semibold text-[#0d141b] dark:text-white">Manage Criteria</h4>
                    <p class="mt-1 text-sm text-[#4c739a]">Adjust weights and criteria for SAW calculation.</p>
                </div>
                <span class="mt-4 flex items-center text-sm font-medium text-purple-600 dark:text-purple-400 group-hover:text-purple-700">
                    Edit settings <span class="material-symbols-outlined text-sm ml-1">arrow_forward</span>
                </span>
            </a>
        </div>

        <!-- Recent Activity List -->
        <div class="lg:col-span-2 rounded-xl bg-white dark:bg-slate-900 shadow-sm ring-1 ring-[#e7edf3] dark:ring-slate-800 p-0 overflow-hidden">
            <div class="border-b border-[#e7edf3] dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50 px-6 py-4 flex justify-between items-center">
                <h3 class="text-base font-semibold text-[#0d141b] dark:text-white">Recent Assessments</h3>
                <button class="text-xs font-medium text-primary hover:text-blue-700">View History</button>
            </div>
            <ul class="divide-y divide-[#e7edf3] dark:divide-slate-800" role="list">
                <li class="flex items-center gap-x-4 px-6 py-4 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors cursor-pointer">
                    <div class="h-10 w-10 flex-none rounded-full bg-primary/10 flex items-center justify-center text-primary text-xs font-bold">MA</div>
                    <div class="min-w-0 flex-auto">
                        <p class="text-sm font-semibold leading-6 text-[#0d141b] dark:text-white">Muhammad Ali</p>
                        <p class="mt-1 truncate text-xs leading-5 text-[#4c739a]">Assessed by Ust. Abdullah</p>
                    </div>
                    <div class="flex flex-col items-end">
                        <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">Recommended</span>
                        <time class="mt-1 text-xs text-[#4c739a]">1h ago</time>
                    </div>
                </li>
                <li class="flex items-center gap-x-4 px-6 py-4 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors cursor-pointer">
                    <div class="h-10 w-10 flex-none rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-[#4c739a] text-xs font-bold">SR</div>
                    <div class="min-w-0 flex-auto">
                        <p class="text-sm font-semibold leading-6 text-[#0d141b] dark:text-white">Siti Rahma</p>
                        <p class="mt-1 truncate text-xs leading-5 text-[#4c739a]">Assessed by Ust. Hasan</p>
                    </div>
                    <div class="flex flex-col items-end">
                        <span class="inline-flex items-center rounded-md bg-yellow-50 px-2 py-1 text-xs font-medium text-yellow-800 ring-1 ring-inset ring-yellow-600/20">Pending</span>
                        <time class="mt-1 text-xs text-[#4c739a]">3h ago</time>
                    </div>
                </li>
                <li class="flex items-center gap-x-4 px-6 py-4 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors cursor-pointer">
                    <div class="h-10 w-10 flex-none rounded-full bg-primary/10 flex items-center justify-center text-primary text-xs font-bold">IK</div>
                    <div class="min-w-0 flex-auto">
                        <p class="text-sm font-semibold leading-6 text-[#0d141b] dark:text-white">Ibrahim Khalil</p>
                        <p class="mt-1 truncate text-xs leading-5 text-[#4c739a]">Assessed by Ust. Abdullah</p>
                    </div>
                    <div class="flex flex-col items-end">
                        <span class="inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/10">Not Rec.</span>
                        <time class="mt-1 text-xs text-[#4c739a]">5h ago</time>
                    </div>
                </li>
            </ul>
        </div>
    </div>
@endsection