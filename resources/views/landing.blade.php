<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Santri — Annuqayah Latee II</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background: #163d22;
            min-height: 100vh;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            overflow-x: hidden;
        }

        /* ── Background canvas ── */
        #bg-canvas {
            position: fixed;
            inset: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
        }

        /* ── Glass cards ── */
        .landing-card {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 20px;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            box-shadow: 0 8px 32px rgba(0,0,0,0.25), inset 0 1px 0 rgba(255,255,255,0.1);
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
            background: rgba(255, 255, 255, 0.14);
            transform: translateY(-4px);
            box-shadow: 0 16px 40px rgba(0,0,0,0.35), inset 0 1px 0 rgba(255,255,255,0.15);
        }
        .landing-card-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 62px;
            width: 62px;
            border-radius: 16px;
            border: 1px solid rgba(255,255,255,0.15);
            transition: background 0.2s ease, transform 0.2s ease;
        }
        .landing-card:hover .landing-card-icon { transform: scale(1.08); }
        .landing-card-title {
            font-size: 1.05rem;
            font-weight: 700;
            color: #ffffff;
            margin: 0;
        }
        .landing-card-desc {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.55);
            margin: -0.3rem 0 0;
            line-height: 1.5;
        }
        .landing-card-arrow {
            display: flex;
            align-items: center;
            gap: 0.3rem;
            font-size: 0.8rem;
            font-weight: 600;
            color: rgba(255,255,255,0.5);
            opacity: 0;
            transition: opacity 0.2s ease;
        }
        .landing-card:hover .landing-card-arrow { opacity: 1; }

        /* ── Mosque silhouette ── */
        .mosque-wrap {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 210px;
            pointer-events: none;
            z-index: 1;
            overflow: hidden;
        }
        .mosque-wrap svg {
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: auto;
            height: 100%;
            min-width: 100%;
        }

    </style>
</head>
<body>

<!-- ① Islamic pattern + sky — drawn by Canvas -->
<canvas id="bg-canvas"></canvas>

<!-- ② Mosque silhouette -->
<div class="mosque-wrap" aria-hidden="true">
    <svg viewBox="0 0 1440 210" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMax meet">
        <defs>
            <linearGradient id="mq" x1="0" y1="0" x2="0" y2="1">
                <stop offset="0%"   stop-color="#081408" stop-opacity="0.96"/>
                <stop offset="100%" stop-color="#060e06"/>
            </linearGradient>
        </defs>
        <g fill="url(#mq)">
            <!-- Ground plane -->
            <rect x="0" y="190" width="1440" height="20"/>

            <!-- Far-left small minaret -->
            <rect x="128" y="115" width="16" height="95" rx="2"/>
            <polygon points="128,115 136,94 144,115"/>
            <rect x="124" y="133" width="24" height="6" rx="2"/>

            <!-- Left main minaret -->
            <rect x="295" y="42" width="22" height="168" rx="2"/>
            <polygon points="295,42 306,14 317,42"/>
            <rect x="289" y="82" width="34" height="7" rx="2"/>
            <rect x="289" y="128" width="34" height="7" rx="2"/>

            <!-- Left wing + dome -->
            <rect x="317" y="148" width="175" height="62"/>
            <path d="M317,148 Q317,88 404,88 Q492,88 492,148 Z"/>
            <rect x="401" y="83" width="5" height="20"/>
            <polygon points="397,83 404,71 411,83"/>

            <!-- Main building body -->
            <rect x="492" y="142" width="456" height="68"/>

            <!-- Main dome (the throne) -->
            <path d="M492,142 Q492,10 720,10 Q948,10 948,142 Z"/>
            <!-- Finial + crescent -->
            <rect x="717" y="5" width="6" height="28"/>
            <polygon points="712,5 720,-6 728,5"/>
            <!-- Crescent shape on finial -->
            <path d="M714,0 Q720,-10 726,0 Q722,-5 720,-5 Q718,-5 714,0 Z" fill="#c9a84c" opacity="0.7"/>

            <!-- Right wing + dome -->
            <rect x="948" y="148" width="175" height="62"/>
            <path d="M948,148 Q948,88 1036,88 Q1123,88 1123,148 Z"/>
            <rect x="1033" y="83" width="5" height="20"/>
            <polygon points="1029,83 1036,71 1043,83"/>

            <!-- Right main minaret -->
            <rect x="1123" y="42" width="22" height="168" rx="2"/>
            <polygon points="1123,42 1134,14 1145,42"/>
            <rect x="1117" y="82" width="34" height="7" rx="2"/>
            <rect x="1117" y="128" width="34" height="7" rx="2"/>

            <!-- Far-right small minaret -->
            <rect x="1296" y="115" width="16" height="95" rx="2"/>
            <polygon points="1296,115 1304,94 1312,115"/>
            <rect x="1292" y="133" width="24" height="6" rx="2"/>

            <!-- Connecting walls -->
            <rect x="144" y="155" width="151" height="55"/>
            <rect x="1145" y="155" width="151" height="55"/>

            <!-- Pointed arched doors (slightly darker cutouts) -->
            <path d="M555,210 Q555,176 578,176 Q601,176 601,210" fill="rgba(0,0,0,0.22)"/>
            <path d="M672,210 Q672,162 720,162 Q768,162 768,210" fill="rgba(0,0,0,0.22)"/>
            <path d="M839,210 Q839,176 862,176 Q885,176 885,210" fill="rgba(0,0,0,0.22)"/>
            <path d="M374,210 Q374,178 397,178 Q420,178 420,210" fill="rgba(0,0,0,0.18)"/>
            <path d="M1020,210 Q1020,178 1043,178 Q1066,178 1066,210" fill="rgba(0,0,0,0.18)"/>
        </g>
    </svg>
