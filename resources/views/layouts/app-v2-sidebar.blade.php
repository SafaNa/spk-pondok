<!DOCTYPE html>
<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>@yield('title', 'Santri Admin')</title>
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;900&display=swap"
        rel="stylesheet" />
    <!-- Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet" />
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <!-- Tailwind Config -->
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#137fec",
                        "background-light": "#f6f7f8",
                        "background-dark": "#101922",
                    },
                    fontFamily: {
                        "display": ["Inter", "sans-serif"]
                    },
                    borderRadius: { "DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px" },
                },
            },
        }
    </script>
    <style>
        .material-symbols-outlined.fill-1 {
            font-variation-settings: 'FILL' 1;
        }
    </style>
</head>

<body
    class="font-display bg-background-light dark:bg-background-dark text-[#0d141b] dark:text-slate-100 overflow-hidden">
    <div class="flex h-screen w-full overflow-hidden">
        <!-- Side Navigation -->
        <aside
            class="hidden md:flex w-64 flex-col justify-between border-r border-[#e7edf3] dark:border-slate-800 bg-white dark:bg-slate-900 h-full">
            <div class="flex flex-col">
                <!-- Logo / Brand -->
                <div class="p-6 flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10 text-primary">
                        <span class="material-symbols-outlined">school</span>
                    </div>
                    <div class="flex flex-col">
                        <h1 class="text-[#0d141b] dark:text-white text-base font-bold leading-normal">Santri Admin</h1>
                        <p class="text-[#4c739a] text-xs font-normal leading-normal">Management System</p>
                    </div>
                </div>
                <!-- Nav Items -->
                <nav class="flex flex-col gap-2 px-3">
                    <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('dashboard-v2') ? 'bg-primary/10 text-primary dark:bg-primary/20 dark:text-blue-400' : 'text-[#4c739a] hover:bg-[#e7edf3] hover:text-[#0d141b] dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white' }} transition-colors"
                        href="{{ route('dashboard-v2') }}">
                        <span
                            class="material-symbols-outlined text-[24px] {{ request()->routeIs('dashboard-v2') ? 'fill-1' : '' }}">dashboard</span>
                        <span class="text-sm font-medium">Dashboard</span>
                    </a>
                    <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('santri-v2') ? 'bg-primary/10 text-primary dark:bg-primary/20 dark:text-blue-400' : 'text-[#4c739a] hover:bg-[#e7edf3] hover:text-[#0d141b] dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white' }} transition-colors"
                        href="{{ route('santri-v2') }}">
                        <span
                            class="material-symbols-outlined text-[24px] {{ request()->routeIs('santri-v2') ? 'fill-1' : '' }}">group</span>
                        <span class="text-sm font-medium">Data Santri</span>
                    </a>
                    <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('kriteria-v2') ? 'bg-primary/10 text-primary dark:bg-primary/20 dark:text-blue-400' : 'text-[#4c739a] hover:bg-[#e7edf3] hover:text-[#0d141b] dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white' }} transition-colors"
                        href="{{ route('kriteria-v2') }}">
                        <span
                            class="material-symbols-outlined text-[24px] {{ request()->routeIs('kriteria-v2') ? 'fill-1' : '' }}">tune</span>
                        <span class="text-sm font-medium">Kriteria</span>
                    </a>
                    <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('periode-v2') ? 'bg-primary/10 text-primary dark:bg-primary/20 dark:text-blue-400' : 'text-[#4c739a] hover:bg-[#e7edf3] hover:text-[#0d141b] dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white' }} transition-colors"
                        href="{{ route('periode-v2') }}">
                        <span
                            class="material-symbols-outlined text-[24px] {{ request()->routeIs('periode-v2') ? 'fill-1' : '' }}">calendar_month</span>
                        <span class="text-sm font-medium">Periode</span>
                    </a>
                    <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('penilaian-v2') ? 'bg-primary/10 text-primary dark:bg-primary/20 dark:text-blue-400' : 'text-[#4c739a] hover:bg-[#e7edf3] hover:text-[#0d141b] dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white' }} transition-colors"
                        href="{{ route('penilaian-v2') }}">
                        <span
                            class="material-symbols-outlined text-[24px] {{ request()->routeIs('penilaian-v2') ? 'fill-1' : '' }}">grading</span>
                        <span class="text-sm font-medium">Penilaian</span>
                    </a>
                    <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('rekomendasi-v2') ? 'bg-primary/10 text-primary dark:bg-primary/20 dark:text-blue-400' : 'text-[#4c739a] hover:bg-[#e7edf3] hover:text-[#0d141b] dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white' }} transition-colors"
                        href="{{ route('rekomendasi-v2') }}">
                        <span
                            class="material-symbols-outlined text-[24px] {{ request()->routeIs('rekomendasi-v2') ? 'fill-1' : '' }}">calculate</span>
                        <span class="text-sm font-medium">SAW Analysis</span>
                    </a>
                    <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[#4c739a] hover:bg-[#e7edf3] hover:text-[#0d141b] dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white transition-colors"
                        href="#">
                        <span class="material-symbols-outlined text-[24px]">settings</span>
                        <span class="text-sm font-medium">Settings</span>
                    </a>
                </nav>
            </div>
            <!-- User Profile (Bottom Sidebar) -->
            <div class="p-4 border-t border-[#e7edf3] dark:border-slate-800">
                <div
                    class="flex items-center gap-3 cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-800 p-2 rounded-lg transition-colors">
                    <div
                        class="flex h-9 w-9 items-center justify-center rounded-full bg-primary/10 text-primary font-bold text-sm">
                        AD
                    </div>
                    <div class="flex flex-col overflow-hidden">
                        <p class="text-[#0d141b] dark:text-white text-sm font-medium truncate">Admin User</p>
                        <p class="text-[#4c739a] text-xs truncate">admin@pesantren.id</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content Wrapper -->
        <main class="flex-1 flex flex-col h-full relative overflow-hidden">
            <!-- Top Navbar -->
            <header
                class="flex items-center justify-between whitespace-nowrap border-b border-[#e7edf3] dark:border-slate-800 bg-white dark:bg-slate-900 px-6 py-3 shrink-0">
                <div class="flex items-center gap-4 lg:hidden">
                    <button class="text-[#0d141b] dark:text-white">
                        <span class="material-symbols-outlined">menu</span>
                    </button>
                    <h2 class="text-[#0d141b] dark:text-white text-lg font-bold leading-tight">
                        @yield('mobile_title', 'Santri Management')</h2>
                </div>
                <!-- Breadcrumbs -->
                <div class="hidden lg:flex items-center gap-2">
                    <a class="text-[#4c739a] text-sm font-medium hover:text-primary transition-colors"
                        href="{{ route('dashboard-v2') }}">Home</a>
                    <span class="text-[#4c739a] text-sm">/</span>
                    <span class="text-[#0d141b] dark:text-white text-sm font-medium">@yield('breadcrumb', 'Page')</span>
                </div>
                <div class="flex flex-1 justify-end gap-4 items-center">
                    <button
                        class="hidden sm:flex items-center justify-center rounded-full size-10 hover:bg-[#e7edf3] dark:hover:bg-slate-800 text-[#4c739a]">
                        <span class="material-symbols-outlined">notifications</span>
                    </button>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="flex h-9 px-4 cursor-pointer items-center justify-center rounded-lg border border-[#e7edf3] dark:border-slate-700 bg-white dark:bg-slate-800 text-[#0d141b] dark:text-white text-sm font-bold shadow-sm hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                            <span class="truncate">Logout</span>
                        </button>
                    </form>
                </div>
            </header>

            <!-- Scrollable Content -->
            <div class="flex-1 overflow-y-auto p-4 md:p-8 scroll-smooth">
                <div class="max-w-[1200px] mx-auto flex flex-col gap-6">
                    @yield('content')
                </div>
            </div>
        </main>
    </div>
</body>

</html>