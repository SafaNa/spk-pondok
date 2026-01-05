<!DOCTYPE html>
<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>@yield('title', 'SPK Pesantren')</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#ea2a33",
                        "background-light": "#f8f6f6",
                        "background-dark": "#211111",
                        "card-light": "#ffffff",
                        "card-dark": "#2a1a1a",
                        "text-primary-light": "#1f2937",
                        "text-primary-dark": "#f3f4f6",
                        "text-secondary-light": "#6b7280",
                        "text-secondary-dark": "#9ca3af",
                    },
                    fontFamily: {
                        "display": ["Inter", "sans-serif"]
                    },
                    borderRadius: {"DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px"},
                },
            },
        }
    </script>
    <style>
        /* Custom scrollbar for cleaner look */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        .dark ::-webkit-scrollbar-thumb {
            background: #4b5563;
        }
    </style>
</head>

<body class="font-display bg-background-light dark:bg-background-dark text-text-primary-light dark:text-text-primary-dark transition-colors duration-300 min-h-screen flex flex-col">
    <!-- Navbar -->
    <nav class="sticky top-0 z-30 w-full bg-card-light dark:bg-card-dark border-b border-gray-200 dark:border-gray-800 shadow-sm backdrop-blur-md bg-opacity-90 dark:bg-opacity-90">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center justify-between">
                <!-- Logo & Brand -->
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10 text-primary">
                        <span class="material-icons">mosque</span>
                    </div>
                    <div class="hidden md:block">
                        <h1 class="text-lg font-bold tracking-tight text-gray-900 dark:text-white">SPK Pesantren</h1>
                        <p class="text-xs text-text-secondary-light dark:text-text-secondary-dark">Homecoming Recommendation System</p>
                    </div>
                </div>
                <!-- Right Side Actions -->
                <div class="flex items-center gap-4">
                    <!-- Period Badge -->
                    <div class="hidden sm:flex items-center gap-2 px-3 py-1 rounded-full bg-primary/5 border border-primary/20">
                        <span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
                        <span class="text-sm font-medium text-primary">Period: Ramadhan 1445H</span>
                    </div>
                    <!-- Theme Toggle -->
                    <button class="p-2 rounded-full text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                        <span class="material-icons text-xl">dark_mode</span>
                    </button>
                    <!-- User Profile -->
                    <div class="flex items-center gap-3 pl-4 border-l border-gray-200 dark:border-gray-700">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Ust. Abdullah</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Head Administrator</p>
                        </div>
                        <div class="h-9 w-9 rounded-full ring-2 ring-white dark:ring-gray-800 bg-primary/10 flex items-center justify-center text-primary font-bold text-sm">
                            UA
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-1 px-4 sm:px-6 lg:px-8 py-8">
        @yield('content')
    </main>
</body>
</html>