<!doctype html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Portal Wali Santri') — Annuqayah Latee II</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-[#f0f4f8] dark:bg-slate-950 font-[Inter]">

    <div class="flex h-full">

        {{-- Sidebar --}}
        <aside class="w-60 shrink-0 flex flex-col h-full bg-white dark:bg-slate-900 border-r border-[#e7edf3] dark:border-slate-800 shadow-sm">
            <div class="p-5 border-b border-[#e7edf3] dark:border-slate-800">
                <div class="flex items-center gap-3">
                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-primary/10 text-primary">
                        <span class="material-symbols-outlined text-[20px]">family_restroom</span>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-[#0d141b] dark:text-white leading-tight">Portal Wali</p>
                        <p class="text-[11px] text-[#4c739a]">Perizinan Santri</p>
                    </div>
                </div>
            </div>

            <nav class="flex-1 overflow-y-auto p-3 space-y-0.5">
                <a href="{{ route('guardian.dashboard') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                    {{ request()->routeIs('guardian.dashboard') ? 'bg-primary/10 text-primary' : 'text-[#4c739a] hover:bg-[#e7edf3] hover:text-[#0d141b] dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white' }}">
                    <span class="material-symbols-outlined text-[22px]">dashboard</span>
                    Dashboard
                </a>
                <a href="{{ route('guardian.licenses.create') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                    {{ request()->routeIs('guardian.licenses.create') ? 'bg-primary/10 text-primary' : 'text-[#4c739a] hover:bg-[#e7edf3] hover:text-[#0d141b] dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white' }}">
                    <span class="material-symbols-outlined text-[22px]">edit_note</span>
                    Pengajuan Izin
                </a>
                <a href="{{ route('guardian.licenses.index') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                    {{ request()->routeIs('guardian.licenses.index') ? 'bg-primary/10 text-primary' : 'text-[#4c739a] hover:bg-[#e7edf3] hover:text-[#0d141b] dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white' }}">
                    <span class="material-symbols-outlined text-[22px]">history</span>
                    Riwayat Izin
                </a>
                <a href="{{ route('guardian.profile') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                    {{ request()->routeIs('guardian.profile') ? 'bg-primary/10 text-primary' : 'text-[#4c739a] hover:bg-[#e7edf3] hover:text-[#0d141b] dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white' }}">
                    <span class="material-symbols-outlined text-[22px]">manage_accounts</span>
                    Profil Saya
                </a>
            </nav>

            <div class="p-3 border-t border-[#e7edf3] dark:border-slate-800">
                <div class="flex items-center gap-3 px-2 py-2 mb-2">
                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-primary/10 text-primary font-bold text-sm shrink-0">
                        {{ substr(Auth::guard('guardian')->user()->name, 0, 1) }}
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-sm font-semibold text-[#0d141b] dark:text-white truncate">{{ Auth::guard('guardian')->user()->name }}</p>
                        <p class="text-xs text-[#4c739a] truncate">{{ Auth::guard('guardian')->user()->username }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('guardian.logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium text-red-500 hover:bg-red-50 dark:hover:bg-red-900/10 transition-colors">
                        <span class="material-symbols-outlined text-[18px]">logout</span>
                        Keluar
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main --}}
        <main class="flex-1 overflow-y-auto">
            <div class="p-6">
                @yield('content')
            </div>
        </main>

    </div>

</body>
</html>
