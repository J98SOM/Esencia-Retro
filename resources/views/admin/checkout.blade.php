<!DOCTYPE html>
<html class="dark" lang="es">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Checkout - Esencia Retro</title>
    <link rel="icon" type="image/png" href="{{ asset('img/icon.png') }}">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
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
                    "DEFAULT": "0.5rem",
                    "lg": "0.75rem",
                    "xl": "1rem",
                    "2xl": "1.5rem",
                    "3xl": "2rem",
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
        .glass-card {
            background: #111111;
            border: 1px solid rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
        }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 48;
        }
        .primary-gradient {
            background: linear-gradient(135deg, #eabc4e 0%, #8a6c1c 100%);
        }
        .keypad-btn {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.2s;
        }
        .keypad-btn:hover {
            background: rgba(234, 188, 78, 0.1);
            border-color: rgba(234, 188, 78, 0.2);
        }
        .keypad-btn:active {
            transform: scale(0.95);
            background: rgba(234, 188, 78, 0.2);
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.02);
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(234, 188, 78, 0.2);
            border-radius: 10px;
        }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #000000; }
        ::-webkit-scrollbar-thumb { background: #1a1a1a; border-radius: 10px; }
    </style>
</head>
<body class="min-h-screen bg-surface flex flex-col">
    <!-- Header -->
    <header class="p-6 flex items-center justify-between border-b border-white/5 bg-[#050505] sticky top-0 z-50">
        <div class="flex items-center gap-5">
            <a href="{{ route('admin.pedido', ['id' => $mesaId ?? '0']) }}" class="w-11 h-11 rounded-full border border-outline-variant/30 flex items-center justify-center hover:bg-surface-container-high transition-all hover:scale-105 active:scale-95 text-white hover:border-primary/40">
                <span class="material-symbols-outlined text-base">arrow_back</span>
            </a>
            <div class="flex items-center gap-3">
                <img src="{{ asset('img/icon.png') }}" class="w-8 h-8 mix-blend-screen" alt="App Icon">
                <div class="flex flex-col">
                    <span class="text-xs font-black text-primary uppercase tracking-[0.2em] mb-0.5">Esencia Retro • Terminal 01</span>
                    <span class="text-sm font-semibold text-white/90">Volver a Pedido</span>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-6">
            <div class="text-right">
                <p class="text-[10px] text-on-surface-variant uppercase font-black tracking-widest mb-0.5">Cajero en Turno</p>
                <p class="text-base font-bold text-white">Alex S.</p>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-11 h-11 rounded-2xl bg-primary/10 border border-primary/20 flex items-center justify-center">
                    <span class="material-symbols-outlined text-primary text-2xl">account_circle</span>
                </div>
                <!-- Logout Button -->
                <a href="{{ route('login') }}" class="w-11 h-11 rounded-2xl bg-error/10 border border-error/20 flex items-center justify-center text-error hover:bg-error hover:text-on-error transition-all group shadow-lg shadow-error/5" title="Cerrar Sesión">
                    <span class="material-symbols-outlined text-xl group-hover:scale-110 transition-transform">logout</span>
                </a>
            </div>
        </div>
    </header>

    <main class="flex-1 max-w-[1600px] mx-auto w-full px-6 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Column 1: Order Summary -->
            <section class="lg:col-span-3 glass-card rounded-2xl p-6 shadow-2xl flex flex-col">
                <div class="mb-6 flex justify-between items-start">
                    <div>
                        <h2 class="text-xl font-black text-white mb-1 tracking-tight">Resumen</h2>
                        <p class="text-slate-500 text-sm font-medium">Mesa {{ $mesaId ?? '12' }} • #4582</p>
                    </div>
                    <span class="bg-primary/10 text-primary border border-primary/20 px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest">Pendiente</span>
                </div>
                
                <div class="space-y-4 flex-1 overflow-y-auto pr-2 custom-scrollbar mb-6">
                    <div class="flex justify-between items-start pb-4 border-b border-white/5">
                        <div>
                            <h3 class="text-white font-semibold text-sm">Cerveza Artesanal</h3>
                            <p class="text-slate-500 text-[10px] mt-1 uppercase font-bold tracking-wider">1 x $5.99</p>
                        </div>
                        <span class="text-white font-bold text-sm">$5.99</span>
                    </div>
                    <div class="flex justify-between items-start pb-4 border-b border-white/5">
                        <div>
                            <h3 class="text-white font-semibold text-sm">Hamburguesa Premium</h3>
                            <p class="text-slate-500 text-[10px] mt-1 uppercase font-bold tracking-wider">1 x $12.99</p>
                        </div>
                        <span class="text-white font-bold text-sm">$12.99</span>
                    </div>
                </div>

                <div class="space-y-3 bg-surface-container-lowest/50 p-5 rounded-xl border border-white/5">
                    <div class="flex justify-between text-sm text-slate-400 font-medium">
                        <span>Subtotal</span>
                        <span class="text-white">$18.98</span>
                    </div>
                    <div class="flex justify-between text-sm text-slate-400 font-medium">
                        <span>Servicio (10%)</span>
                        <span class="text-white">$1.90</span>
                    </div>
                </div>

                <div class="mt-4 grid grid-cols-2 gap-3">
                    <button class="flex items-center justify-center gap-2 py-3 rounded-xl bg-surface-container-highest border border-white/5 text-white text-xs font-black uppercase tracking-widest hover:bg-surface-container-high transition-all">
                        <span class="material-symbols-outlined text-lg">print</span> Ticket
                    </button>
                    <button class="py-3 rounded-xl bg-surface-container-highest border border-white/5 text-white text-xs font-black uppercase tracking-widest hover:bg-surface-container-high transition-all">
                        Dividir
                    </button>
                </div>
            </section>

            <!-- Column 2: Payment Methods & Shortcuts -->
            <section class="lg:col-span-5 flex flex-col gap-8">
                <div class="glass-card rounded-2xl p-8 shadow-xl">
                    <div class="mb-6">
                        <h2 class="text-xl font-black text-white mb-1 tracking-tight">Método de Pago</h2>
                        <p class="text-slate-500 text-sm font-medium">Selecciona una opción de cobro</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <button class="primary-gradient rounded-2xl p-6 flex flex-col items-center justify-center gap-3 ring-2 ring-primary ring-offset-4 ring-offset-[#111111] transition-all transform hover:scale-[1.02]">
                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center shadow-inner">
                                <span class="material-symbols-outlined text-white text-2xl">payments</span>
                            </div>
                            <span class="text-white font-black text-xs uppercase tracking-widest">Efectivo</span>
                        </button>
                        <button class="bg-surface-container-highest/50 border border-white/5 rounded-2xl p-6 flex flex-col items-center justify-center gap-3 hover:bg-white/5 transition-all transform hover:scale-[1.02]">
                            <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center">
                                <span class="material-symbols-outlined text-blue-400 text-2xl">credit_card</span>
                            </div>
                            <span class="text-slate-300 font-black text-xs uppercase tracking-widest">Tarjeta</span>
                        </button>
                        <button class="bg-surface-container-highest/50 border border-white/5 rounded-2xl p-6 flex flex-col items-center justify-center gap-3 hover:bg-white/5 transition-all transform hover:scale-[1.02]">
                            <div class="w-12 h-12 bg-purple-500/20 rounded-xl flex items-center justify-center">
                                <span class="material-symbols-outlined text-purple-400 text-2xl">smartphone</span>
                            </div>
                            <span class="text-slate-300 font-black text-xs uppercase tracking-widest">Transferencia</span>
                        </button>
                        <button class="bg-surface-container-highest/50 border border-white/5 rounded-2xl p-6 flex flex-col items-center justify-center gap-3 hover:bg-white/5 transition-all transform hover:scale-[1.02]">
                            <div class="w-12 h-12 bg-orange-500/20 rounded-xl flex items-center justify-center">
                                <span class="material-symbols-outlined text-orange-400 text-2xl">account_balance_wallet</span>
                            </div>
                            <span class="text-slate-300 font-black text-xs uppercase tracking-widest">Mixto</span>
                        </button>
                    </div>
                </div>

                <div class="glass-card rounded-2xl p-8 shadow-xl flex-1 flex flex-col">
                    <div class="mb-6">
                        <h3 class="text-lg font-black text-white uppercase tracking-[0.15em]">Atajos de Efectivo</h3>
                        <p class="text-slate-500 text-xs mt-1">Montos rápidos sugeridos</p>
                    </div>
                    <div class="grid grid-cols-3 gap-4">
                        <button class="py-5 bg-white/5 border border-white/10 rounded-xl font-black text-2xl text-primary hover:bg-primary hover:text-on-primary transition-all transform active:scale-95">$50</button>
                        <button class="py-5 bg-white/5 border border-white/10 rounded-xl font-black text-2xl text-primary hover:bg-primary hover:text-on-primary transition-all transform active:scale-95">$100</button>
                        <button class="py-5 bg-white/5 border border-white/10 rounded-xl font-black text-2xl text-primary hover:bg-primary hover:text-on-primary transition-all transform active:scale-95">$200</button>
                    </div>
                    <div class="mt-auto pt-8">
                        <a href="{{ route('admin.mesas') }}" class="w-full flex items-center justify-center primary-gradient text-on-primary py-6 rounded-2xl font-black text-lg tracking-[0.2em] shadow-2xl shadow-primary/30 hover:shadow-primary/50 hover:-translate-y-0.5 active:translate-y-0.5 transition-all uppercase">
                            FINALIZAR PAGO
                        </a>
                    </div>
                </div>
            </section>

            <!-- Column 3: Calculator + Payment Summary (Combined) -->
            <section class="lg:col-span-4 flex flex-col gap-6">
                <!-- Payment Summary Card (Total, Received, Change - All Together) -->
                <div class="glass-card rounded-2xl p-6 shadow-xl relative overflow-hidden">
                    <div class="absolute -right-8 -top-8 w-32 h-32 bg-primary/10 blur-3xl rounded-full"></div>
                    <div class="relative z-10">
                        <!-- Total -->
                        <div class="flex justify-between items-center pb-4 border-b border-white/10">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-primary/10 border border-primary/20 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-primary text-xl">receipt_long</span>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-on-surface-variant uppercase tracking-widest">Total a Pagar</p>
                                    <p class="text-xs text-slate-500">USD</p>
                                </div>
                            </div>
                            <span class="text-3xl font-black text-white tracking-tighter">$20.88</span>
                        </div>
                        
                        <!-- Monto Recibido -->
                        <div class="flex justify-between items-center py-4 border-b border-white/10">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-emerald-400 text-xl">payments</span>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-on-surface-variant uppercase tracking-widest">Monto Recibido</p>
                                    <p class="text-xs text-slate-500">Efectivo</p>
                                </div>
                            </div>
                            <span class="text-3xl font-black text-emerald-400 tracking-tighter">$100.00</span>
                        </div>

                        <!-- Cambio -->
                        <div class="flex justify-between items-center pt-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-primary/10 border border-primary/20 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-primary text-xl">currency_exchange</span>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-primary uppercase tracking-widest">Cambio a Entregar</p>
                                    <p class="text-xs text-slate-500">USD</p>
                                </div>
                            </div>
                            <span class="text-4xl font-black text-primary tracking-tighter">$79.12</span>
                        </div>
                    </div>
                </div>

                <!-- Calculator -->
                <div class="glass-card rounded-2xl p-6 shadow-xl flex flex-col gap-5 flex-1">
                    <div>
                        <h2 class="text-lg font-black text-white mb-1 tracking-tight">Calculadora</h2>
                        <p class="text-slate-500 text-xs font-medium">Ingresa el monto recibido</p>
                    </div>
                    <div class="relative group">
                        <span class="absolute left-6 top-1/2 -translate-y-1/2 text-2xl font-black text-primary group-focus-within:scale-110 transition-transform">$</span>
                        <input class="w-full bg-surface-container-lowest border-2 border-primary/20 focus:border-primary rounded-2xl py-6 pl-14 pr-6 text-4xl font-black text-white ring-0 outline-none transition-all text-right shadow-inner" placeholder="0.00" type="text" value="100.00"/>
                    </div>
                    <div class="grid grid-cols-3 gap-3 flex-1">
                        <button class="keypad-btn py-4 rounded-xl text-2xl font-black text-white">1</button>
                        <button class="keypad-btn py-4 rounded-xl text-2xl font-black text-white">2</button>
                        <button class="keypad-btn py-4 rounded-xl text-2xl font-black text-white">3</button>
                        <button class="keypad-btn py-4 rounded-xl text-2xl font-black text-white">4</button>
                        <button class="keypad-btn py-4 rounded-xl text-2xl font-black text-white">5</button>
                        <button class="keypad-btn py-4 rounded-xl text-2xl font-black text-white">6</button>
                        <button class="keypad-btn py-4 rounded-xl text-2xl font-black text-white">7</button>
                        <button class="keypad-btn py-4 rounded-xl text-2xl font-black text-white">8</button>
                        <button class="keypad-btn py-4 rounded-xl text-2xl font-black text-white">9</button>
                        <button class="keypad-btn py-4 rounded-xl text-2xl font-black text-white">.</button>
                        <button class="keypad-btn py-4 rounded-xl text-2xl font-black text-white">0</button>
                        <button class="keypad-btn py-4 rounded-xl text-white flex items-center justify-center hover:bg-error/20 hover:text-error hover:border-error/30 transition-all">
                            <span class="material-symbols-outlined text-2xl">backspace</span>
                        </button>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <!-- Decorative Background Elements -->
    <div class="fixed top-[-10%] right-[-10%] w-[800px] h-[800px] bg-primary/5 rounded-full blur-[150px] -z-10"></div>
    <div class="fixed bottom-[-10%] left-[-10%] w-[700px] h-[700px] bg-primary/3 rounded-full blur-[150px] -z-10"></div>
</body>
</html>
