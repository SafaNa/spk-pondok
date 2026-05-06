<!DOCTYPE html>
<html lang="en" class="light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Login - SIM Kepulangan Santri</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet" />

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
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
                        "display": ["Inter", "sans-serif"],
                    },
                    borderRadius: { "DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px" },
                },
            },
        }
    </script>
</head>

<body class="bg-background-light dark:bg-background-dark text-[#0d141b] dark:text-slate-200 font-display antialiased">
    <div class="min-h-screen flex w-full">
        <!-- Left Section: Visual / Context (Desktop Only) -->
        <div class="hidden lg:flex w-1/2 relative bg-primary overflow-hidden flex-col justify-between p-12 text-white">
            <div class="absolute inset-0 z-0">
                <div class="absolute inset-0 bg-gradient-to-br from-primary via-blue-600 to-blue-800"></div>
                <div class="absolute inset-0 opacity-10">
                    <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                        <defs>
                            <pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse">
                                <path d="M 10 0 L 0 0 0 10" fill="none" stroke="white" stroke-width="0.5" />
                            </pattern>
                        </defs>
                        <rect width="100" height="100" fill="url(#grid)" />
                    </svg>
                </div>
            </div>
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-2 bg-white/20 backdrop-blur-sm rounded-lg">
                        <span class="material-symbols-outlined text-3xl">school</span>
                    </div>
                    <span class="text-xl font-bold tracking-wide uppercase">Sistem Informasi Manajemen Santri</span>
                </div>
            </div>
            <div class="relative z-10 max-w-lg">
                <h1 class="text-2xl md:text-3xl font-bold leading-tight mb-6">SISTEM VALIDASI IZIN DAN KEPULANGAN SANTRI
                </h1>
                <p class="text-lg text-blue-50 leading-relaxed opacity-90">
                    Sistem informasi terintegrasi untuk mengelola validasi perizinan kepulangan santri, pembayaran SPP,
                    dan pencatatan pelanggaran dalam satu platform yang terpadu dan efisien.
                </p>
            </div>
            <div class="relative z-10 text-sm text-blue-100 opacity-60">
                © {{ date('Y') }} Pesantren Administration System.
            </div>
        </div>

        <!-- Right Section: Login Form -->
        <div class="w-full lg:w-1/2 flex flex-col justify-center items-center p-6 md:p-12 relative">
            <!-- Mobile Header Logo (Visible only on small screens) -->
            <div class="lg:hidden absolute top-6 left-6 flex items-center gap-2 text-primary">
                <span class="material-symbols-outlined text-3xl">school</span>
                <span class="font-bold text-lg text-[#0d141b] dark:text-white">SIM Santri</span>
            </div>

            <div class="w-full max-w-[480px] flex flex-col gap-6">
                <!-- Page Heading -->
                <div class="flex flex-col gap-2 mb-2">
                    <h2 class="text-[#0d141b] dark:text-white tracking-tight text-[32px] font-bold leading-tight">
                        Administrator Login</h2>
                    <p class="text-[#4c739a] dark:text-slate-400 text-sm font-normal leading-normal">
                        Welcome back! Please enter your details to access the dashboard.
                    </p>
                </div>

                <!-- Session Flash Message -->
                @if(session('error'))
                    <div
                        class="rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-100 dark:border-red-800 p-4 flex gap-3 items-start">
                        <span class="material-symbols-outlined text-red-600 dark:text-red-400 mt-0.5 text-xl">error</span>
                        <div class="flex-1">
                            <h3 class="text-sm font-medium text-red-800 dark:text-red-300">Authentication Failed</h3>
                            <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ session('error') }}</p>
                        </div>
                    </div>
                @endif

                @if($errors->any())
                    <div
                        class="rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-100 dark:border-red-800 p-4 flex gap-3 items-start">
                        <span class="material-symbols-outlined text-red-600 dark:text-red-400 mt-0.5 text-xl">error</span>
                        <div class="flex-1">
                            <h3 class="text-sm font-medium text-red-800 dark:text-red-300">Authentication Failed</h3>
                            <p class="text-sm text-red-600 dark:text-red-400 mt-1">
                                The credentials provided do not match our records. Please try again.
                            </p>
                        </div>
                    </div>
                @endif

                <!-- Login Form -->
                <form action="{{ route('login') }}" method="POST" class="flex flex-col gap-5">
                    @csrf

                    <!-- Email Input Field -->
                    <div class="flex flex-col gap-2">
                        <label class="text-[#0d141b] dark:text-slate-200 text-base font-medium leading-normal"
                            for="email">Email Address</label>
                        <div class="relative">
                            <input
                                class="form-input flex w-full min-w-0 resize-none overflow-hidden rounded-lg text-[#0d141b] dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border {{ $errors->has('email') ? 'border-red-300 dark:border-red-800' : 'border-[#cfdbe7] dark:border-slate-700' }} bg-white dark:bg-slate-800 h-14 placeholder:text-[#4c739a] p-[15px] text-base font-normal leading-normal transition-all"
                                id="email" name="email" placeholder="admin@pesantren.com" required type="email"
                                value="admin@pondok.test" />
                            @if($errors->has('email'))
                                <div class="absolute right-4 top-1/2 -translate-y-1/2 text-red-500">
                                    <span class="material-symbols-outlined text-[20px]">warning</span>
                                </div>
                            @endif
                        </div>
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Input Field -->
                    <div class="flex flex-col gap-2">
                        <div class="flex justify-between items-center">
                            <label class="text-[#0d141b] dark:text-slate-200 text-base font-medium leading-normal"
                                for="password">Password</label>
                        </div>
                        <div class="flex w-full items-stretch rounded-lg relative group">
                            <input
                                class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-[#0d141b] dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border {{ $errors->has('password') ? 'border-red-300 dark:border-red-800' : 'border-[#cfdbe7] dark:border-slate-700' }} bg-white dark:bg-slate-800 focus:border-primary h-14 placeholder:text-[#4c739a] p-[15px] pr-12 text-base font-normal leading-normal transition-all"
                                id="password" name="password" placeholder="Enter your password" required type="password"
                                value="password" />
                            <button
                                class="absolute right-0 top-0 h-full px-4 text-[#4c739a] hover:text-primary dark:text-slate-400 dark:hover:text-white flex items-center justify-center transition-colors"
                                type="button" onclick="togglePassword()">
                                <span class="material-symbols-outlined" id="passwordIcon">visibility</span>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between mt-1">
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <div class="relative flex items-center">
                                <input
                                    class="peer h-5 w-5 cursor-pointer appearance-none rounded border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 checked:bg-primary checked:border-primary focus:ring-2 focus:ring-primary/30 transition-all"
                                    type="checkbox" name="remember" />
                                <span
                                    class="material-symbols-outlined absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 text-white opacity-0 peer-checked:opacity-100 text-[16px] pointer-events-none font-bold">check</span>
                            </div>
                            <span
                                class="text-sm font-medium text-[#0d141b] dark:text-slate-300 group-hover:text-primary transition-colors">Remember
                                me</span>
                        </label>
                        <a class="text-sm font-bold text-primary hover:text-blue-700 dark:hover:text-blue-400 transition-colors"
                            href="#">
                            Forgot Password?
                        </a>
                    </div>

                    <!-- Submit Button -->
                    <button
                        class="flex w-full cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-4 bg-primary hover:bg-blue-600 active:bg-blue-700 text-white text-base font-bold leading-normal tracking-[0.015em] transition-all shadow-md hover:shadow-lg mt-4 gap-2"
                        type="submit">
                        <span class="material-symbols-outlined text-[20px]">login</span>
                        <span class="truncate">Sign In</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const passwordIcon = document.getElementById('passwordIcon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.textContent = 'visibility_off';
            } else {
                passwordInput.type = 'password';
                passwordIcon.textContent = 'visibility';
            }
        }
    </script>
</body>

</html>