</div>

<!-- ③ Page content -->
<div style="position:relative;z-index:10;min-height:100vh;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:2rem 1.5rem 220px;gap:0;font-family:'Inter',system-ui,sans-serif;">

    <!-- Header -->
    <div style="text-align:center;margin-bottom:2.5rem;">
        <div style="display:inline-flex;height:88px;width:88px;align-items:center;justify-content:center;border-radius:24px;background:rgba(255,255,255,0.15);border:1.5px solid rgba(255,255,255,0.3);backdrop-filter:blur(8px);-webkit-backdrop-filter:blur(8px);box-shadow:0 8px 32px rgba(0,0,0,0.3),inset 0 1px 0 rgba(255,255,255,0.2);margin-bottom:1.25rem;overflow:hidden;padding:8px;">
            <img src="{{ asset('favicon.png') }}" alt="Logo Annuqayah Latee II" style="width:100%;height:100%;object-fit:contain;filter:drop-shadow(0 2px 8px rgba(0,0,0,0.4));">
        </div>
        <h1 style="font-size:clamp(1.6rem,4vw,2.2rem);font-weight:900;color:#ffffff;letter-spacing:-0.02em;text-shadow:0 2px 20px rgba(0,0,0,0.4);">Annuqayah Latee II</h1>
        <p style="color:rgba(255,255,255,0.65);margin-top:0.4rem;font-size:0.9rem;letter-spacing:0.04em;">Sistem Informasi Manajemen Santri</p>
    </div>

    <!-- Cards -->
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1.25rem;width:100%;max-width:520px;">

        <a href="{{ route('admin.login') }}" class="landing-card" style="text-decoration:none;">
            <div class="landing-card-icon" style="background:rgba(255,255,255,0.12);">
                <span class="material-symbols-outlined" style="font-size:30px;color:rgba(255,255,255,0.9);">manage_accounts</span>
            </div>
            <p class="landing-card-title">Admin / Pengurus</p>
            <p class="landing-card-desc">Kelola data santri, perizinan, dan laporan</p>
            <div class="landing-card-arrow">
                <span>Masuk</span>
                <span class="material-symbols-outlined" style="font-size:15px;">arrow_forward</span>
            </div>
        </a>

        <a href="{{ route('guardian.login') }}" class="landing-card" style="text-decoration:none;">
            <div class="landing-card-icon" style="background:rgba(110,231,183,0.15);">
                <span class="material-symbols-outlined" style="font-size:30px;color:rgba(110,231,183,0.95);">family_restroom</span>
            </div>
            <p class="landing-card-title">Wali Santri</p>
            <p class="landing-card-desc">Pantau aktivitas & ajukan izin kepulangan</p>
            <div class="landing-card-arrow" style="color:rgba(110,231,183,0.9);">
                <span>Masuk</span>
                <span class="material-symbols-outlined" style="font-size:15px;">arrow_forward</span>
            </div>
        </a>

    </div>

    <p style="text-align:center;font-size:0.7rem;color:rgba(255,255,255,0.25);margin-top:2.5rem;letter-spacing:0.04em;">© {{ date('Y') }} Pondok Pesantren Annuqayah Latee II</p>

</div>

