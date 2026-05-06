<!DOCTYPE html>
<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <!-- Vite HMR Support -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <title>@yield('title', 'Santri Admin')</title>
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;900&display=swap"
        rel="stylesheet" />
    <!-- Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet" />
    <!-- Tailwind CSS (CDN for V2 Styles) -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

    <!-- Choices.js -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

    <!-- Select2 for searchable dropdowns -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <style>
        /* Custom Choices.js Styles for Tailwind */
        .choices__inner {
            background-color: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 0.75rem;
            padding: 0.5rem 1rem !important;
            font-size: 1rem;
            min-height: 48px;
            display: flex;
            align-items: center;
        }

        .dark .choices__inner {
            background-color: #1e293b;
            border-color: #334155;
            color: #fff;
        }

        .choices.is-focused .choices__inner {
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        }

        .choices__list--dropdown {
            background-color: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            margin-top: 0.5rem;
            z-index: 50;
        }

        .dark .choices__list--dropdown {
            background-color: #1e293b;
            border-color: #334155;
            color: #fff;
        }

        .choices__list--dropdown .choices__item--selectable {
            padding: 0.75rem 1rem;
        }

        .choices__list--dropdown .choices__item--selectable.is-highlighted {
            background-color: #eff6ff;
            color: #1e293b;
        }

        .dark .choices__list--dropdown .choices__item--selectable.is-highlighted {
            background-color: #334155;
            color: #fff;
        }

        /* Mobile adjustments */
        @media (max-width: 640px) {
            .choices__inner {
                min-height: 44px;
                font-size: 0.875rem;
            }
        }

        /* Fix Arrow Logic: Hide default Choices.js triangle and use SVG */
        .choices[data-type*="select-one"]::after {
            display: none !important;
        }

        .choices__inner {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 1rem center;
            background-repeat: no-repeat;
            background-size: 1.25em 1.25em;
            padding-right: 3rem !important;
            /* Space for Arrow + Clear Button */
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .dark .choices__inner {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%2394a3b8' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
        }

        /* Adjust inner padding to accommodate both icons */
        .choices__inner {
            position: relative;
            /* Ensure absolute positioning works relative to this */
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            /* Increased stroke width to 2 */
            background-position: right 1rem center;
            background-repeat: no-repeat;
            background-size: 1.25em 1.25em;
            padding-right: 2.5rem !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .dark .choices__inner {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%2394a3b8' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            /* Increased stroke width to 2 */
        }

        .choices__list--single .choices__item {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 100%;
        }
    </style>
    <script>
        localStorage.removeItem("theme");
        document.documentElement.classList.remove("dark");
    </script>
    <style>
        .material-symbols-outlined.fill-1 {
            font-variation-settings: 'FILL' 1;
        }

        /* Hide Scrollbar */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            /* IE and Edge */
            scrollbar-width: none;
            /* Firefox */
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
    <!-- Alpine.js Store Setup (Head - Synchronous) -->
    <script>
        document.addEventListener('alpine:init', () => {
            // console.log('Alpine Init: Registering Stores');

            // Delete Modal Store
            Alpine.store('deleteModal', {
                show: false,
                message: '',
                form: null,

                open(form, message = 'Apakah Anda yakin ingin menghapus data ini?') {
                    this.form = form;
                    this.message = message;
                    this.show = true;
                },

                confirm() {
                    if (this.form) this.form.submit();
                    this.show = false;
                },

                cancel() {
                    this.show = false;
                    this.form = null;
                }
            });

            // Confirm Modal Store
            Alpine.store('confirmModal', {
                show: false,
                title: 'Konfirmasi',
                message: '',
                confirmText: 'Ya, Lanjutkan',
                cancelText: 'Batal',
                type: 'primary',
                form: null,

                open(form, title = 'Konfirmasi', message = 'Apakah Anda yakin?', confirmText = 'Ya, Lanjutkan', cancelText = 'Batal', type = 'primary') {
                    this.form = form;
                    this.title = title;
                    this.message = message;
                    this.confirmText = confirmText;
                    this.cancelText = cancelText;
                    this.type = type;
                    this.show = true;
                },

                confirm() {
                    if (this.form) this.form.submit();
                    this.show = false;
                },

                cancel() {
                    this.show = false;
                    this.form = null;
                }
            });

            // Image Preview Modal Store
            Alpine.store('imageModal', {
                show: false,
                imageUrl: '',
                altText: '',

                open(url, alt = 'Preview') {
                    this.imageUrl = url;
                    this.altText = alt;
                    this.show = true;
                },

                close() {
                    this.show = false;
                    setTimeout(() => {
                        this.imageUrl = '';
                        this.altText = '';
                    }, 300); // Wait for transition
                }
            });
        });
    </script>
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

        /* Hide Scrollbar */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            /* IE and Edge */
            scrollbar-width: none;
            /* Firefox */
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body x-data
    class="font-display bg-background-light dark:bg-background-dark text-[#0d141b] dark:text-slate-100 overflow-hidden">
    <div class="flex h-screen w-full overflow-hidden">
        <!-- Mobile Overlay -->
        <div id="sidebarOverlay" onclick="toggleSidebar()"
            class="fixed inset-0 bg-black/50 z-40 hidden transition-opacity opacity-0 pointer-events-none md:hidden backdrop-blur-sm">
        </div>

        <!-- Side Navigation -->
        <aside id="sidebar"
            class="fixed inset-y-0 left-0 z-50 w-64 flex flex-col h-full border-r border-[#e7edf3] dark:border-slate-800 bg-white dark:bg-slate-900 transform -translate-x-full transition-transform duration-300 md:relative md:translate-x-0 shadow-2xl md:shadow-none">

            <!-- Logo / Brand (Fixed at top) -->
            <div class="p-6 flex items-center justify-between shrink-0">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10 text-primary">
                        <span class="material-symbols-outlined">school</span>
                    </div>
                    <div class="flex flex-col">
                        <h1 class="text-[#0d141b] dark:text-white text-base font-bold leading-normal">Santri Admin
                        </h1>
                        <p class="text-[#4c739a] text-xs font-normal leading-normal">Management System</p>
                    </div>
                </div>
                <!-- Mobile Close Button -->
                <button onclick="toggleSidebar()"
                    class="md:hidden text-[#4c739a] hover:text-[#0d141b] dark:hover:text-white">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <!-- Nav Items (Scrollable area) -->
            <div class="flex-1 overflow-y-auto px-3 py-2 scroll-smooth no-scrollbar">
                <nav class="flex flex-col gap-2">
                    <div class="mb-0 space-y-0.5">
                        <p class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Menu Utama
                        </p>
                        <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('dashboard', 'home') ? 'bg-primary/10 text-primary dark:bg-primary/20 dark:text-blue-400' : 'text-[#4c739a] hover:bg-[#e7edf3] hover:text-[#0d141b] dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white' }} transition-colors"
                            href="{{ route('dashboard') }}">
                            <span
                                class="material-symbols-outlined text-[24px] {{ request()->routeIs('dashboard', 'home') ? 'fill-1' : '' }}">dashboard</span>
                            <span class="text-sm font-medium">Dashboard</span>
                        </a>

                        @if(Auth::user()->isAdmin())
                            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('users.*') ? 'bg-primary/10 text-primary dark:bg-primary/20 dark:text-blue-400' : 'text-[#4c739a] hover:bg-[#e7edf3] hover:text-[#0d141b] dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white' }} transition-colors"
                                href="{{ route('users.index') }}">
                                <span
                                    class="material-symbols-outlined text-[24px] {{ request()->routeIs('users.*') ? 'fill-1' : '' }}">manage_accounts</span>
                                <span class="text-sm font-medium">Manajemen User</span>
                            </a>
                        @endif

                        @if(Auth::user()->isAdmin() || Auth::user()->isFinanceSecretary())
                            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('students.*') ? 'bg-primary/10 text-primary dark:bg-primary/20 dark:text-blue-400' : 'text-[#4c739a] hover:bg-[#e7edf3] hover:text-[#0d141b] dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white' }} transition-colors"
                                href="{{ route('students.index') }}">
                                <span
                                    class="material-symbols-outlined text-[24px] {{ request()->routeIs('students.*') ? 'fill-1' : '' }}">group</span>
                                <span class="text-sm font-medium">Data Santri</span>
                            </a>
                        @endif
                    </div>

                    @if(Auth::user()->isAdmin())

                        <!-- Data Master Group -->
                        <div x-data="{ open: {{ (request()->is('academic-years*', 'periods*', 'education-levels*', 'rayons*', 'rooms*', 'departments*', 'memorization-types*')) ? 'true' : 'false' }} }"
                            class="mb-2">
                            <button @click="open = !open"
                                class="flex items-center justify-between w-full gap-3 px-3 py-2.5 rounded-lg text-[#4c739a] hover:bg-[#e7edf3] hover:text-[#0d141b] dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white transition-colors group">
                                <div class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-[24px]">database</span>
                                    <span class="text-sm font-medium">Data Master</span>
                                </div>
                                <span class="material-symbols-outlined text-[20px] transition-transform duration-200"
                                    :class="open ? 'rotate-180' : ''">expand_more</span>
                            </button>

                            <div x-show="open" x-collapse style="display: none;"
                                class="flex flex-col gap-1 mt-1 pl-3 border-l-2 border-slate-100 dark:border-slate-800 ml-5">
                                <a class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('academic-years.*') ? 'bg-primary/5 text-primary dark:text-blue-400' : 'text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white' }} transition-colors"
                                    href="{{ route('academic-years.index') }}">
                                    <span class="material-symbols-outlined text-[20px]">event_note</span>
                                    <span class="text-sm font-medium">Tahun Ajaran</span>
                                </a>
                                <a class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('periods.*') ? 'bg-primary/5 text-primary dark:text-blue-400' : 'text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white' }} transition-colors"
                                    href="{{ route('periods.index') }}">
                                    <span class="material-symbols-outlined text-[20px]">calendar_month</span>
                                    <span class="text-sm font-medium">Periode</span>
                                </a>
                                <a class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('education-levels.*') ? 'bg-primary/5 text-primary dark:text-blue-400' : 'text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white' }} transition-colors"
                                    href="{{ route('education-levels.index') }}">
                                    <span class="material-symbols-outlined text-[20px]">school</span>
                                    <span class="text-sm font-medium">Jenjang</span>
                                </a>
                                <a class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('departments.*') ? 'bg-primary/5 text-primary dark:text-blue-400' : 'text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white' }} transition-colors"
                                    href="{{ route('departments.index') }}">
                                    <span class="material-symbols-outlined text-[20px]">apartment</span>
                                    <span class="text-sm font-medium">Departemen</span>
                                </a>
                                <a class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('rayons.*') ? 'bg-primary/5 text-primary dark:text-blue-400' : 'text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white' }} transition-colors"
                                    href="{{ route('rayons.index') }}">
                                    <span class="material-symbols-outlined text-[20px]">domain</span>
                                    <span class="text-sm font-medium">Rayon</span>
                                </a>
                                <a class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('rooms.*') ? 'bg-primary/5 text-primary dark:text-blue-400' : 'text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white' }} transition-colors"
                                    href="{{ route('rooms.index') }}">
                                    <span class="material-symbols-outlined text-[20px]">meeting_room</span>
                                    <span class="text-sm font-medium">Kamar</span>
                                </a>
                                <a class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('memorization-types.*') ? 'bg-primary/5 text-primary dark:text-blue-400' : 'text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white' }} transition-colors"
                                    href="{{ route('memorization-types.index') }}">
                                    <span class="material-symbols-outlined text-[20px]">menu_book</span>
                                    <span class="text-sm font-medium">Ketentuan Hafalan</span>
                                </a>
                            </div>
                        </div>
                    @endif



                    @if(Auth::user()->isAdmin() || Auth::user()->isFinanceOfficer())
                        <div class="mb-2">
                            <p class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Pembayaran
                            </p>
                            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('spp-payments.*') ? 'bg-primary/10 text-primary dark:bg-primary/20 dark:text-blue-400' : 'text-[#4c739a] hover:bg-[#e7edf3] hover:text-[#0d141b] dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white' }} transition-colors"
                                href="{{ route('spp-payments.index') }}">
                                <span
                                    class="material-symbols-outlined text-[24px] {{ request()->routeIs('spp-payments.*') ? 'fill-1' : '' }}">payments</span>
                                <span class="text-sm font-medium">Pembayaran SPP</span>
                            </a>
                        </div>
                    @endif

                    @if(Auth::user()->isAdmin() || Auth::user()->isDepartmentOfficer())
                        <div class="mb-2">
                            <p class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Pelanggaran
                            </p>
                            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('violations.*') ? 'bg-primary/10 text-primary dark:bg-primary/20 dark:text-blue-400' : 'text-[#4c739a] hover:bg-[#e7edf3] hover:text-[#0d141b] dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white' }} transition-colors"
                                href="{{ route('violations.index') }}">
                                <span
                                    class="material-symbols-outlined text-[24px] {{ request()->routeIs('violations.*') ? 'fill-1' : '' }}">gavel</span>
                                <span class="text-sm font-medium">Catat Pelanggaran</span>
                            </a>
                            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('violation-types.*') ? 'bg-primary/10 text-primary dark:bg-primary/20 dark:text-blue-400' : 'text-[#4c739a] hover:bg-[#e7edf3] hover:text-[#0d141b] dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white' }} transition-colors"
                                href="{{ route('violation-types.index') }}">
                                <span
                                    class="material-symbols-outlined text-[24px] {{ request()->routeIs('violation-types.*') ? 'fill-1' : '' }}">category</span>
                            </a>
                        </div>
                    @endif

                    @if(Auth::user()->isAdmin() || Auth::user()->isMemorizationOfficer())
                        <div class="mb-2">
                            <p class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Hafalan</p>
                            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('memorization.*') ? 'bg-primary/10 text-primary dark:bg-primary/20 dark:text-blue-400' : 'text-[#4c739a] hover:bg-[#e7edf3] hover:text-[#0d141b] dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white' }} transition-colors"
                                href="{{ route('memorization.index') }}">
                                <span
                                    class="material-symbols-outlined text-[24px] {{ request()->routeIs('memorization.*') ? 'fill-1' : '' }}">auto_stories</span>
                                <span class="text-sm font-medium">Cek Hafalan Santri</span>
                            </a>
                        </div>
                    @endif



                    {{-- Licensing Menu --}}
                    @if(Auth::user()->isAdmin() || Auth::user()->isLicensingOfficer())
                        <div class="mb-2">
                            <p class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Perizinan</p>
                            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('licenses.*') ? 'bg-primary/10 text-primary dark:bg-primary/20 dark:text-blue-400' : 'text-[#4c739a] hover:bg-[#e7edf3] hover:text-[#0d141b] dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white' }} transition-colors"
                                href="{{ route('licenses.index') }}">
                                <span
                                    class="material-symbols-outlined text-[24px] {{ request()->routeIs('licenses.*') ? 'fill-1' : '' }}">assignment_turned_in</span>
                                <span class="text-sm font-medium">Validasi Pulang</span>
                            </a>
                            {{-- Event Liburan Removed --}}
                        </div>
                    @endif
                </nav>
            </div>

            <!-- User Profile (MOBILE ONLY) -->
            <div class="p-4 border-t border-[#e7edf3] dark:border-slate-800 shrink-0 lg:hidden user-profile-mobile">
                <div class="flex items-center gap-3 p-2 rounded-lg transition-colors">
                    <div
                        class="flex h-9 w-9 items-center justify-center rounded-full bg-primary/10 text-primary font-bold text-sm">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div class="flex flex-col overflow-hidden">
                        <p class="text-[#0d141b] dark:text-white text-sm font-medium truncate">{{ Auth::user()->name }}
                        </p>
                        <p class="text-[#4c739a] text-xs truncate">{{ Auth::user()->email }}</p>
                    </div>
                </div>
                <!-- Mobile Actions -->
                <div class="mt-2 grid grid-cols-1 gap-1">
                    <a href="{{ route('password.change') }}"
                        class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-[#4c739a] hover:bg-[#e7edf3] hover:text-[#0d141b] dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white transition-colors text-sm font-medium">
                        <span class="material-symbols-outlined text-[20px]">key</span>
                        <span>Ganti Password</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-red-600 hover:bg-red-50 dark:hover:bg-red-900/10 transition-colors text-sm font-medium">
                            <span class="material-symbols-outlined text-[20px]">logout</span>
                            <span>Keluar</span>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content Wrapper -->
        <main class="flex-1 flex flex-col h-full relative overflow-hidden">
            <!-- Top Navbar -->
            <header
                class="flex items-center justify-between whitespace-nowrap border-b border-[#e7edf3] dark:border-slate-800 bg-white dark:bg-slate-900 px-6 py-3 shrink-0">
                <div class="flex items-center gap-4 lg:hidden">
                    <button onclick="toggleSidebar()" class="text-[#0d141b] dark:text-white">
                        <span class="material-symbols-outlined">menu</span>
                    </button>
                    <h2 class="text-[#0d141b] dark:text-white text-lg font-bold leading-tight">
                        @yield('mobile_title', 'Santri Management')</h2>
                </div>
                <!-- Breadcrumbs -->
                <div class="hidden lg:flex items-center gap-2">
                    <a class="text-[#4c739a] text-sm font-medium hover:text-primary transition-colors"
                        href="{{ route('dashboard') }}">Beranda</a>
                    <span class="text-[#4c739a] text-sm">/</span>

                    @if(View::hasSection('breadcrumb_parent'))
                        <a class="text-[#4c739a] text-sm font-medium hover:text-primary transition-colors"
                            href="{{ route(View::yieldContent('breadcrumb_parent_route')) }}">
                            @yield('breadcrumb_parent')
                        </a>
                        <span class="text-[#4c739a] text-sm">/</span>
                    @endif

                    <span class="text-[#0d141b] dark:text-white text-sm font-medium">@yield('breadcrumb', 'Page')</span>
                </div>
                <div class="flex flex-1 justify-end gap-3 items-center">
                    <button
                        class="hidden sm:flex items-center justify-center rounded-full size-10 hover:bg-[#e7edf3] dark:hover:bg-slate-800 text-[#4c739a]">
                        <span class="material-symbols-outlined">notifications</span>
                    </button>

                    <!-- User Profile Dropdown (DESKTOP ONLY) -->
                    <div class="hidden lg:flex items-center gap-3 pl-3 border-l border-[#e7edf3] dark:border-slate-800"
                        x-data="{ open: false }">
                        <div class="text-right hidden xl:block">
                            <p class="text-sm font-bold text-[#0d141b] dark:text-white">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-[#4c739a]">{{ Auth::user()->email }}</p>
                        </div>
                        <div class="relative">
                            <button @click="open = !open" @click.away="open = false"
                                class="flex h-10 w-10 items-center justify-center rounded-full bg-primary/10 text-primary font-bold text-sm hover:ring-2 hover:ring-primary/20 transition-all">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="open" x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="absolute right-0 mt-2 w-48 origin-top-right rounded-xl bg-white dark:bg-slate-900 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none z-50 border border-[#e7edf3] dark:border-slate-800"
                                style="display: none;">
                                <div class="p-1">
                                    <a href="{{ route('password.change') }}"
                                        class="flex w-full items-center gap-2 px-4 py-2 text-sm text-[#0d141b] dark:text-white hover:bg-slate-50 dark:hover:bg-slate-800 rounded-lg transition-colors">
                                        <span class="material-symbols-outlined text-[18px]">key</span>
                                        Ganti Password
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                            class="flex w-full items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/10 rounded-lg transition-colors">
                                            <span class="material-symbols-outlined text-[18px]">logout</span>
                                            Keluar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Scrollable Content -->
            <div class="flex-1 overflow-y-auto p-6 scroll-smooth">
                <div class="max-w-[1200px] mx-auto flex flex-col gap-6">
                    @yield('content')
                </div>
            </div>
        </main>
    </div>

    <!-- Sidebar Script -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');

            if (sidebar.classList.contains('-translate-x-full')) {
                // Open sidebar
                sidebar.classList.remove('-translate-x-full');

                // Show overlay
                overlay.classList.remove('hidden', 'opacity-0', 'pointer-events-none');
            } else {
                // Close sidebar
                sidebar.classList.add('-translate-x-full');

                // Hide overlay
                overlay.classList.add('opacity-0', 'pointer-events-none');
                setTimeout(() => {
                    overlay.classList.add('hidden');
                }, 300); // Wait for transition
            }
        }
    </script>

    {{-- Delete Confirmation Modal --}}
    <div x-data @keydown.escape.window="$store.deleteModal.cancel()" x-show="$store.deleteModal.show" x-cloak
        class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        {{-- Backdrop --}}
        <div x-show="$store.deleteModal.show" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" @click="$store.deleteModal.cancel()"
            class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm">
        </div>

        {{-- Modal --}}
        <div class="flex min-h-full items-center justify-center p-4">
            <div x-show="$store.deleteModal.show" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95" @click.stop
                class="relative bg-white dark:bg-slate-800 rounded-2xl shadow-2xl max-w-md w-full p-6">

                {{-- Icon --}}
                <div
                    class="flex items-center justify-center w-12 h-12 mx-auto mb-4 rounded-full bg-red-100 dark:bg-red-900/30">
                    <span class="material-symbols-outlined text-red-600 dark:text-red-400 text-[28px]">warning</span>
                </div>

                {{-- Title --}}
                <h3 class="text-lg font-bold text-center text-slate-900 dark:text-white mb-2">
                    Konfirmasi Hapus
                </h3>

                {{-- Message --}}
                <p class="text-sm text-center text-slate-600 dark:text-slate-400 mb-6"
                    x-text="$store.deleteModal.message"></p>

                {{-- Buttons --}}
                <div class="flex gap-3">
                    <button @click="$store.deleteModal.cancel()" type="button"
                        class="flex-1 px-4 py-2.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 font-semibold hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                        Batal
                    </button>
                    <button @click="$store.deleteModal.confirm()" type="button"
                        class="flex-1 px-4 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white font-semibold shadow-lg hover:shadow-xl transition-all">
                        Hapus
                    </button>
                </div>
            </div>
        </div>

        {{-- Generic Confirmation Modal --}}
        <div x-data @keydown.escape.window="$store.confirmModal.cancel()" x-show="$store.confirmModal.show" x-cloak
            class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            {{-- Backdrop --}}
            <div x-show="$store.confirmModal.show" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0" @click="$store.confirmModal.cancel()"
                class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm">
            </div>

            {{-- Modal --}}
            <div class="flex min-h-full items-center justify-center p-4">
                <div x-show="$store.confirmModal.show" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                    @click.stop class="relative bg-white dark:bg-slate-800 rounded-2xl shadow-2xl max-w-md w-full p-6">

                    {{-- Icon --}}
                    <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 rounded-full" :class="{
                    'bg-blue-100 dark:bg-blue-900/30': $store.confirmModal.type === 'primary',
                    'bg-red-100 dark:bg-red-900/30': $store.confirmModal.type === 'danger',
                    'bg-amber-100 dark:bg-amber-900/30': $store.confirmModal.type === 'warning'
                }">
                        <span class="material-symbols-outlined text-[28px]" :class="{
                        'text-blue-600 dark:text-blue-400': $store.confirmModal.type === 'primary',
                        'text-red-600 dark:text-red-400': $store.confirmModal.type === 'danger',
                        'text-amber-600 dark:text-amber-400': $store.confirmModal.type === 'warning'
                    }" x-text="$store.confirmModal.type === 'danger' ? 'warning' : 'info'">
                        </span>
                    </div>

                    {{-- Title --}}
                    <h3 class="text-lg font-bold text-center text-slate-900 dark:text-white mb-2"
                        x-text="$store.confirmModal.title">
                    </h3>

                    {{-- Message --}}
                    <p class="text-sm text-center text-slate-600 dark:text-slate-400 mb-6"
                        x-text="$store.confirmModal.message"></p>

                    {{-- Buttons --}}
                    <div class="flex gap-3">
                        <button @click="$store.confirmModal.cancel()" type="button"
                            class="flex-1 px-4 py-2.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 font-semibold hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors"
                            x-text="$store.confirmModal.cancelText">
                        </button>
                        <button @click="$store.confirmModal.confirm()" type="button"
                            class="flex-1 px-4 py-2.5 rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all text-white"
                            :class="{
                        'bg-primary hover:bg-primary-dark': $store.confirmModal.type === 'primary',
                        'bg-red-600 hover:bg-red-700': $store.confirmModal.type === 'danger',
                        'bg-amber-500 hover:bg-amber-600': $store.confirmModal.type === 'warning'
                    }" x-text="$store.confirmModal.confirmText">
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Image Preview Modal --}}
        <div x-data @keydown.escape.window="$store.imageModal.close()" x-show="$store.imageModal.show" x-cloak
            class="fixed inset-0 z-[60] overflow-y-auto" style="display: none;">
            {{-- Backdrop --}}
            <div x-show="$store.imageModal.show" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0" @click="$store.imageModal.close()"
                class="fixed inset-0 bg-black/90 backdrop-blur-sm">
            </div>

            {{-- Close Button (Fixed) --}}
            <button @click="$store.imageModal.close()" x-show="$store.imageModal.show"
                x-transition:enter="transition ease-out duration-300 delay-100"
                x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                class="fixed top-6 right-6 z-[70] text-white/50 hover:text-white transition-colors p-2 rounded-full hover:bg-white/10">
                <span class="material-symbols-outlined text-[40px]">close</span>
            </button>

            {{-- Modal Content --}}
            <div class="flex min-h-screen items-center justify-center p-4 text-center">
                <div x-show="$store.imageModal.show" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90"
                    @click.stop class="relative max-w-5xl w-full inline-block align-middle">

                    {{-- Image --}}
                    <img :src="$store.imageModal.imageUrl" :alt="$store.imageModal.altText"
                        class="w-auto h-auto max-w-full max-h-[85vh] object-contain rounded-lg shadow-2xl ring-1 ring-white/10 mx-auto">

                    {{-- Caption (Optional) --}}
                    <p class="text-white/80 text-center mt-4 text-base font-medium uppercase tracking-wide"
                        x-text="$store.imageModal.altText"></p>
                </div>
            </div>
        </div>


        <!-- Page-specific Scripts -->
        @stack('scripts')

        <!-- Alpine.js Setup & Core (Loaded at bottom for performance & stability) -->

        <!-- WhatsApp Notification Popup -->
        @if(session('wa_url'))
            <div x-data="{ 
                                                                    open: true, 
                                                                    url: '{{ session('wa_url') }}' 
                                                                 }" x-show="open" x-cloak
                class="fixed inset-0 z-[100] overflow-y-auto" style="display: none;">

                <!-- Backdrop -->
                <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="open = false"></div>

                <!-- Modal -->
                <div class="flex min-h-full items-center justify-center p-4">
                    <div class="relative bg-white dark:bg-slate-800 rounded-2xl shadow-2xl max-w-sm w-full p-6 text-center">

                        <div
                            class="flex items-center justify-center w-12 h-12 mx-auto mb-4 rounded-full bg-green-100 dark:bg-green-900/30">
                            <span
                                class="material-symbols-outlined text-green-600 dark:text-green-400 text-[28px]">chat</span>
                        </div>

                        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">Kirim Notifikasi WA?</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400 mb-6">Data berhasil disimpan. Apakah Anda ingin
                            mengirim notifikasi ke Orang Tua?</p>

                        <div class="flex gap-3">
                            <button @click="open = false"
                                class="flex-1 px-4 py-2.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 font-semibold hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                                Nanti Saja
                            </button>
                            <a :href="url" target="_blank" @click="open = false"
                                class="flex-1 px-4 py-2.5 rounded-xl bg-green-600 hover:bg-green-700 text-white font-semibold shadow-lg hover:shadow-xl transition-all flex items-center justify-center gap-2">
                                <span>Kirim</span>
                                <span class="material-symbols-outlined text-[18px]">send</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
</body>

</html>

<script src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>

</html>