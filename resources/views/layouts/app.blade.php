<!DOCTYPE html>
<html lang="en" class="scroll-smooth" data-theme="{{ session('theme', 'default') }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SPK Kepulangan Santri P2AL II')</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg-base);
            min-height: 100vh;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
        }

        .glass-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px -5px rgba(0, 0, 0, 0.1);
        }

        .nav-link {
            position: relative;
            transition: all 0.3s ease;
        }

        .nav-link:after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 50%;
            background-color: white;
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .nav-link:hover:after {
            width: 70%;
        }

        .nav-link.active {
            font-weight: 600;
        }

        .nav-link.active:after {
            width: 70%;
            background-color: white;
        }
    </style>
</head>

<body class="min-h-screen flex flex-col">
    <nav x-data="{ mobileMenuOpen: false }" @click.outside="mobileMenuOpen = false"
        class="bg-gradient-to-r from-[var(--gradient-from)] to-[var(--gradient-to)] shadow-lg sticky top-0 z-50 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center mr-3 p-1">
                            <img src="{{ asset('favicon.png') }}" alt="Logo PP Annuqayah"
                                class="w-full h-full object-contain">
                        </div>
                        <span
                            class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-white to-[var(--color-primary-100)]">SPK
                            P2AL II</span>
                    </div>
                    <div class="hidden lg:ml-6 lg:flex lg:space-x-4">
                        <a href="{{ route('dashboard') }}"
                            class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }} px-3 py-2 text-sm font-medium flex items-center text-white whitespace-nowrap">
                            <i class="fas fa-home mr-2"></i> Dashboard
                        </a>
                        <a href="{{ route('santri.index') }}"
                            class="nav-link {{ request()->routeIs('santri.*') ? 'active' : '' }} px-3 py-2 text-sm font-medium flex items-center text-white whitespace-nowrap">
                            <i class="fas fa-users mr-2"></i> Data Santri
                        </a>

                        <a href="{{ route('kriteria.index') }}"
                            class="nav-link {{ request()->routeIs('kriteria.*') ? 'active' : '' }} px-3 py-2 text-sm font-medium flex items-center text-white whitespace-nowrap">
                            <i class="fas fa-list-check mr-2"></i> Kriteria
                        </a>
                        <a href="{{ route('periode.index') }}"
                            class="nav-link {{ request()->routeIs('periode.*') ? 'active' : '' }} px-3 py-2 text-sm font-medium flex items-center text-white whitespace-nowrap">
                            <i class="fas fa-calendar-alt mr-2"></i> Periode
                        </a>

                        <!-- SPK SMART Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" @click.away="open = false"
                                class="nav-link {{ request()->routeIs('perhitungan.*') ? 'active' : '' }} px-3 py-2 text-sm font-medium flex items-center text-white whitespace-nowrap focus:outline-none">
                                <i class="fas fa-calculator mr-2"></i> SPK SMART <i
                                    class="fas fa-chevron-down ml-1 text-xs"></i>
                            </button>
                            <div x-show="open"
                                class="origin-top-left absolute left-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-gray-200 ring-opacity-5 focus:outline-none z-50"
                                style="display: none;" x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95">
                                <a href="{{ route('perhitungan.index') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('perhitungan.index', 'perhitungan.hitung', 'perhitungan.hasil') ? 'bg-gray-50 text-[var(--color-primary-600)] font-semibold' : '' }}">
                                    <i class="fas fa-calculator w-5 mr-1 text-gray-400"></i> Hitung
                                </a>
                                <a href="{{ route('perhitungan.rekomendasi') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('perhitungan.rekomendasi') ? 'bg-gray-50 text-[var(--color-primary-600)] font-semibold' : '' }}">
                                    <i class="fas fa-award w-5 mr-1 text-gray-400"></i> Rekomendasi
                                </a>
                                <a href="{{ route('perhitungan.history') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('perhitungan.history') ? 'bg-gray-50 text-[var(--color-primary-600)] font-semibold' : '' }}">
                                    <i class="fas fa-history w-5 mr-1 text-gray-400"></i> Riwayat
                                </a>
                                <a href="{{ route('sensitivity.index') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('sensitivity.*') ? 'bg-gray-50 text-[var(--color-primary-600)] font-semibold' : '' }}">
                                    <i class="fas fa-chart-line w-5 mr-1 text-gray-400"></i> Analisis Sensitivitas
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="hidden lg:flex items-center">
                    <div class="ml-4 flex items-center md:ml-6">
                        {{-- Theme Switcher Desktop --}}
                        <div class="relative ml-3" x-data="{ open: false }">
                            <button @click="open = !open" @click.away="open = false" type="button"
                                class="px-2 py-1 rounded-full text-white hover:text-white focus:outline-none">
                                <span class="sr-only">Ganti Tema</span>
                                <i class="fas fa-palette text-xl"></i>
                            </button>
                            <div x-show="open"
                                class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white focus:outline-none ring-1 ring-gray-200 ring-opacity-5"
                                style="display: none;">
                                <a href="{{ route('theme.set', 'default') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-circle text-green-600 mr-2"></i> Default (Hijau)
                                </a>
                                <a href="{{ route('theme.set', 'blue') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-circle text-blue-600 mr-2"></i> Biru (Langit)
                                </a>
                                <a href="{{ route('theme.set', 'purple') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-circle text-purple-600 mr-2"></i> Ungu (Royal)
                                </a>
                            </div>
                        </div>

                        <div class="ml-3 relative" x-data="{ open: false }">
                            <div>
                                <button @click="open = !open" @click.away="open = false" type="button"
                                    class="max-w-xs rounded-full flex items-center text-sm focus:outline-none"
                                    id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                    <span class="sr-only">Open user menu</span>
                                    <div
                                        class="h-8 w-8 rounded-full bg-white/20 flex items-center justify-center text-white">
                                        <i class="fas fa-user"></i>
                                    </div>
                                </button>
                            </div>
                            <div x-show="open"
                                class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg py-1 bg-white focus:outline-none ring-1 ring-gray-200 ring-opacity-5"
                                role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1"
                                style="display: none;" x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95">

                                <div class="px-4 py-3 border-b border-gray-100">
                                    <p class="text-sm text-gray-900 font-semibold">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                                </div>

                                <a href="{{ route('password.change') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 items-center">
                                    <i class="fas fa-key w-5 text-gray-400 mr-2"></i> Ganti Password
                                </a>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 items-center"
                                        role="menuitem" tabindex="-1" id="user-menu-item-2">
                                        <i class="fas fa-sign-out-alt w-5 text-red-500 mr-2"></i> Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Mobile menu button -->
                <div class="-mr-2 flex lg:hidden">
                    <button type="button" @click="mobileMenuOpen = !mobileMenuOpen"
                        class="inline-flex items-center justify-center p-2 rounded-md text-white hover:bg-white/30 focus:outline-none transition-colors duration-300 text-xl"
                        aria-controls="mobile-menu" :aria-expanded="mobileMenuOpen">
                        <i class="fas" :class="mobileMenuOpen ? 'fa-times' : 'fa-bars'"></i>
                    </button>
                </div>
            </div>
        </div>
        <!-- Mobile menu -->
        <div class="lg:hidden" id="mobile-menu" x-show="mobileMenuOpen" style="display: none;"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2"
            class="max-h-[calc(100vh-5rem)] overflow-y-auto custom-scrollbar">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                <a href="{{ route('dashboard') }}"
                    class="block px-3 py-2 rounded-md text-base font-medium text-white hover:bg-white/10 {{ request()->routeIs('dashboard') ? 'bg-white/20' : '' }}">
                    <i class="fas fa-home mr-2"></i> Dashboard
                </a>
                <a href="{{ route('santri.index') }}"
                    class="block px-3 py-2 rounded-md text-base font-medium text-white hover:bg-white/10 {{ request()->routeIs('santri.*') ? 'bg-white/20' : '' }}">
                    <i class="fas fa-users mr-2"></i> Data Santri
                </a>
                <a href="{{ route('kriteria.index') }}"
                    class="block px-3 py-2 rounded-md text-base font-medium text-white hover:bg-white/10 {{ request()->routeIs('kriteria.*') ? 'bg-white/20' : '' }}">
                    <i class="fas fa-list-check mr-2"></i> Kriteria
                </a>
                <a href="{{ route('periode.index') }}"
                    class="block px-3 py-2 rounded-md text-base font-medium text-white hover:bg-white/10 {{ request()->routeIs('periode.*') ? 'bg-white/20' : '' }}">
                    <i class="fas fa-calendar-alt mr-2"></i> Periode
                </a>

                <!-- SPK SMART Mobile -->
                <div x-data="{ open: false }">
                    <button @click="open = !open" type="button"
                        class="w-full flex justify-between items-center px-3 py-2 rounded-md text-base font-medium text-white hover:bg-white/10 focus:outline-none {{ request()->routeIs('perhitungan.*') ? 'bg-white/10' : '' }}">
                        <span class="flex items-center">
                            <i class="fas fa-calculator mr-2"></i> SPK SMART
                        </span>
                        <i class="fas fa-chevron-down text-xs transition-transform duration-200"
                            :class="{ 'rotate-180': open }"></i>
                    </button>
                    <div x-show="open" class="pl-4 space-y-1 mt-1" style="display: none;" x-collapse>
                        <a href="{{ route('perhitungan.index') }}"
                            class="block px-3 py-2 rounded-md text-sm font-medium text-white hover:bg-white/10 {{ request()->routeIs('perhitungan.index', 'perhitungan.hitung', 'perhitungan.hasil') ? 'bg-white/20' : '' }}">
                            <i class="fas fa-calculator mr-2"></i> Hitung
                        </a>
                        <a href="{{ route('perhitungan.rekomendasi') }}"
                            class="block px-3 py-2 rounded-md text-sm font-medium text-white hover:bg-white/10 {{ request()->routeIs('perhitungan.rekomendasi') ? 'bg-white/20' : '' }}">
                            <i class="fas fa-award mr-2"></i> Rekomendasi
                        </a>
                        <a href="{{ route('perhitungan.history') }}"
                            class="block px-3 py-2 rounded-md text-sm font-medium text-white hover:bg-white/10 {{ request()->routeIs('perhitungan.history') ? 'bg-white/20' : '' }}">
                            <i class="fas fa-history mr-2"></i> Riwayat
                        </a>
                        <a href="{{ route('sensitivity.index') }}"
                            class="block px-3 py-2 rounded-md text-sm font-medium text-white hover:bg-white/10 {{ request()->routeIs('sensitivity.*') ? 'bg-white/20' : '' }}">
                            <i class="fas fa-chart-line mr-2"></i> Analisis Sensitivitas
                        </a>
                    </div>
                </div>

                <!-- Theme Switcher Submenu -->
                <div x-data="{ open: false }">
                    <button @click="open = !open" type="button"
                        class="w-full flex justify-between items-center px-3 py-2 rounded-md text-base font-medium text-white hover:bg-white/10 focus:outline-none">
                        <span class="flex items-center">
                            <i class="fas fa-palette mr-2"></i> Ganti Tema
                        </span>
                        <i class="fas fa-chevron-down text-xs transition-transform duration-200"
                            :class="{ 'rotate-180': open }"></i>
                    </button>
                    <div x-show="open" class="pl-4 space-y-1 mt-1" style="display: none;" x-collapse>
                        <a href="{{ route('theme.set', 'default') }}"
                            class="block px-3 py-2 rounded-md text-sm font-medium text-white hover:bg-white/10 {{ session('theme', 'default') == 'default' ? 'bg-white/20' : '' }}">
                            <i class="fas fa-circle text-emerald-300 mr-2"></i> Default (Hijau)
                        </a>
                        <a href="{{ route('theme.set', 'blue') }}"
                            class="block px-3 py-2 rounded-md text-sm font-medium text-white hover:bg-white/10 {{ session('theme') == 'blue' ? 'bg-white/20' : '' }}">
                            <i class="fas fa-circle text-blue-300 mr-2"></i> Biru (Langit)
                        </a>
                        <a href="{{ route('theme.set', 'purple') }}"
                            class="block px-3 py-2 rounded-md text-sm font-medium text-white hover:bg-white/10 {{ session('theme') == 'purple' ? 'bg-white/20' : '' }}">
                            <i class="fas fa-circle text-purple-300 mr-2"></i> Ungu (Royal)
                        </a>
                    </div>
                </div>
            </div>
            <div class="pt-4 pb-4 border-t border-white/20">
                <div class="flex items-center px-4 mb-3">
                    <div class="flex-shrink-0">
                        <div class="h-10 w-10 rounded-full bg-white/20 flex items-center justify-center text-white">
                            <i class="fas fa-user text-lg"></i>
                        </div>
                    </div>
                    <div class="ml-3">
                        <div class="text-base font-medium text-white">{{ Auth::user()->name }}</div>
                        <div class="text-sm font-medium text-white/70 truncate max-w-[150px]">{{ Auth::user()->email }}
                        </div>
                    </div>
                </div>
                <div class="px-2 space-y-1">
                    <a href="{{ route('password.change') }}"
                        class="block px-3 py-2 rounded-md text-base font-medium text-white hover:bg-white/10 {{ request()->routeIs('password.change') ? 'bg-white/20' : '' }}">
                        <i class="fas fa-key mr-2"></i> Ganti Password
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="block w-full text-left px-3 py-2 rounded-md text-base font-medium text-white hover:bg-white/10 text-red-200 hover:text-red-100">
                            <i class="fas fa-sign-out-alt mr-2"></i> Keluar
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </nav>

    <main class="flex-grow py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @yield('content')
        </div>
    </main>

    <footer class="bg-white/80 backdrop-blur-md border-t border-gray-100 mt-12">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row justify-between items-center">
                <div class="flex items-center mb-4 lg:mb-0">
                    <div
                        class="w-8 h-8 rounded-lg bg-[var(--color-primary-100)] flex items-center justify-center mr-3 p-1">
                        <img src="{{ asset('favicon.png') }}" alt="Logo PP Annuqayah"
                            class="w-full h-full object-contain">
                    </div>
                    <span class="text-sm font-medium text-gray-700">SPK P2AL II</span>
                </div>
                <p class="text-sm text-center lg:text-right text-gray-500">
                    &copy; {{ date('Y') }} Sistem Pendukung Keputusan Kepulangan Santri P2AL II Menggunakan Metode
                    <b>SMART</b>
                </p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Mobile menu toggle logic is now handled by Alpine.js in the nav element

        // Add active class to current nav item
        document.addEventListener('DOMContentLoaded', function () {
            const currentPath = window.location.pathname;
            const navLinks = document.querySelectorAll('.nav-link');

            navLinks.forEach(link => {
                if (link.getAttribute('href') === currentPath) {
                    link.classList.add('active');
                }
            });

            // SweetAlert for Flash Messages
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    timer: 3000,
                    showConfirmButton: false
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: "{{ session('error') }}",
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Tutup'
                });
            @endif

            // Global Delete Confirmation
            document.body.addEventListener('submit', function (e) {
                if (e.target.classList.contains('delete-form')) {
                    e.preventDefault();
                    const form = e.target;

                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Data yang dihapus tidak dapat dikembalikan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                }
            });
        });
    </script>
</body>

</html>