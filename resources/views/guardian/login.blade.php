<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Wali Santri — {{ $appSetting->app_name ?? 'Pondok Pesantren Annuqayah Latee II' }}</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}?v=2">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background: linear-gradient(rgba(0, 0, 0, 0.45), rgba(0, 0, 0, 0.45)), 
                        url("{{ asset('assets/images/pondok.png') }}") no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            overflow-x: hidden;
        }

        /* ── Glass Form Card ── */
        .glass-card {
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 24px;
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.4), inset 0 1px 0 rgba(255, 255, 255, 0.25);
            padding: 2.25rem 1.75rem;
            width: 100%;
            max-width: 420px;
        }

        /* Input Styling */
        .glass-input {
            width: 100%;
            height: 48px; /* Diberikan tinggi pasti agar tidak goyang */
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: #ffffff;
            border-radius: 12px;
            padding: 0 3rem; /* Padding kiri dan kanan aman dari ikon */
            font-size: 0.9rem;
            transition: all 0.2s ease;
        }
        .glass-input::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }
        .glass-input:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.25);
            border-color: #6ee7b7;
            box-shadow: 0 0 0 4px rgba(110, 231, 183, 0.2);
        }

        /* Button Styling - Diberikan margin-top mandiri */
        .btn-green {
            background: #10b981;
            color: #ffffff;
            border: none;
            border-radius: 12px;
            height: 48px;
            font-weight: 700;
            font-size: 0.95rem;
            width: 100%;
            cursor: pointer;
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.35);
            transition: all 0.2s ease;
            margin-top: 1.5rem !important; /* MENGUNCI JARAK AGAR TIDAK BERTINDIH */
        }
        .btn-green:hover {
            background: #059669;
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(16, 185, 129, 0.45);
        }
    </style>
</head>
<body class="flex items-center justify-center p-4">

<div style="position:relative;z-index:10;width:100%;min-height:100vh;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:2rem 1.5rem;">

    <!-- Header Section -->
    <div style="text-align:center;margin-bottom:1.75rem;">
        <div style="display:inline-flex; height:100px; width:100px; align-items:center; justify-content:center; margin-bottom:0.75rem;">
            <img src="{{ (isset($appSetting) && $appSetting->logo) ? asset('storage/' . $appSetting->logo) : asset('favicon.png') }}"
                 alt="Logo"
                 style="width:100%; height:100%; object-fit:contain; filter:drop-shadow(0 4px 12px rgba(0,0,0,0.5));">
        </div>

        <h1 style="font-size:clamp(1.4rem,3.5vw,1.8rem);font-weight:900;color:#ffffff;letter-spacing:-0.02em;text-shadow:0 2px 16px rgba(0,0,0,0.7);">
            PORTAL WALI SANTRI
        </h1>

        <p style="color:rgba(255,255,255,0.85);margin-top:0.3rem;font-size:0.85rem;letter-spacing:0.02em;text-shadow:0 1px 8px rgba(0,0,0,0.6);">
            Pondok Pesantren Annuqayah Latee II
        </p>
    </div>

    <!-- Card Form Glassmorphism -->
    <div class="glass-card">

        <!-- Error Flash Message -->
        @if($errors->any())
            <div style="background: rgba(239, 68, 68, 0.25); border: 1px solid rgba(248, 113, 113, 0.4); color: #fecaca;" class="mb-4 px-4 py-3 rounded-xl text-xs flex items-center gap-2 backdrop-blur-md">
                <span class="material-symbols-outlined text-[18px]">error</span>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('guardian.login.post') }}" class="flex flex-col" 
              onsubmit="document.getElementById('login-btn').disabled = true; document.getElementById('login-text').classList.add('hidden'); document.getElementById('login-loading').classList.remove('hidden'); document.getElementById('login-btn').classList.add('opacity-75', 'cursor-not-allowed');">
            @csrf

           <!-- Username Field -->
            <div class="flex flex-col gap-1.5 mb-5">
                <label class="text-xs font-semibold text-white/90 drop-shadow">Username</label>
                <div class="relative flex items-center">
                    <div class="pointer-events-none absolute left-4 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[18px] text-white/70">person</span>
                    </div>
                    <input type="text" name="username" value="{{ old('username') }}" required autofocus
                        class="glass-input" placeholder="Masukkan username">
                </div>
            </div>

            <!-- Password Field (Ditambahkan mt-2 dan mb-6 untuk memberi jarak atas & bawah yang pas) -->
            <div class="flex flex-col gap-1.5 mt-4 mb-6">
                <label class="text-xs font-semibold text-white/90 drop-shadow">Password</label>
                <div class="relative flex items-center">
                    <div class="pointer-events-none absolute left-4 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[18px] text-white/70">lock</span>
                    </div>
                    <input type="password" name="password" required
                        class="glass-input" placeholder="Masukkan password">
                    <button type="button" onclick="let inp=this.previousElementSibling; if(inp.type==='password'){inp.type='text'; this.firstElementChild.innerText='visibility_off';}else{inp.type='password'; this.firstElementChild.innerText='visibility';}" 
                        class="absolute right-4 flex items-center justify-center text-white/70 hover:text-white transition-colors focus:outline-none">
                        <span class="material-symbols-outlined text-[18px]">visibility</span>
                    </button>
                </div>
            </div>

            <!-- Submit Button (Jarak Diberi Margin Top 1.5rem / 24px) -->
            <button type="submit" id="login-btn" class="btn-green flex items-center justify-center">
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

    <!-- Link Kembali -->
    <p style="text-align:center;margin-top:1.75rem;">
        <a href="{{ route('landing') }}" style="color:#6ee7b7;font-size:0.8rem;font-weight:600;text-decoration:none;text-shadow:0 1px 4px rgba(0,0,0,0.6);" class="hover:underline flex items-center gap-1 justify-center">
            <span class="material-symbols-outlined text-[16px]">arrow_back</span>
            Kembali ke halaman utama
        </a>
    </p>

    <!-- Footer -->
    <p style="text-align:center;font-size:0.75rem;color:rgba(255,255,255,0.7);margin-top:1.5rem;letter-spacing:0.04em;text-shadow:0 1px 6px rgba(0,0,0,0.6);">
        © {{ date('Y') }} Pondok Pesantren Annuqayah Latee II
    </p>

</div>

</body>
</html>