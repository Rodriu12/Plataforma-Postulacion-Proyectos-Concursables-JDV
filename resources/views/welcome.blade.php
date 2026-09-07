<!DOCTYPE html>
<html class="scroll-smooth" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Bienvenido a JuntApp - Plataforma de Gestión y Coordinación Comunitaria</title>

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&amp;display=swap"
        rel="stylesheet" />

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        juntapp: {
                            50: '#eef6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            500: '#3b82f6',
                            600: '#1d4ed8',
                            700: '#1e40af',
                            800: '#1e3a8a',
                            900: '#172554',
                        }
                    },
                    animation: {
                        'float-slow': 'float 6s ease-in-out infinite',
                        'float-delayed': 'float 7s ease-in-out 2s infinite',
                        'pulse-subtle': 'pulseSubtle 3s ease-in-out infinite',
                        'shimmer': 'shimmer 2.5s infinite linear',
                        'fade-in-up': 'fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': {
                                transform: 'translateY(0px)'
                            },
                            '50%': {
                                transform: 'translateY(-10px)'
                            },
                        },
                        pulseSubtle: {
                            '0%, 100%': {
                                opacity: '1',
                                transform: 'scale(1)'
                            },
                            '50%': {
                                opacity: '0.85',
                                transform: 'scale(1.05)'
                            },
                        },
                        shimmer: {
                            '0%': {
                                backgroundPosition: '-200% 0'
                            },
                            '100%': {
                                backgroundPosition: '200% 0'
                            },
                        },
                        fadeInUp: {
                            '0%': {
                                opacity: '0',
                                transform: 'translateY(24px)'
                            },
                            '100%': {
                                opacity: '1',
                                transform: 'translateY(0)'
                            },
                        }
                    }
                }
            }
        }
    </script>

    <style data-purpose="custom-glassmorphism">
        html {
            transition: font-size 0.2s ease-in-out;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.78);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.85);
            box-shadow: 0 18px 38px -10px rgba(27, 58, 125, 0.08), 0 4px 12px -2px rgba(27, 58, 125, 0.04);
        }

        .glass-pill {
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.9);
        }

        .shimmer-btn {
            background-size: 200% auto;
            background-image: linear-gradient(110deg, #1d4ed8 0%, #2563eb 25%, #60a5fa 50%, #2563eb 75%, #1d4ed8 100%);
        }

        .delay-100 {
            animation-delay: 100ms;
        }

        .delay-200 {
            animation-delay: 200ms;
        }

        .delay-300 {
            animation-delay: 300ms;
        }

        .delay-400 {
            animation-delay: 400ms;
        }

        .delay-500 {
            animation-delay: 500ms;
        }

        .delay-600 {
            animation-delay: 600ms;
        }
    </style>
</head>

<body
    class="font-sans antialiased text-slate-800 bg-[#f8fbff] min-h-screen relative overflow-x-hidden flex flex-col justify-between selection:bg-blue-600 selection:text-white"
    x-data="{
        agrandar: localStorage.getItem('agrandarTexto') === 'true',
        aplicar() { document.documentElement.style.fontSize = this.agrandar ? '145%' : '100%'; },
        toggle() { this.agrandar = !this.agrandar;
            localStorage.setItem('agrandarTexto', this.agrandar);
            this.aplicar(); }
    }" x-init="aplicar()" :class="agrandar ? 'contrast-125' : ''">

    <div aria-hidden="true" class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
        <div class="absolute inset-0 w-full h-full pointer-events-none opacity-80" style="display:block;">
            <canvas id="shader-canvas-ANIMATION_4" style="display:block;width:100%;height:100%"></canvas>
            <script>
                (function() {
                    const canvas = document.getElementById('shader-canvas-ANIMATION_4');

                    function syncSize() {
                        const w = canvas.clientWidth || 1280;
                        const h = canvas.clientHeight || 720;
                        if (canvas.width !== w || canvas.height !== h) {
                            canvas.width = w;
                            canvas.height = h;
                        }
                    }
                    if (typeof ResizeObserver !== 'undefined') {
                        new ResizeObserver(syncSize).observe(canvas);
                    }
                    syncSize();

                    const gl = canvas.getContext('webgl') || canvas.getContext('experimental-webgl');
                    if (!gl) return;
                    const vs =
                        `attribute vec2 a_position; varying vec2 v_texCoord; void main() { v_texCoord = a_position * 0.5 + 0.5; gl_Position = vec4(a_position, 0.0, 1.0); }`;
                    const fs =
                        `precision highp float; uniform float u_time; uniform vec2 u_resolution; uniform vec2 u_mouse; void main() { vec2 st = gl_FragCoord.xy / u_resolution.xy; vec2 p = st * 2.0 - 1.0; p.x *= u_resolution.x / u_resolution.y; float t = u_time * 0.45; vec2 mouseNorm = u_mouse / u_resolution; vec2 mouseOffset = (mouseNorm - 0.5) * 0.4; p += mouseOffset; float wave1 = sin(p.x * 2.2 + t + sin(p.y * 2.5 + t * 0.7)); float wave2 = cos(p.y * 2.8 - t * 0.8 + cos(p.x * 2.0 - t * 0.5)); float wave3 = sin((p.x + p.y) * 1.8 + t * 0.6); float combined = (wave1 + wave2 + wave3) / 3.0; vec3 baseColor = vec3(0.96, 0.98, 1.0); vec3 blueColor = vec3(0.14, 0.38, 0.92); vec3 cyanColor = vec3(0.22, 0.74, 0.97); vec3 warmAmber = vec3(0.99, 0.82, 0.55); float d = length(p - vec2(0.35, 0.1)); float aura = smoothstep(1.5, 0.1, d + combined * 0.25); vec3 col = mix(baseColor, cyanColor, clamp(aura * 0.35 + wave1 * 0.1, 0.0, 1.0)); col = mix(col, blueColor, clamp(aura * 0.28 + wave2 * 0.12, 0.0, 1.0)); col = mix(col, warmAmber, clamp(sin(d * 4.0 - t) * 0.08, 0.0, 1.0)); float vig = 1.0 - 0.25 * length(st - 0.5); col *= vig; gl_FragColor = vec4(col, 1.0); }`;

                    function cs(type, src) {
                        const s = gl.createShader(type);
                        gl.shaderSource(s, src);
                        gl.compileShader(s);
                        return s;
                    }
                    const prog = gl.createProgram();
                    gl.attachShader(prog, cs(gl.VERTEX_SHADER, vs));
                    gl.attachShader(prog, cs(gl.FRAGMENT_SHADER, fs));
                    gl.linkProgram(prog);
                    gl.useProgram(prog);
                    const buf = gl.createBuffer();
                    gl.bindBuffer(gl.ARRAY_BUFFER, buf);
                    gl.bufferData(gl.ARRAY_BUFFER, new Float32Array([-1, -1, 1, -1, -1, 1, 1, 1]), gl.STATIC_DRAW);
                    const pos = gl.getAttribLocation(prog, 'a_position');
                    gl.enableVertexAttribArray(pos);
                    gl.vertexAttribPointer(pos, 2, gl.FLOAT, false, 0, 0);
                    const uTime = gl.getUniformLocation(prog, 'u_time');
                    const uRes = gl.getUniformLocation(prog, 'u_resolution');
                    const uMouse = gl.getUniformLocation(prog, 'u_mouse');

                    let mouse = {
                        x: canvas.width / 2,
                        y: canvas.height / 2
                    };
                    window.addEventListener('mousemove', (event) => {
                        const rect = canvas.getBoundingClientRect();
                        if (rect.width && rect.height) {
                            const nx = (event.clientX - rect.left) / rect.width;
                            const ny = 1.0 - (event.clientY - rect.top) / rect.height;
                            mouse.x = nx * canvas.width;
                            mouse.y = ny * canvas.height;
                        }
                    });

                    function render(t) {
                        if (typeof ResizeObserver === 'undefined') syncSize();
                        gl.viewport(0, 0, canvas.width, canvas.height);
                        if (uTime) gl.uniform1f(uTime, t * 0.001);
                        if (uRes) gl.uniform2f(uRes, canvas.width, canvas.height);
                        if (uMouse) gl.uniform2f(uMouse, mouse.x, mouse.y);
                        gl.drawArrays(gl.TRIANGLE_STRIP, 0, 4);
                        requestAnimationFrame(render);
                    }
                    render(0);
                })();
            </script>
        </div>
        <div class="absolute inset-0 bg-gradient-to-b from-white/80 via-white/50 to-white/90 backdrop-blur-[2px]"></div>
        <div class="absolute top-0 right-1/4 w-96 h-96 bg-blue-200/40 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-20 left-10 w-96 h-96 bg-cyan-200/40 rounded-full blur-3xl pointer-events-none">
        </div>
    </div>

    <header
        class="relative z-20 w-full border-b border-white/60 bg-white/60 backdrop-blur-md sticky top-0 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a class="flex items-center gap-2.5 group cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-lg p-1"
                    href="/">
                    <div
                        class="w-11 h-11 rounded-2xl bg-blue-600 flex items-center justify-center text-white shadow-md shadow-blue-500/30 group-hover:scale-105 transition-transform duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.2"
                            viewBox="0 0 24 24">
                            <path
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                                stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-2xl font-extrabold tracking-tight text-slate-900 leading-none">Junt<span
                                class="text-blue-600">App</span></span>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mt-0.5">Yumbel
                            Comunitaria</span>
                    </div>
                </a>
                <div
                    class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-full bg-blue-50/80 border border-blue-100 text-xs font-semibold text-slate-700 shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                    <span class="w-2 h-2 -ml-3.5 rounded-full bg-emerald-500"></span>
                    <span>Yumbel &amp; Alrededores</span>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button @click="toggle()" type="button" aria-label="Opciones de accesibilidad de lectura"
                    :class="agrandar ? 'bg-blue-600 text-white shadow-md border border-blue-700' :
                        'glass-pill text-slate-700 hover:bg-white/90 hover:text-blue-700 hover:shadow-sm'"
                    class="hidden md:flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-semibold transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    :title="agrandar ? 'Volver a la vista normal' : 'Activar accesibilidad de lectura'">

                    <svg class="w-4 h-4" :class="agrandar ? 'text-white' : 'text-blue-600'" fill="none"
                        stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke-linecap="round" stroke-linejoin="round">
                        </path>
                        <path
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                            stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                    <span x-text="agrandar ? 'Volver a la vista normal' : 'Accesibilidad de lectura'"></span>
                </button>

                <a class="flex items-center gap-2 px-4 py-2 rounded-xl text-xs sm:text-sm font-bold text-blue-700 bg-blue-50/90 hover:bg-blue-600 hover:text-white border border-blue-200/80 shadow-sm transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-600"
                    href="/admin">
                    <span>Ingreso Dirigente</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path
                            d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"
                            stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </a>
            </div>
        </div>
    </header>

    <main
        class="relative z-10 flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 lg:py-16 flex flex-col justify-center">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center">

            <div class="lg:col-span-7 flex flex-col items-start space-y-6">
                <div
                    class="animate-fade-up inline-flex items-center gap-2.5 px-4 py-1.5 rounded-full bg-white/80 border border-blue-200/80 text-xs font-semibold text-blue-900 shadow-sm">
                    <div class="relative flex items-center justify-center w-2.5 h-2.5">
                        <span
                            class="absolute inline-flex h-full w-full rounded-full bg-blue-500 opacity-75 animate-ping"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-600"></span>
                    </div>
                    <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2.5"
                        viewBox="0 0 24 24">
                        <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round"
                            stroke-linejoin="round"></path>
                    </svg>
                    <span class="tracking-wide">INICIATIVA COMUNITARIA • FUNDACIÓN BÍO BÍO CULTURAL</span>
                </div>

                <div class="animate-fade-up delay-100 space-y-2">
                    <h1
                        class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-slate-950 tracking-tight leading-[1.12]">
                        Bienvenido a <span
                            class="bg-gradient-to-r from-blue-700 via-blue-600 to-cyan-500 bg-clip-text text-transparent">JuntApp</span>
                    </h1>
                    <p class="text-xl sm:text-2xl font-bold text-blue-600 tracking-tight">
                        Plataforma de Gestión y Coordinación Comunitaria
                    </p>
                </div>

                <p
                    class="animate-fade-up delay-200 text-base sm:text-lg text-slate-600 leading-relaxed max-w-2xl font-normal">
                    Herramienta digital diseñada para fortalecer el trabajo de las Juntas de Vecinos y Organizaciones
                    Sociales en Yumbel y sus alrededores. Nuestro objetivo es optimizar la administración de fondos y
                    agilizar la comunicación, haciendo los procesos más accesibles y transparentes para toda la
                    comunidad local.
                </p>

                <div
                    class="animate-fade-up delay-300 flex flex-col sm:flex-row items-stretch sm:items-center gap-4 w-full sm:w-auto pt-2">
                    <a class="group shimmer-btn animate-shimmer relative inline-flex items-center justify-center gap-3 px-8 py-4 rounded-2xl text-white font-bold text-base shadow-xl shadow-blue-600/30 hover:shadow-blue-600/50 transform hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-blue-300"
                        href="/admin">
                        <span>Pulse aquí para acceder a la página</span>
                        <svg class="w-5 h-5 group-hover:translate-x-1.5 transition-transform duration-200"
                            fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path d="M14 5l7 7m0 0l-7 7m7-7H3" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </a>
                </div>

                <div
                    class="animate-fade-up delay-400 w-full pt-6 border-t border-slate-200/70 grid grid-cols-3 gap-4 text-left">
                    <div>
                        <div class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">100%</div>
                        <div class="text-xs sm:text-sm font-medium text-slate-500 mt-0.5">Transparencia en Fondos</div>
                    </div>
                    <div>
                        <div class="text-2xl sm:text-3xl font-extrabold text-blue-600 tracking-tight">Comunal</div>
                        <div class="text-xs sm:text-sm font-medium text-slate-500 mt-0.5">Acceso y Participación</div>
                    </div>
                    <div>
                        <div class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">+18</div>
                        <div class="text-xs sm:text-sm font-medium text-slate-500 mt-0.5">Juntas Conectadas</div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-5 flex justify-center w-full">
                <div
                    class="w-full max-w-md rounded-3xl overflow-hidden shadow-2xl shadow-blue-900/15 border border-white/90 bg-white/85 backdrop-blur-xl animate-float-slow transition-all">
                    <div class="bg-slate-900 px-5 py-3.5 flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <span class="w-3 h-3 rounded-full bg-[#ff5f56] inline-block"></span>
                            <span class="w-3 h-3 rounded-full bg-[#ffbd2e] inline-block"></span>
                            <span class="w-3 h-3 rounded-full bg-[#27c93f] inline-block"></span>
                        </div>
                        <span
                            class="text-xs font-mono text-slate-300 font-medium tracking-tight">portal.juntapp.cl/yumbel</span>
                        <div
                            class="flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-blue-900/80 border border-blue-500/40 text-[11px] font-semibold text-blue-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                            <span>En Línea</span>
                        </div>
                    </div>
                    <div class="p-6 space-y-5">
                        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                            <div class="flex items-center gap-3.5">
                                <div
                                    class="w-12 h-12 rounded-2xl bg-blue-100 border border-blue-200 flex items-center justify-center font-bold text-blue-700 text-sm shadow-inner">
                                    JY</div>
                                <div>
                                    <span
                                        class="text-[11px] font-bold tracking-wider uppercase text-slate-400 block">Comuna
                                        de Yumbel</span>
                                    <h2 class="text-sm font-bold text-slate-900 leading-snug">Junta de Vecinos N° 4 El
                                        Centenario</h2>
                                </div>
                            </div>
                            <span
                                class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/80">Vigente
                                2026</span>
                        </div>
                        <div class="grid grid-cols-2 gap-3.5">
                            <div
                                class="p-4 rounded-2xl bg-white border border-slate-100 shadow-sm hover:shadow-md hover:border-blue-100 transition-all">
                                <span class="text-xs font-medium text-slate-500 block">Fondo Comunitario</span>
                                <span class="text-xl font-extrabold text-slate-900 mt-1 block tracking-tight">$
                                    1.840.000</span>
                                <div class="flex items-center gap-1.5 mt-2 text-[11px] font-semibold text-emerald-600">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5"
                                        viewBox="0 0 24 24">
                                        <path d="M5 10l7-7m0 0l7 7m-7-7v18" stroke-linecap="round"
                                            stroke-linejoin="round"></path>
                                    </svg>
                                    <span>Auditoría aprobada</span>
                                </div>
                            </div>
                            <div
                                class="p-4 rounded-2xl bg-white border border-slate-100 shadow-sm hover:shadow-md hover:border-blue-100 transition-all">
                                <span class="text-xs font-medium text-slate-500 block">Vecinos Inscritos</span>
                                <span class="text-xl font-extrabold text-slate-900 mt-1 block tracking-tight">142
                                    Activos</span>
                                <div class="flex items-center gap-1.5 mt-2 text-[11px] font-semibold text-blue-600">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.2"
                                        viewBox="0 0 24 24">
                                        <path
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                                            stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                    <span>Quórum disponible</span>
                                </div>
                            </div>
                        </div>
                        <div
                            class="p-4 rounded-2xl bg-blue-50/90 border border-blue-100 flex items-start gap-3.5 relative overflow-hidden">
                            <div
                                class="w-9 h-9 rounded-xl bg-blue-600 text-white flex-shrink-0 flex items-center justify-center shadow-md shadow-blue-500/30">
                                <svg class="w-5 h-5 animate-bounce" fill="none" stroke="currentColor"
                                    stroke-width="2" viewBox="0 0 24 24">
                                    <path
                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"
                                        stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </div>
                            <div class="space-y-1">
                                <h3 class="text-xs font-bold text-slate-900">Asamblea Extraordinaria de Proyectos</h3>
                                <p class="text-xs text-slate-600 leading-relaxed">Sábado 18:00 hrs en Sede Comunitaria.
                                    Revisión de luminarias y plaza infantil.</p>
                            </div>
                        </div>
                        <div
                            class="pt-2 flex items-center justify-between text-slate-500 text-[11px] font-medium border-t border-slate-100">
                            <span class="flex items-center gap-1.5 text-slate-600">
                                <svg class="w-3.5 h-3.5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path clip-rule="evenodd"
                                        d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        fill-rule="evenodd"></path>
                                </svg>
                                Datos respaldados para rendición municipal
                            </span>
                            <span class="text-slate-400 font-mono text-[10px]">Yumbel 2026</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <section aria-label="Pilares fundamentales de JuntApp"
            class="mt-16 sm:mt-24 grid grid-cols-1 md:grid-cols-3 gap-6 animate-fade-up delay-500">
            <article
                class="glass-card rounded-2xl p-6 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-xl hover:border-blue-200 group">
                <div
                    class="w-12 h-12 rounded-xl bg-blue-100/90 text-blue-600 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                            stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-900 mb-2">Gestión de Fondos</h3>
                <p class="text-sm text-slate-600 leading-relaxed">Administración financiera clara, seguimiento de
                    aportes y rendición de cuentas simplificada y auditable.</p>
            </article>
            <article
                class="glass-card rounded-2xl p-6 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-xl hover:border-blue-200 group">
                <div
                    class="w-12 h-12 rounded-xl bg-indigo-100/90 text-indigo-600 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"
                            stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-900 mb-2">Comunicación Vecinal</h3>
                <p class="text-sm text-slate-600 leading-relaxed">Boletines oficiales, avisos de emergencias comunales
                    y coordinación directa y oportuna entre vecinos.</p>
            </article>
            <article
                class="glass-card rounded-2xl p-6 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-xl hover:border-blue-200 group">
                <div
                    class="w-12 h-12 rounded-xl bg-emerald-100/90 text-emerald-600 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"
                            stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-900 mb-2">Comunidad Transparente</h3>
                <p class="text-sm text-slate-600 leading-relaxed">Acceso abierto a directivas, asambleas y proyectos
                    para todas las organizaciones de Yumbel.</p>
            </article>
        </section>
    </main>

    <footer class="relative z-20 border-t border-white/60 bg-white/60 backdrop-blur-md py-6 mt-12">
        <div
            class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-4 text-xs text-slate-500 font-medium">
            <p class="text-center md:text-left text-slate-600">
                © 2026 JuntApp - Fundación Bío Bío Cultural, Yumbel. Desarrollando soluciones para la comunidad.
            </p>
        </div>
    </footer>
</body>

</html>
