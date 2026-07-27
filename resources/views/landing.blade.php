<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Santri — Pondok Pesantren Annuqayah Latee II</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}?v=2">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background: linear-gradient(rgba(0, 0, 0, 0.35), rgba(0, 0, 0, 0.35)), 
                        url("{{ asset('docs/images/pondok.png') }}") no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            overflow-x: hidden;
        }

        /* ── Glass cards ── */
        .landing-card {
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 20px;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            box-shadow: 0 12px 32px rgba(0,0,0,0.3), inset 0 1px 0 rgba(255,255,255,0.2);
            padding: 2rem 1.5rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: 0.9rem;
            transition: transform 0.2s ease, background 0.2s ease, box-shadow 0.2s ease;
            cursor: pointer;
        }
        .landing-card:hover {
            background: rgba(255, 255, 255, 0.22);
            transform: translateY(-4px);
            box-shadow: 0 18px 40px rgba(0,0,0,0.4), inset 0 1px 0 rgba(255,255,255,0.3);
        }
        .landing-card-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 62px;
            width: 62px;
            border-radius: 16px;
            border: 1px solid rgba(255,255,255,0.25);
            transition: background 0.2s ease, transform 0.2s ease;
        }
        .landing-card:hover .landing-card-icon { transform: scale(1.08); }
        .landing-card-title {
            font-size: 1.05rem;
            font-weight: 700;
            color: #ffffff;
            margin: 0;
            text-shadow: 0 1px 4px rgba(0,0,0,0.4);
        }
        .landing-card-desc {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.85);
            margin: -0.3rem 0 0;
            line-height: 1.5;
            text-shadow: 0 1px 3px rgba(0,0,0,0.4);
        }
        .landing-card-arrow {
            display: flex;
            align-items: center;
            gap: 0.3rem;
            font-size: 0.8rem;
            font-weight: 600;
            color: #6ee7b7; /* Disamakan ke Hijau */
            opacity: 0;
            transition: opacity 0.2s ease;
        }
        .landing-card:hover .landing-card-arrow { opacity: 1; }
    </style>
</head>
<body>

<div style="position:relative;z-index:10;min-height:100vh;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:2rem 1.5rem;gap:0;font-family:'Inter',system-ui,sans-serif;">

    <!-- Header -->
    <div style="text-align:center;margin-bottom:2.5rem;">
        <div style="display:inline-flex;
            height:120px;
            width:120px;
            align-items:center;
            justify-content:center;
            border-radius:28px;
            background:rgba(255,255,255,0.2);
            border:1.5px solid rgba(255,255,255,0.4);
            backdrop-filter:blur(10px);
            -webkit-backdrop-filter:blur(10px);
            box-shadow:0 8px 32px rgba(0,0,0,0.3),inset 0 1px 0 rgba(255,255,255,0.3);
            margin-bottom:1.25rem;
            overflow:hidden;
            padding:4px;">
            
            <img src="{{ asset('favicon.png') }}"
                 alt="Logo Annuqayah Latee II"
                 style="width:100%;
                        height:100%;
                        object-fit:contain;
                        filter:drop-shadow(0 2px 8px rgba(0,0,0,0.4));">
        </div>

        <h1 style="font-size:clamp(1.6rem,4vw,2.2rem);font-weight:900;color:#ffffff;letter-spacing:-0.02em;text-shadow:0 2px 16px rgba(0,0,0,0.7);">
            Pondok Pesantren Annuqayah Latee II
        </h1>

        <p style="color:rgba(255,255,255,0.9);margin-top:0.4rem;font-size:0.9rem;letter-spacing:0.04em;text-shadow:0 1px 8px rgba(0,0,0,0.6);">
            Sistem Informasi Manajemen Izin dan Kepulangan Santri PP. Annuqayah Latee II
        </p>
    </div>

    <!-- Cards (Keduanya Beraksen Hijau) -->
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1.25rem;width:100%;max-width:520px;">

        <a href="{{ route('admin.login') }}" class="landing-card" style="text-decoration:none;">
            <div class="landing-card-icon" style="background:rgba(110,231,183,0.25);">
                <span class="material-symbols-outlined" style="font-size:30px;color:#6ee7b7;">manage_accounts</span>
            </div>
            <p class="landing-card-title">Admin / Pengurus</p>
            <p class="landing-card-desc">Kelola data santri, perizinan, dan laporan</p>
            <div class="landing-card-arrow">
                <span>Masuk</span>
                <span class="material-symbols-outlined" style="font-size:15px;">arrow_forward</span>
            </div>
        </a>

        <a href="{{ route('guardian.login') }}" class="landing-card" style="text-decoration:none;">
            <div class="landing-card-icon" style="background:rgba(110,231,183,0.25);">
                <span class="material-symbols-outlined" style="font-size:30px;color:#6ee7b7;">family_restroom</span>
            </div>
            <p class="landing-card-title">Wali Santri</p>
            <p class="landing-card-desc">Pantau aktivitas & ajukan izin kepulangan</p>
            <div class="landing-card-arrow">
                <span>Masuk</span>
                <span class="material-symbols-outlined" style="font-size:15px;">arrow_forward</span>
            </div>
        </a>

    </div>

    <p style="text-align:center;font-size:0.75rem;color:rgba(255,255,255,0.7);margin-top:2.5rem;letter-spacing:0.04em;text-shadow:0 1px 6px rgba(0,0,0,0.6);">
        © {{ date('Y') }} Pondok Pesantren Annuqayah Latee II
    </p>

</div>

</body>
</html>