<script>
(function () {
    const canvas = document.getElementById('bg-canvas');
    const ctx    = canvas.getContext('2d');

    /* Fixed star positions [rx, ry, r] — deterministic */
    const SKY_STARS = [
        [.07,.05,.9],[.14,.11,.6],[.21,.04,1.1],[.30,.08,.7],[.38,.05,.8],
        [.47,.10,1.0],[.55,.03,.7],[.63,.08,.9],[.71,.05,.6],[.80,.11,1.1],
        [.88,.06,.8],[.95,.09,.7],[.03,.18,.5],[.11,.22,.8],[.19,.15,.6],
        [.27,.20,.9],[.36,.14,.7],[.44,.25,.5],[.53,.18,.8],[.62,.22,.6],
        [.70,.16,.9],[.79,.21,.7],[.87,.17,.6],[.93,.24,.8],
        [.09,.31,.5],[.24,.28,.6],[.42,.33,.4],[.58,.30,.7],[.75,.34,.5],
        [.91,.29,.6],[.16,.38,.4],[.49,.40,.5],[.83,.37,.4],
    ];

    /* Draw one 8-pointed star at (cx, cy) */
    function star8(cx, cy, outer, inner) {
        ctx.beginPath();
        for (let i = 0; i < 16; i++) {
            const a = (i * Math.PI / 8) - Math.PI / 2;
            const r = (i & 1) ? inner : outer;
            const x = cx + r * Math.cos(a);
            const y = cy + r * Math.sin(a);
            i === 0 ? ctx.moveTo(x, y) : ctx.lineTo(x, y);
        }
        ctx.closePath();
    }

    function draw() {
        const W = canvas.width  = window.innerWidth;
        const H = canvas.height = window.innerHeight;

        /* Senja — gradient dari atas gelap biru-hijau, tengah hijau emerald terang, bawah hijau tua */
        const bg = ctx.createLinearGradient(0, 0, 0, H);
        bg.addColorStop(0,    '#0e2a18');   /* langit atas — gelap indigo-hijau */
        bg.addColorStop(0.30, '#1d5232');   /* tengah atas — emerald dalam */
        bg.addColorStop(0.60, '#2d7a4a');   /* tengah — emerald terang (pusat perhatian) */
        bg.addColorStop(0.85, '#1e5c35');   /* bawah — kembali gelap ke arah tanah */
        bg.addColorStop(1,    '#112a1a');   /* tanah */
        ctx.fillStyle = bg;
        ctx.fillRect(0, 0, W, H);

        /* Cahaya senja keemasan dari cakrawala kiri */
        const glow = ctx.createRadialGradient(W * .15, H * .55, 0, W * .15, H * .55, W * .6);
        glow.addColorStop(0,   'rgba(201,168,76,0.18)');
        glow.addColorStop(0.5, 'rgba(201,168,76,0.06)');
        glow.addColorStop(1,   'rgba(0,0,0,0)');
        ctx.fillStyle = glow;
        ctx.fillRect(0, 0, W, H);

        /* ── Islamic 8-pointed star tessellation ── */
        const S  = 68;
        const OR = 19;
        const IR = 7.5;

        ctx.strokeStyle = 'rgba(201,168,76,0.22)';
        ctx.lineWidth   = 0.9;
        for (let row = -1; row * S < H + S; row++) {
            for (let col = -1; col * S < W + S; col++) {
                star8(col * S + S / 2, row * S + S / 2, OR, IR);
                ctx.stroke();
            }
        }

        /* Diamond connector antara bintang */
        ctx.strokeStyle = 'rgba(201,168,76,0.11)';
        ctx.lineWidth   = 0.6;
        for (let row = -1; row * S < H + S; row++) {
            for (let col = -1; col * S < W + S; col++) {
                ctx.save();
                ctx.translate(col * S + S, row * S + S);
                ctx.rotate(Math.PI / 4);
                ctx.strokeRect(-6, -6, 12, 12);
                ctx.restore();
            }
        }

        /* Bintang-bintang kecil di langit atas (lebih terang karena langit lebih gelap di atas) */
        SKY_STARS.forEach(([rx, ry, r]) => {
            ctx.beginPath();
            ctx.arc(rx * W, ry * H * 0.45, r, 0, Math.PI * 2);
            ctx.fillStyle = `rgba(255,252,230,${0.55 + r * 0.25})`;
            ctx.fill();
        });

        /* Vignette ringan — hanya sisi kiri-kanan, tidak atas-bawah */
        const vig = ctx.createLinearGradient(0, 0, W, 0);
        vig.addColorStop(0,    'rgba(0,0,0,0.30)');
        vig.addColorStop(0.15, 'rgba(0,0,0,0)');
        vig.addColorStop(0.85, 'rgba(0,0,0,0)');
        vig.addColorStop(1,    'rgba(0,0,0,0.30)');
        ctx.fillStyle = vig;
        ctx.fillRect(0, 0, W, H);
    }

    draw();
    window.addEventListener('resize', draw);
})();
</script>

</body>
</html>
