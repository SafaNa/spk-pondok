<!doctype html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Wali Santri - {{ $appSetting->app_name ?? 'SIM Kepulangan Santri' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 dark:from-slate-900 dark:via-slate-900 dark:to-slate-950 font-[Inter] flex items-center justify-center p-4">

    <div class="w-full max-w-sm">

        <div class="text-center mb-8">
            <div class="inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-primary text-white shadow-lg shadow-primary/30 mb-4 overflow-hidden">
                @if(isset($appSetting) && $appSetting->logo)
                    <img src="{{ asset('storage/' . $appSetting->logo) }}" alt="Logo" class="w-full h-full object-contain bg-white">
                @else
                    <span class="material-symbols-outlined text-[32px]">family_restroom</span>
                @endif
            </div>
            <h1 class="text-2xl font-black text-[#0d141b] dark:text-white">Portal Wali Santri</h1>
            <p class="text-sm text-[#4c739a] mt-1">Masuk untuk mengelola pengajuan izin santri Anda</p>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-[#e7edf3] dark:border-slate-800 p-6">

            @if($errors->any())
                <div class="mb-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-600 dark:text-red-400 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">error</span>
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('guardian.login.post') }}" class="space-y-4" 
                  onsubmit="document.getElementById('login-btn').disabled = true; document.getElementById('login-text').classList.add('hidden'); document.getElementById('login-loading').classList.remove('hidden'); document.getElementById('login-btn').classList.add('opacity-75', 'cursor-not-allowed');">
                @csrf

                <div class="space-y-1.5">
                    <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Username</label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                            <span class="material-symbols-outlined text-[18px] text-slate-400">person</span>
                        </div>
                        <input type="text" name="username" value="{{ old('username') }}" required autofocus
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white text-sm focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all"
                            placeholder="Masukkan username">
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Password</label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                            <span class="material-symbols-outlined text-[18px] text-slate-400">lock</span>
                        </div>
                        <input type="password" name="password" required
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white text-sm focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all"
                            placeholder="Masukkan password">
                    </div>
                </div>

                <button type="submit" id="login-btn"
                    class="w-full py-3 rounded-xl bg-primary hover:bg-primary/90 text-white text-sm font-bold shadow-md shadow-primary/25 transition-all mt-2 flex items-center justify-center min-h-[48px]">
                    <span id="login-text">Masuk</span>
                    <div id="login-loading" class="hidden flex items-center gap-2">
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>Memproses...</span>
                    </div>
                </button>
            </form>
        </div>

        <p class="text-center text-xs text-[#4c739a] mt-6">
            <a href="{{ route('landing') }}" class="text-primary font-semibold hover:underline">← Kembali ke halaman utama</a>
        </p>
    </div>

</body>
</html>
