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
    <nav
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
                    <div class="hidden lg:ml-10 lg:flex lg:space-x-8">
                        <a href="{{ route('dashboard') }}"
                            class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }} px-3 py-2 text-sm font-medium flex items-center text-white">
                            <i class="fas fa-home mr-2"></i> Dashboard
                        </a>
                        <a href="{{ route('santri.index') }}"
                            class="nav-link {{ request()->routeIs('santri.*') ? 'active' : '' }} px-3 py-2 text-sm font-medium flex items-center text-white">
                            <i class="fas fa-users mr-2"></i> Data Santri
                        </a>
                        <a href="{{ route('kriteria.index') }}"
                            class="nav-link {{ request()->routeIs('kriteria.*') ? 'active' : '' }} px-3 py-2 text-sm font-medium flex items-center text-white">
                            <i class="fas fa-list-check mr-2"></i> Kriteria
                        </a>
                        <a href="{{ route('perhitungan.index') }}"
                            class="nav-link {{ request()->routeIs('perhitungan.index', 'perhitungan.hitung', 'perhitungan.hasil') ? 'active' : '' }} px-3 py-2 text-sm font-medium flex items-center text-white">
                            <i class="fas fa-calculator mr-2"></i> Perhitungan SMART
                        </a>
                        <a href="{{ route('perhitungan.rekomendasi') }}"
                            class="nav-link {{ request()->routeIs('perhitungan.rekomendasi') ? 'active' : '' }} px-3 py-2 text-sm font-medium flex items-center text-white">
                            <i class="fas fa-award mr-2"></i> Rekomendasi
                        </a>
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
                                class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white focus:outline-none"
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

                        <!-- <div class="ml-3 relative">
                            <div class="flex items-center">
                                <span class="text-sm font-medium text-white mr-2">Admin</span>
                                <div
                                    class="h-8 w-8 rounded-full bg-[var(--color-primary-400)] flex items-center justify-center">
                                    <i class="fas fa-user text-white"></i>
                                </div>
                            </div>
                        </div> -->
                    </div>
                </div>
                <!-- Mobile menu button -->
                <div class="-mr-2 flex lg:hidden">
                    <button type="button" id="mobile-menu-button"
                        class="inline-flex items-center justify-center p-2 rounded-md text-white hover:bg-white/30 focus:outline-none">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>
        </div>
        <!-- Mobile menu -->
        <div class="lg:hidden hidden" id="mobile-menu">
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
                <a href="{{ route('perhitungan.index') }}"
                    class="block px-3 py-2 rounded-md text-base font-medium text-white hover:bg-white/10 {{ request()->routeIs('perhitungan.index', 'perhitungan.hitung', 'perhitungan.hasil') ? 'bg-white/20' : '' }}">
                    <i class="fas fa-calculator mr-2"></i> Perhitungan SMART
                </a>
                <a href="{{ route('perhitungan.rekomendasi') }}"
                    class="block px-3 py-2 rounded-md text-base font-medium text-white hover:bg-white/10 {{ request()->routeIs('perhitungan.rekomendasi') ? 'bg-white/20' : '' }}">
                    <i class="fas fa-award mr-2"></i> Rekomendasi
                </a>
            </div>
            <div class="pt-4 pb-4 border-t border-white/20">
                <div class="px-2 space-y-1">
                    <p class="px-3 text-xs font-semibold text-white uppercase tracking-wider mb-2 opacity-80">
                        Ganti Tema
                    </p>
                    <a href="{{ route('theme.set', 'default') }}"
                        class="block px-3 py-2 rounded-md text-base font-medium text-white hover:bg-white/10 {{ session('theme', 'default') == 'default' ? 'bg-white/20' : '' }}">
                        <i class="fas fa-circle text-emerald-300 mr-2"></i> Default (Hijau)
                    </a>
                    <a href="{{ route('theme.set', 'blue') }}"
                        class="block px-3 py-2 rounded-md text-base font-medium text-white hover:bg-white/10 {{ session('theme') == 'blue' ? 'bg-white/20' : '' }}">
                        <i class="fas fa-circle text-blue-300 mr-2"></i> Biru (Langit)
                    </a>
                    <a href="{{ route('theme.set', 'purple') }}"
                        class="block px-3 py-2 rounded-md text-base font-medium text-white hover:bg-white/10 {{ session('theme') == 'purple' ? 'bg-white/20' : '' }}">
                        <i class="fas fa-circle text-purple-300 mr-2"></i> Ungu (Royal)
                    </a>
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
        // Mobile menu toggle
        document.getElementById('mobile-menu-button').addEventListener('click', function () {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
            menu.classList.toggle('block');
        });

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