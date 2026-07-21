<!doctype html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="{{ config('session.lifetime') * 60 }}">
    <title>@yield('title', 'Portal Wali Santri') — Annuqayah Latee II</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}?v=2">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Cropper.js -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
</head>
<body class="bg-[#f0f4f8] font-[Inter] overflow-hidden">

    <div class="flex h-screen w-full overflow-hidden">

        {{-- Mobile Overlay --}}
        <div id="guardianOverlay" onclick="toggleGuardianSidebar()"
            class="fixed inset-0 bg-black/50 z-40 hidden opacity-0 pointer-events-none md:hidden backdrop-blur-sm transition-opacity">
        </div>

        {{-- Sidebar --}}
        <aside id="guardianSidebar"
            class="fixed inset-y-0 left-0 z-50 w-60 flex flex-col h-full bg-white border-r border-[#e7edf3] shadow-2xl transform -translate-x-full transition-transform duration-300 md:relative md:translate-x-0 md:shadow-none md:shrink-0">

            <div class="p-5 border-b border-[#e7edf3] shrink-0 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-primary/10 text-primary">
                        <span class="material-symbols-outlined text-[20px]">family_restroom</span>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-[#0d141b] leading-tight">Portal Wali</p>
                        <p class="text-[11px] text-[#4c739a]">Perizinan Santri</p>
                    </div>
                </div>
                <button onclick="toggleGuardianSidebar()" class="md:hidden text-[#4c739a] hover:text-[#0d141b]">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <nav class="flex-1 overflow-y-auto p-3 space-y-0.5">
                <a href="{{ route('guardian.dashboard') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                    {{ request()->routeIs('guardian.dashboard') ? 'bg-primary/10 text-primary' : 'text-[#4c739a] hover:bg-[#e7edf3] hover:text-[#0d141b]' }}">
                    <span class="material-symbols-outlined text-[22px]">dashboard</span>
                    Dashboard
                </a>
                <a href="{{ route('guardian.licenses.create') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                    {{ request()->routeIs('guardian.licenses.create') ? 'bg-primary/10 text-primary' : 'text-[#4c739a] hover:bg-[#e7edf3] hover:text-[#0d141b]' }}">
                    <span class="material-symbols-outlined text-[22px]">edit_note</span>
                    Pengajuan Izin
                </a>
                <a href="{{ route('guardian.licenses.index') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                    {{ request()->routeIs('guardian.licenses.index') ? 'bg-primary/10 text-primary' : 'text-[#4c739a] hover:bg-[#e7edf3] hover:text-[#0d141b]' }}">
                    <span class="material-symbols-outlined text-[22px]">history</span>
                    Riwayat Izin
                </a>
                <a href="{{ route('guardian.profile') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                    {{ request()->routeIs('guardian.profile') ? 'bg-primary/10 text-primary' : 'text-[#4c739a] hover:bg-[#e7edf3] hover:text-[#0d141b]' }}">
                    <span class="material-symbols-outlined text-[22px]">manage_accounts</span>
                    Profil Saya
                </a>
            </nav>

            <div class="p-3 border-t border-[#e7edf3] shrink-0">
                <div class="flex items-center gap-3 px-2 py-2 mb-2">
                    <div class="flex h-8 w-8 items-center justify-center overflow-hidden rounded-full bg-primary/10 text-primary font-bold text-sm shrink-0">
                        @if(Auth::guard('guardian')->user()->avatar)
                            <img src="{{ asset('storage/' . Auth::guard('guardian')->user()->avatar) }}" alt="Avatar" class="h-full w-full object-cover">
                        @else
                            {{ substr(Auth::guard('guardian')->user()->name, 0, 1) }}
                        @endif
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-sm font-semibold text-[#0d141b] truncate">{{ Auth::guard('guardian')->user()->name }}</p>
                        <p class="text-xs text-[#4c739a] truncate">{{ Auth::guard('guardian')->user()->username }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('guardian.logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium text-red-500 hover:bg-red-50 transition-colors">
                        <span class="material-symbols-outlined text-[18px]">logout</span>
                        Keluar
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main --}}
        <main class="flex-1 flex flex-col h-full overflow-hidden">

            {{-- Mobile Header --}}
            <header class="flex items-center justify-between border-b border-[#e7edf3] bg-white px-4 py-3 shrink-0 md:hidden">
                <div class="flex items-center gap-3">
                    <button onclick="toggleGuardianSidebar()" class="text-[#0d141b]">
                        <span class="material-symbols-outlined">menu</span>
                    </button>
                    <h2 class="text-[#0d141b] text-base font-bold leading-tight">
                        @yield('mobile_title', 'Portal Wali')
                    </h2>
                </div>
                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-primary/10 text-primary font-bold text-sm shrink-0">
                    {{ substr(Auth::guard('guardian')->user()->name, 0, 1) }}
                </div>
            </header>

            {{-- Scrollable Content --}}
            <div class="flex-1 overflow-y-auto p-4 sm:p-6">
                @yield('content')
            </div>
        </main>

    </div>

    <script>
        function toggleGuardianSidebar() {
            const sidebar = document.getElementById('guardianSidebar');
            const overlay = document.getElementById('guardianOverlay');
            if (sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden', 'opacity-0', 'pointer-events-none');
            } else {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('opacity-0', 'pointer-events-none');
                setTimeout(() => overlay.classList.add('hidden'), 300);
            }
        }
    </script>
    
    @stack('scripts')
    <x-cropper-modal />
</body>
</html>
