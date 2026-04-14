<!DOCTYPE html>
<html class="dark" lang="es">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Esencia Retro - Iniciar Sesión</title>
    <link rel="icon" type="image/png" href="{{ asset('img/icon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "primary": "#eabc4e",
                        "on-primary": "#3a2b00",
                        "primary-container": "#554100",
                        "on-primary-container": "#ffdea2",
                        "secondary": "#d0c5a0",
                        "on-secondary": "#353020",
                        "secondary-container": "#4c4634",
                        "on-secondary-container": "#ece5c0",
                        "tertiary": "#a1d0ba",
                        "on-tertiary": "#043725",
                        "tertiary-container": "#234e3b",
                        "on-tertiary-container": "#bceccf",
                        "error": "#ffb4ab",
                        "on-error": "#690005",
                        "error-container": "#93000a",
                        "on-error-container": "#ffdad6",
                        "background": "#000000",
                        "on-background": "#e4e2de",
                        "surface": "#000000",
                        "on-surface": "#e4e2de",
                        "surface-variant": "#1c2026",
                        "on-surface-variant": "#c8c6c0",
                        "outline": "#919089",
                        "outline-variant": "#46453f",
                        "surface-container-lowest": "#000000",
                        "surface-container-low": "#0a0a0a",
                        "surface-container": "#111111",
                        "surface-container-high": "#1a1a1a",
                        "surface-container-highest": "#242424",
                        "inverse-surface": "#e4e2de",
                        "inverse-on-surface": "#000000",
                        "inverse-primary": "#735c00"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    "fontFamily": {
                        "headline": ["Inter"],
                        "body": ["Inter"],
                        "label": ["Inter"]
                    }
                },
            },
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #000000; color: #e4e2de; }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .glass-panel {
            background: rgba(10, 10, 10, 0.6);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        .primary-gradient {
            background: linear-gradient(135deg, #eabc4e 0%, #8a6c1c 100%);
        }
        .active-glow {
            box-shadow: 0 0 25px rgba(234, 188, 78, 0.15);
            border-color: rgba(234, 188, 78, 0.3) !important;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-8px); }
        }
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 20px rgba(234, 188, 78, 0.1); }
            50% { box-shadow: 0 0 40px rgba(234, 188, 78, 0.2); }
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center overflow-hidden selection:bg-primary/30 selection:text-primary">
    <main class="flex w-full h-screen">
        <!-- Left Side: Professional Illustration & Features -->
        <section class="hidden lg:flex flex-col w-1/2 bg-[#000000] relative overflow-hidden px-16 justify-center border-r border-white/5">
            <!-- Background Atmospheric Glow -->
            <div class="absolute top-[-10%] right-[-10%] w-[500px] h-[500px] bg-primary/10 rounded-full blur-[120px]"></div>
            <div class="absolute bottom-[-5%] left-[-5%] w-[400px] h-[400px] bg-primary/5 rounded-full blur-[100px]"></div>
            
            <div class="relative z-10 flex flex-col items-center">
                <div class="mb-12 flex flex-col items-center text-center">
                    <div class="w-80 h-40 flex items-center justify-center mb-4">
                        <img src="{{ asset('img/logo.png') }}" alt="Esencia Retro Logo" class="max-w-full max-h-full mix-blend-screen opacity-100 object-contain">
                    </div>
                    <p class="text-on-surface-variant text-lg font-medium">Sistema de Gestión para Alta Gastronomía</p>
                </div>

                <!-- Featured Card (Bento-style pattern) -->
                <div class="grid grid-cols-1 gap-5 w-full max-w-md">
                    <div class="p-6 rounded-xl glass-panel group hover:bg-surface-container-high transition-all duration-300 hover:border-primary/20" style="animation: pulse-glow 4s ease-in-out infinite;">
                        <div class="flex items-center gap-4 text-left">
                            <div class="flex-shrink-0 p-3 rounded-lg bg-primary/10 text-primary border border-primary/20">
                                <span class="material-symbols-outlined text-3xl">grid_view</span>
                            </div>
                            <div>
                                <h3 class="font-bold text-xl text-on-surface mb-0.5">Gestión de mesas</h3>
                                <p class="text-on-surface-variant text-sm leading-relaxed">Control visual en tiempo real de disponibilidad y estados.</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 rounded-xl glass-panel group hover:bg-surface-container-high transition-all duration-300 hover:border-primary/20">
                        <div class="flex items-center gap-4 text-left">
                            <div class="flex-shrink-0 p-3 rounded-lg bg-tertiary-container/20 text-tertiary border border-tertiary/20">
                                <span class="material-symbols-outlined text-3xl">inventory_2</span>
                            </div>
                            <div>
                                <h3 class="font-bold text-xl text-on-surface mb-0.5">Control de inventario</h3>
                                <p class="text-on-surface-variant text-sm leading-relaxed">Monitoreo automatizado de insumos y alertas de stock.</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 rounded-xl glass-panel group hover:bg-surface-container-high transition-all duration-300 hover:border-primary/20">
                        <div class="flex items-center gap-4 text-left">
                            <div class="flex-shrink-0 p-3 rounded-lg bg-secondary-container/20 text-secondary border border-secondary/20">
                                <span class="material-symbols-outlined text-3xl">bar_chart</span>
                            </div>
                            <div>
                                <h3 class="font-bold text-xl text-on-surface mb-0.5">Reportes analíticos</h3>
                                <p class="text-on-surface-variant text-sm leading-relaxed">Análisis detallado de ventas y métricas operativas.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Decorative Image Anchor -->
            <div class="absolute bottom-12 left-16 right-16">
                <div class="h-1 bg-gradient-to-r from-primary/30 via-transparent to-transparent rounded-full opacity-50"></div>
            </div>
        </section>

        <!-- Right Side: Login Form -->
        <section class="w-full lg:w-1/2 flex items-center justify-center bg-surface px-6 md:px-20 lg:px-24">
            <div class="w-full max-w-md">
                <div class="lg:hidden mb-12 flex justify-center">
                    <img src="{{ asset('img/logo.png') }}" alt="Esencia Retro Logo" class="w-56 h-auto mix-blend-screen object-contain">
                </div>

                <div class="mb-10 text-center lg:text-left">
                    <h2 class="text-3xl font-bold text-on-surface tracking-tight mb-3">Bienvenido de nuevo</h2>
                    <p class="text-on-surface-variant">Por favor, ingresa tus credenciales para continuar.</p>
                </div>

                <form method="GET" action="{{ route('admin.dashboard') }}" class="space-y-6">
                    <!-- Role Selection -->
                    <div class="space-y-3">
                        <label class="text-sm font-semibold uppercase tracking-widest text-on-surface-variant px-1">Rol de Acceso</label>
                        <div class="grid grid-cols-3 gap-3">
                            <label class="relative flex flex-col items-center gap-2 p-3 rounded-xl bg-surface-container-low border border-outline-variant/10 cursor-pointer hover:border-primary/50 transition-all">
                                <input type="radio" name="role" value="admin" class="sr-only peer" checked>
                                <span class="material-symbols-outlined text-on-surface-variant peer-checked:text-primary transition-colors">admin_panel_settings</span>
                                <span class="text-[10px] font-bold uppercase text-on-surface-variant peer-checked:text-on-surface">Admin</span>
                                <div class="absolute inset-0 rounded-xl border-2 border-transparent peer-checked:border-primary/40 pointer-events-none"></div>
                            </label>
                            
                            <label class="relative flex flex-col items-center gap-2 p-3 rounded-xl bg-surface-container-low border border-outline-variant/10 cursor-pointer hover:border-primary/50 transition-all">
                                <input type="radio" name="role" value="waiter" class="sr-only peer">
                                <span class="material-symbols-outlined text-on-surface-variant peer-checked:text-primary transition-colors">restaurant</span>
                                <span class="text-[10px] font-bold uppercase text-on-surface-variant peer-checked:text-on-surface">Mesero</span>
                                <div class="absolute inset-0 rounded-xl border-2 border-transparent peer-checked:border-primary/40 pointer-events-none"></div>
                            </label>

                            <label class="relative flex flex-col items-center gap-2 p-3 rounded-xl bg-surface-container-low border border-outline-variant/10 cursor-pointer hover:border-primary/50 transition-all">
                                <input type="radio" name="role" value="kitchen" class="sr-only peer">
                                <span class="material-symbols-outlined text-on-surface-variant peer-checked:text-primary transition-colors">outdoor_grill</span>
                                <span class="text-[10px] font-bold uppercase text-on-surface-variant peer-checked:text-on-surface">Cocina</span>
                                <div class="absolute inset-0 rounded-xl border-2 border-transparent peer-checked:border-primary/40 pointer-events-none"></div>
                            </label>
                        </div>
                    </div>

                    <!-- Username -->
                    <div class="space-y-2">
                        <label for="username" class="text-sm font-semibold text-on-surface-variant px-1">Usuario</label>
                        <div class="relative group">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline group-focus-within:text-primary transition-colors">person</span>
                            <input type="text" id="username" placeholder="Ingrese su usuario" class="w-full pl-12 pr-4 py-4 rounded-xl bg-surface-container-low border border-outline-variant/20 focus:border-primary focus:ring-0 text-on-surface placeholder:text-outline transition-all outline-none">
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="space-y-2">
                        <div class="flex justify-between items-center px-1">
                            <label for="password" class="text-sm font-semibold text-on-surface-variant">Contraseña</label>
                            <a href="#" class="text-xs font-medium text-primary hover:text-primary/80 transition-colors">¿Olvidó su contraseña?</a>
                        </div>
                        <div class="relative group">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline group-focus-within:text-primary transition-colors">lock</span>
                            <input type="password" id="password" placeholder="••••••••" class="w-full pl-12 pr-4 py-4 rounded-xl bg-surface-container-low border border-outline-variant/20 focus:border-primary focus:ring-0 text-on-surface placeholder:text-outline transition-all outline-none">
                        </div>
                    </div>

                    <!-- CTA Button -->
                    <button type="submit" class="w-full py-4 rounded-xl primary-gradient text-on-primary font-bold text-lg shadow-lg shadow-primary/20 hover:shadow-primary/30 active:scale-[0.98] transition-all flex items-center justify-center gap-2 mt-4">
                        <span>Ingresar al Sistema</span>
                        <span class="material-symbols-outlined">arrow_forward</span>
                    </button>
                </form>

                <div class="mt-12 pt-8 border-t border-outline-variant/10 text-center">
                    <p class="text-sm text-on-surface-variant">
                        Terminal 01 • Puerto Seguro • Versión 2.4.0
                    </p>
                </div>
            </div>
        </section>
    </main>

    <!-- Visual Polish: Grain Overlay -->
    <div class="fixed inset-0 pointer-events-none z-50 opacity-[0.03] mix-blend-overlay">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200">
            <filter id="noise">
                <feTurbulence type="fractalNoise" baseFrequency="0.65" numOctaves="3" stitchTiles="stitch"/>
            </filter>
            <rect width="100%" height="100%" filter="url(#noise)"/>
        </svg>
    </div>
</body>
</html>
