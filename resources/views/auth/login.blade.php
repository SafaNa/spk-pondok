<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SPK Santri P2AL II</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .login-bg {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            position: relative;
            overflow: hidden;
        }

        .login-bg::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }

        /* Hide scrollbar for Chrome, Safari and Opera */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        /* Hide scrollbar for IE, Edge and Firefox */
        .no-scrollbar {
            -ms-overflow-style: none;
            /* IE and Edge */
            scrollbar-width: none;
            /* Firefox */
        }
    </style>
</head>

<body class="h-screen flex items-stretch overflow-hidden">
    <!-- Left Side - Form -->
    <div class="flex-1 flex items-center justify-center p-6 bg-white relative z-10">
        <div class="w-full max-w-sm">
            <div class="text-center mb-6">
                <div
                    class="inline-flex items-center justify-center h-16 w-16 rounded-2xl bg-emerald-50 text-emerald-600 mb-4 p-2 shadow-sm">
                    <img src="{{ asset('favicon.png') }}" alt="Logo" class="h-full w-full object-contain">
                </div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Selamat Datang</h1>
                <p class="mt-2 text-sm text-gray-500">
                    Sistem Pendukung Keputusan Kepulangan Santri
                    <br class="hidden sm:block">
                    Pondok Pesantren Annuqayah Latee II
                </p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <div class="space-y-1">
                    <label for="email" class="block text-xs font-medium text-gray-700">Email Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <input type="email" name="email" id="email" required autofocus placeholder="masukan email anda"
                            class="block w-full pl-9 pr-3 py-2.5 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm transition duration-200">
                    </div>
                    @error('email')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-1">
                    <label for="password" class="block text-xs font-medium text-gray-700">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input type="password" name="password" id="password" required placeholder="••••••••"
                            class="block w-full pl-9 pr-3 py-2.5 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm transition duration-200">
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember_me" type="checkbox" name="remember"
                            class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded cursor-pointer">
                        <label for="remember_me" class="ml-2 block text-xs text-gray-600 cursor-pointer">
                            Ingat Saya
                        </label>
                    </div>
                </div>

                <button type="submit"
                    class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-lg text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-1 focus:ring-offset-1 focus:ring-emerald-500 transform transition-all duration-200 hover:-translate-y-0.5 cursor-pointer">
                    Masuk ke Sistem
                </button>
            </form>

            <div class="mt-6 border-t border-gray-100 pt-4 text-center">
                <p class="text-xs text-gray-400">
                    &copy; {{ date('Y') }} Pondok Pesantren Annuqayah Latee II.
                </p>
            </div>
        </div>
    </div>

    <!-- Right Side - Visual -->
    <div class="hidden lg:flex lg:flex-1 login-bg items-center justify-center relative">
        <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
        <div class="relative z-10 w-full max-w-lg p-8 text-white"
            x-data="{ activeSlide: 0, slides: [0, 1, 2], timer: null }"
            x-init="timer = setInterval(() => activeSlide = activeSlide === slides.length - 1 ? 0 : activeSlide + 1, 8000)">
            <div class="glass-effect rounded-3xl p-8 text-emerald-900 shadow-2xl min-h-[320px] flex flex-col justify-between transition-all duration-500"
                @mouseenter="clearInterval(timer)"
                @mouseleave="timer = setInterval(() => activeSlide = activeSlide === slides.length - 1 ? 0 : activeSlide + 1, 8000)">

                <!-- Slide Content Wrapper -->
                <div class="flex-grow flex items-center justify-center overflow-hidden relative">
                    <!-- Slide 1: Quote -->
                    <div x-show="activeSlide === 0" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform translate-x-4"
                        x-transition:enter-end="opacity-100 transform translate-x-0"
                        x-transition:leave="transition ease-in duration-300"
                        x-transition:leave-start="opacity-100 transform translate-x-0"
                        x-transition:leave-end="opacity-0 transform -translate-x-4"
                        class="absolute inset-0 flex flex-col justify-center items-center">
                        <blockquote class="text-xl font-medium italic mb-4 text-center">
                            "Memberikan penilaian yang objektif dan transparan demi kemaslahatan santri dan pondok
                            pesantren."
                        </blockquote>
                        <div class="mt-4 font-bold uppercase tracking-wider text-sm text-emerald-700">
                            Prinsip Metode SMART
                        </div>
                    </div>

                    <!-- Slide 2: SMART Explanation -->
                    <div x-show="activeSlide === 1" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform translate-x-4"
                        x-transition:enter-end="opacity-100 transform translate-x-0"
                        x-transition:leave="transition ease-in duration-300"
                        x-transition:leave-start="opacity-100 transform translate-x-0"
                        x-transition:leave-end="opacity-0 transform -translate-x-4"
                        class="absolute inset-0 flex flex-col justify-center text-left">
                        <h3 class="text-sm font-bold text-center mb-2 text-emerald-800 uppercase tracking-wide">Elemen
                            Kunci Metode SMART</h3>
                        <div
                            class="space-y-2 text-[10px] text-gray-700 leading-relaxed overflow-y-auto no-scrollbar max-h-[260px] pr-1">
                            <p><strong class="text-emerald-700">1. Alternatif (Alternatives):</strong> Pilihan yang
                                tersedia untuk dievaluasi (santri).</p>
                            <p><strong class="text-emerald-700">2. Kriteria (Criteria):</strong> Faktor untuk menilai
                                setiap alternatif (akhlak, prestasi, dll).</p>
                            <p><strong class="text-emerald-700">3. Bobot (Weights):</strong> Tingkat kepentingan relatif
                                dari setiap kriteria.</p>
                            <p><strong class="text-emerald-700">4. Nilai/Rating (Utility):</strong> Nilai numerik yang
                                merepresentasikan tingkat preferensi setiap alternatif pada setiap kriteria.</p>
                            <p><strong class="text-emerald-700">5. Normalisasi:</strong> Mengubah nilai mentah menjadi
                                skala 0-1 untuk perbandingan.</p>
                            <p><strong class="text-emerald-700">6. Nilai Akhir:</strong> Total bobot x nilai utilitas
                                untuk penentuan peringkat.</p>
                        </div>
                    </div>

                    <!-- Slide 3: Tujuan Sistem -->
                    <div x-show="activeSlide === 2" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform translate-x-4"
                        x-transition:enter-end="opacity-100 transform translate-x-0"
                        x-transition:leave="transition ease-in duration-300"
                        x-transition:leave-start="opacity-100 transform translate-x-0"
                        x-transition:leave-end="opacity-0 transform -translate-x-4"
                        class="absolute inset-0 flex flex-col justify-center items-center text-center">
                        <h3 class="text-sm font-bold mb-2 text-emerald-800 uppercase tracking-wide">Tujuan Sistem</h3>
                        <div class="space-y-1.5 text-[10px] text-gray-700 w-full px-1 overflow-hidden">
                            <div
                                class="flex items-start space-x-2 bg-emerald-50/50 p-1.5 rounded-lg border border-emerald-100/50 items-center">
                                <div
                                    class="h-4 w-4 rounded-full bg-emerald-600 text-white flex items-center justify-center font-bold text-[9px] shrink-0">
                                    1</div>
                                <span class="font-medium text-left leading-tight">Membantu menentukan santri yang layak
                                    untuk kepulangan.</span>
                            </div>
                            <div
                                class="flex items-start space-x-2 bg-emerald-50/50 p-1.5 rounded-lg border border-emerald-100/50 items-center">
                                <div
                                    class="h-4 w-4 rounded-full bg-emerald-600 text-white flex items-center justify-center font-bold text-[9px] shrink-0">
                                    2</div>
                                <span class="font-medium text-left leading-tight">Mengurangi subjektivitas pengambilan
                                    keputusan.</span>
                            </div>
                            <div
                                class="flex items-start space-x-2 bg-emerald-50/50 p-1.5 rounded-lg border border-emerald-100/50 items-center">
                                <div
                                    class="h-4 w-4 rounded-full bg-emerald-600 text-white flex items-center justify-center font-bold text-[9px] shrink-0">
                                    3</div>
                                <span class="font-medium text-left leading-tight">Perhitungan objektif & terukur sesuai
                                    kriteria.</span>
                            </div>
                            <div
                                class="flex items-start space-x-2 bg-emerald-50/50 p-1.5 rounded-lg border border-emerald-100/50 items-center">
                                <div
                                    class="h-4 w-4 rounded-full bg-emerald-600 text-white flex items-center justify-center font-bold text-[9px] shrink-0">
                                    4</div>
                                <span class="font-medium text-left leading-tight">Keputusan berdasarkan fakta dan data,
                                    bukan asumsi.</span>
                            </div>
                            <div
                                class="flex items-start space-x-2 bg-emerald-50/50 p-1.5 rounded-lg border border-emerald-100/50 items-center">
                                <div
                                    class="h-4 w-4 rounded-full bg-emerald-600 text-white flex items-center justify-center font-bold text-[9px] shrink-0">
                                    5</div>
                                <span class="font-medium text-left leading-tight">Hasil rekomendasi transparan & dapat
                                    dipertanggungjawabkan.</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Indicators -->
                <div class="flex items-center justify-center space-x-2 mt-6 z-20">
                    <button @click="activeSlide = 0"
                        :class="activeSlide === 0 ? 'w-8 bg-emerald-600' : 'w-2 bg-emerald-600/40 hover:bg-emerald-600/60'"
                        class="h-1.5 rounded-full transition-all duration-300 focus:outline-none"></button>
                    <button @click="activeSlide = 1"
                        :class="activeSlide === 1 ? 'w-8 bg-emerald-600' : 'w-2 bg-emerald-600/40 hover:bg-emerald-600/60'"
                        class="h-1.5 rounded-full transition-all duration-300 focus:outline-none"></button>
                    <button @click="activeSlide = 2"
                        :class="activeSlide === 2 ? 'w-8 bg-emerald-600' : 'w-2 bg-emerald-600/40 hover:bg-emerald-600/60'"
                        class="h-1.5 rounded-full transition-all duration-300 focus:outline-none"></button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>