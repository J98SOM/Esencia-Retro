<!DOCTYPE html>
<html class="dark" lang="es">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>@yield('title', 'Esencia Retro - Command Center')</title>
    <link rel="icon" type="image/png" href="{{ asset('img/icon.png') }}">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
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
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        .glass { background: rgba(234, 188, 78, 0.05); backdrop-filter: blur(20px); }
        .glass-card { background: rgba(20, 20, 20, 0.6); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.05); }
        .glass-panel { background: rgba(10, 10, 10, 0.6); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.05); }
        .active-glow { box-shadow: 0 0 25px rgba(234, 188, 78, 0.15); border-color: rgba(234, 188, 78, 0.3) !important; }
        .primary-gradient { background: linear-gradient(135deg, #eabc4e 0%, #8a6c1c 100%); }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #000000; }
        ::-webkit-scrollbar-thumb { background: #1a1a1a; border-radius: 10px; }
        
        /* Sidebar Collapsed State */
        body.sidebar-collapsed #sidebar { width: 5.5rem; } /* ~22px / w-22 equivalent */
        body.sidebar-collapsed #main-content { margin-left: 5.5rem; }
        body.sidebar-collapsed .sidebar-text { opacity: 0; display: none; }
        body.sidebar-collapsed #sidebar-logo { display: none; }
        body.sidebar-collapsed #sidebar-icon { display: block; }
        body.sidebar-collapsed .nav-link, body.sidebar-collapsed .nav-btn { justify-content: center; padding-left: 0; padding-right: 0; }
        body.sidebar-collapsed #sidebar-toggle-icon { transform: rotate(180deg); }
        
        /* Mobile handling overrides */
        @media (max-width: 768px) {
            #main-content { margin-left: 0 !important; }
            #sidebar { width: 16rem; transform: translateX(-100%); }
            body.mobile-open #sidebar { transform: translateX(0); }
            body.sidebar-collapsed #sidebar { width: 16rem; }
            body.sidebar-collapsed .sidebar-text { opacity: 1; display: block; }
            body.sidebar-collapsed #sidebar-logo { display: block; }
            body.sidebar-collapsed #sidebar-icon { display: none; }
            body.sidebar-collapsed .nav-link { justify-content: flex-start; padding-left: 1rem; padding-right: 1rem; }
        }
    </style>
    <script>
        window.VITE_API_URL = '/api';
        window.API_BASE = '/api';
    </script>
    @stack('styles')
</head>
<body class="flex min-h-screen selection:bg-primary/30 selection:text-primary">
    <!-- Sidebar Component -->
    <x-sidebar />

    <!-- Main Content Canvas -->
    <main id="main-content" class="md:ml-64 flex-1 flex flex-col min-h-screen bg-surface transition-all duration-300 relative w-full">
        <!-- Mobile Header Toggle (Only visible on small screens) -->
        <div class="md:hidden flex items-center justify-between p-4 bg-[#050505] border-b border-white/5 sticky top-0 z-40">
            <div class="flex items-center gap-3">
                <img src="{{ asset('img/icon.png') }}" class="w-8 h-8 mix-blend-screen" alt="App Icon">
                <span class="font-bold text-white text-sm tracking-widest">ESENCIA RETRO</span>
            </div>
            <button onclick="toggleMobileMenu()" class="text-white hover:text-primary transition-colors p-2 bg-white/5 rounded-lg border border-white/10">
                <span class="material-symbols-outlined">menu</span>
            </button>
        </div>
        
        <div class="flex-1 flex flex-col overflow-x-hidden">
            @yield('content')
        </div>

        <!-- Footer -->
        <footer class="w-full border-t border-white/5 bg-[#050505] py-12 px-8 flex flex-col md:flex-row justify-between items-center mt-auto z-40">
            <div class="mb-6 md:mb-0">
                <p class="font-bold text-primary">Esencia Retro</p>
                <p class="font-['Inter'] text-sm text-slate-500 mt-1">© {{ date('Y') }} Esencia Retro. Todos los derechos reservados.</p>
            </div>
            <div class="flex space-x-8">
                <a href="#" class="font-['Inter'] text-sm text-slate-500 hover:text-white transition-colors">Política de Privacidad</a>
                <a href="#" class="font-['Inter'] text-sm text-slate-500 hover:text-white transition-colors">Términos de Servicio</a>
                <a href="#" class="font-['Inter'] text-sm text-slate-500 hover:text-white transition-colors">Contacto</a>
            </div>
        </footer>
    </main>

    <!-- Global Modals Container -->
    <div id="modal-overlay" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[100] hidden items-center justify-center opacity-0 transition-opacity duration-300">
        <!-- New Order Modal -->
        @php
            $freeMesas = \App\Models\Mesa::whereDoesntHave('facturas', function($query) {
                $query->whereNotIn(\Illuminate\Support\Facades\DB::raw('LOWER(estatus)'), ['pagado', 'pagada']);
            })->orderBy('nombre')->get();
            $hasActiveCaja = \App\Models\AperturaCaja::where('estado', 'abierta')->exists();
        @endphp
        <div id="modal-new-order" class="modal-content hidden bg-surface-container-low border border-white/10 p-8 rounded-3xl w-full max-w-md shadow-2xl transform scale-95 transition-transform duration-300">
            <h3 class="text-2xl font-black text-white mb-2">Nueva Orden</h3>
            <p class="text-sm text-on-surface-variant mb-6">Selecciona una mesa libre para abrir una nueva orden rápida.</p>
            
            <div class="space-y-3 mb-6 max-h-64 overflow-y-auto pr-2" id="mesas-list-container">
                @foreach($freeMesas as $m)
                    @if($hasActiveCaja)
                        <a href="{{ route('admin.pedido', ['id' => $m->id]) }}" class="flex items-center justify-between p-4 rounded-xl border border-white/5 transition-all bg-surface hover:bg-surface-container-highest hover:border-primary/50 group">
                    @else
                        <a href="javascript:void(0)" onclick="openModal('modal-caja-cerrada-alerta')" class="flex items-center justify-between p-4 rounded-xl border border-white/5 transition-all bg-surface hover:bg-surface-container-highest hover:border-primary/50 group">
                    @endif
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-lg bg-emerald-500/10 text-emerald-400 flex items-center justify-center">
                                <span class="material-symbols-outlined">restaurant</span>
                            </div>
                            <div>
                                <p class="font-bold text-white group-hover:text-primary transition-colors">{{ $m->nombre }}</p>
                                <p class="text-[10px] text-slate-500 uppercase tracking-widest">Capacidad: {{ $m->capacidad }} pax</p>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
            
            <div class="flex gap-3 pt-4 border-t border-white/10">
                <button onclick="closeModals()" class="flex-1 py-3 rounded-xl border border-white/10 hover:bg-white/5 transition-colors font-bold text-sm">Cancelar</button>
            </div>
        </div>

        <!-- Alerta Caja Cerrada Modal (Global) -->
        <div id="modal-caja-cerrada-alerta" class="modal-content hidden bg-surface-container-low border border-white/10 p-8 rounded-3xl w-full max-w-md shadow-2xl transform scale-95 transition-transform duration-300">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-black text-white flex items-center gap-2">
                    <span class="material-symbols-outlined text-error">warning</span>
                    Caja Cerrada
                </h3>
                <button onclick="closeModals()" class="text-outline hover:text-white transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <p class="text-sm text-slate-300 mb-6 leading-relaxed">
                Hasta que no se abra la caja, no es posible registrar pedidos ni gestionar órdenes en las mesas.
            </p>
            <div class="flex gap-3 pt-4 border-t border-white/10">
                <button type="button" onclick="closeModals()" class="flex-1 py-3 rounded-xl border border-white/10 hover:bg-white/5 transition-colors font-bold text-sm text-white">Volver</button>
                @php
                    $currentUserRole = strtolower(optional(auth()->user()->rol)->name ?? '');
                @endphp
                @if($currentUserRole === 'admin')
                    <a href="{{ route('admin.caja') }}" class="flex-1 py-3 bg-gradient-to-br from-primary to-primary-container text-on-primary-container font-bold rounded-xl hover:scale-[0.98] transition-transform text-sm text-center flex items-center justify-center gap-1">
                        <span class="material-symbols-outlined text-sm">lock_open</span>
                        Abrir Caja
                    </a>
                @else
                    <div class="flex-1 py-3 bg-white/5 text-slate-400 font-bold rounded-xl text-xs text-center flex items-center justify-center">
                        Pide al Administrador abrir caja
                    </div>
                @endif
            </div>
        </div>

        <!-- Profile Settings Dropdown Modal -->
        <div id="modal-profile" class="modal-content hidden bg-surface-container-low border border-white/10 p-6 rounded-3xl w-full max-w-sm absolute bottom-24 left-6 shadow-2xl transform scale-95 transition-transform duration-300">
            <div class="flex items-center mb-6 border-b border-white/10 pb-4">
                <div class="w-12 h-12 rounded-full bg-surface-container-highest flex items-center justify-center border border-white/10 overflow-hidden mr-4">
                    <img class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCiWenezqJGbD-1rlSh8Is3huor8fZxWZLXx3y1f9a9228ZWoq_mtHho3gXIPj4ssTtBWFIbYpYNH3Dd1P6GFV8jKd3ieKk7oWUP1EZauBfRGLv2b75v0aqlS4tkgPga-ISdrxYZ5PKQQgqkNq6Rkwzj6xARv3r09m8_tcR0OlRG4dnve3aGcpcepAINAsNgglD61KbQ_SYlkfogXTf4LEBGo_caiaVksVMNHv1ar1HblEJlraXJhRw-tq9Jp-T4lPGhucnStjGiYY4" alt="Operator"/>
                </div>
                <div>
                    <p class="font-bold text-white text-lg">{{ auth()->user()->name ?? 'Usuario' }}</p>
                    <p class="text-[10px] uppercase font-bold text-primary tracking-widest">{{ ucfirst(optional(auth()->user()->rol)->name ?? 'Rol') }}</p>
                </div>
            </div>
            <div class="space-y-2 mb-6">
                <a href="#" class="flex items-center gap-3 p-3 rounded-xl hover:bg-white/5 text-slate-300 hover:text-white transition-colors">
                    <span class="material-symbols-outlined">manage_accounts</span>
                    <span class="font-medium text-sm">Mi Perfil</span>
                </a>
                <a href="#" class="flex items-center gap-3 p-3 rounded-xl hover:bg-white/5 text-slate-300 hover:text-white transition-colors">
                    <span class="material-symbols-outlined">notifications</span>
                    <span class="font-medium text-sm">Notificaciones</span>
                </a>
            </div>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="w-full py-3 rounded-xl bg-error/10 text-error hover:bg-error hover:text-on-error transition-colors flex items-center justify-center gap-2 font-bold text-sm">
                <span class="material-symbols-outlined text-sm">logout</span>
                Cerrar Sesión
            </a>
        </div>
        @php
            $allProducts = \App\Models\Producto::orderBy('nombre')->get();
            $beverageProducts = $allProducts->filter(fn($p) => !$p->esPetaco());
        @endphp
        <!-- Añadir Producto a la orden Modal (global) -->
        <div id="modal-add-product-order" class="modal-content hidden bg-surface-container-low border border-white/10 p-6 md:p-8 rounded-2xl w-full max-w-5xl shadow-2xl transform scale-95 transition-transform duration-300 sm:mx-3 sm:my-4 sm:rounded-xl sm:h-[calc(100vh-4rem)] sm:overflow-hidden">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-black text-white">Menú de Productos</h3>
                <div class="flex gap-4 items-center">
                    <input type="text" id="productos-search-input" class="bg-surface-container-highest border border-white/10 rounded-xl px-4 py-2 text-sm text-white focus:outline-none focus:border-primary transition-all w-48" placeholder="Buscar producto...">
                    <button onclick="closeModals()" class="text-outline hover:text-white transition-colors">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
            </div>

            <form id="add-products-form" method="POST" action="">
                @csrf
                <input type="hidden" name="cantidad" value="1">
                <div id="productos-grid" class="grid gap-6 max-h-[60vh] overflow-y-auto pr-4" style="grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));">
                    @foreach($allProducts as $p)
                        @if($p->esPetaco())
                            <div class="product-card group relative overflow-hidden rounded-xl bg-surface-container-low p-5 md:p-6 transition-all hover:bg-surface-container-high flex flex-col justify-between gap-4 border border-amber-500/20" data-name="{{ strtolower($p->nombre) }}">
                                <div class="flex flex-col md:flex-row items-start gap-4 w-full">
                                    <div class="w-full md:w-28 h-36 md:h-28 rounded-lg overflow-hidden bg-surface-container-highest flex items-center justify-center flex-shrink-0">
                                        <img src="{{ $p->imagen_url ?? '' }}" class="w-full h-full object-cover" onerror="this.style.display='none'" />
                                    </div>
                                    <div class="flex-1 min-w-0 flex flex-col justify-between relative w-full h-full">
                                        <div>
                                            <div class="flex items-center gap-2 flex-wrap">
                                                <p class="font-bold text-white text-base md:text-lg whitespace-nowrap overflow-visible">{{ $p->nombre }}</p>
                                                <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-wider bg-amber-500/20 text-amber-400 border border-amber-500/30">📦 Petaco</span>
                                            </div>
                                            <p class="text-sm md:text-base text-primary font-bold mt-1">${{ number_format($p->precio, 2) }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-2 flex items-center justify-between gap-2 border-t border-white/5 pt-3">
                                    <div class="flex items-center gap-1.5">
                                        <label class="text-[11px] text-slate-500 font-bold">CANT:</label>
                                        <input type="number" name="products[{{ $p->id }}]" id="petaco-qty-{{ $p->id }}" min="0" value="0" class="w-16 rounded bg-surface-container-highest border border-white/10 text-white text-xs p-1.5 text-center focus:outline-none focus:border-primary font-bold">
                                    </div>
                                    <button type="button" onclick="openPetacoConfigModal({{ $p->id }}, '{{ addslashes($p->nombre) }}', '{{ number_format($p->precio, 0, ',', '.') }}')" class="px-3 py-1.5 rounded-lg bg-amber-500/10 border border-amber-500/30 text-amber-400 hover:bg-amber-500 hover:text-black font-bold text-xs flex items-center gap-1.5 transition-all shadow-sm">
                                        <span class="material-symbols-outlined text-sm">local_bar</span>
                                        <span>Bebidas</span>
                                        <span id="petaco-badge-count-{{ $p->id }}" class="hidden bg-amber-400 text-black px-1.5 py-0.5 rounded-full text-[10px] font-black leading-none">0</span>
                                    </button>
                                </div>
                                <!-- Hidden inputs for petaco beverages -->
                                <div id="petaco-hidden-inputs-{{ $p->id }}"></div>
                            </div>
                        @else
                            <div class="product-card group relative overflow-hidden rounded-xl bg-surface-container-low p-5 md:p-6 transition-all hover:bg-surface-container-high flex flex-col md:flex-row items-start gap-4" data-name="{{ strtolower($p->nombre) }}">
                                <div class="w-full md:w-28 h-36 md:h-28 rounded-lg overflow-hidden bg-surface-container-highest flex items-center justify-center flex-shrink-0">
                                    <img src="{{ $p->imagen_url ?? '' }}" class="w-full h-full object-cover" onerror="this.style.display='none'" />
                                </div>
                                <div class="flex-1 min-w-0 flex flex-col justify-between relative">
                                    <div>
                                        <p class="font-bold text-white text-base md:text-lg whitespace-nowrap overflow-visible">{{ $p->nombre }}</p>
                                        <p class="text-sm md:text-base text-primary font-bold mt-1">${{ number_format($p->precio, 2) }}</p>
                                    </div>
                                    <div class="mt-4 flex items-center gap-3">
                                        <label class="text-xs text-slate-500 font-bold">CANTIDAD:</label>
                                        <input type="number" name="products[{{ $p->id }}]" min="0" value="0" class="w-20 rounded bg-surface-container-highest border border-white/10 text-white text-sm p-1 px-2 text-center focus:outline-none focus:border-primary font-bold">
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
                <div class="mt-6 flex justify-end gap-3 border-t border-white/5 pt-4">
                    <button type="submit" class="py-3 px-6 rounded-xl bg-primary text-on-primary font-bold shadow-lg shadow-primary/20">Agregar al Pedido</button>
                    <button type="button" onclick="closeModals()" class="py-3 px-6 rounded-xl border border-white/10">Cancelar</button>
                </div>
            </form>
        </div>

        @stack('modals')

    </div>

    <!-- Modal flotante independiente para Configurar Bebidas del Petaco (Abre ENCIMA de todo en móvil y desktop) -->
    <div id="modal-config-petaco-menu" class="fixed inset-0 bg-black/80 backdrop-blur-md z-[150] hidden items-center justify-center p-3 sm:p-6 opacity-0 transition-opacity duration-200">
        <div class="bg-surface-container-low border border-white/10 rounded-2xl w-full max-w-lg max-h-[90vh] flex flex-col shadow-2xl transform scale-95 transition-transform duration-200">
            <!-- Modal Header -->
            <div class="flex justify-between items-start p-4 sm:p-6 border-b border-white/5 bg-surface-container">
                <div class="pr-2">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-amber-400 text-2xl">liquor</span>
                        <h3 id="cfg-modal-petaco-title" class="text-lg sm:text-xl font-black text-white">Configurar Bebidas</h3>
                    </div>
                    <p class="text-xs text-slate-300 mt-1">
                        Precio fijo del Petaco: <span id="cfg-modal-petaco-price" class="text-primary font-bold"></span> 
                        <span class="text-slate-400 block text-[11px] mt-0.5">(Las bebidas seleccionadas solo se descuentan del inventario)</span>
                    </p>
                </div>
                <button type="button" onclick="closePetacoConfigModal()" class="text-outline hover:text-white transition-colors p-1.5 rounded-lg bg-white/5 hover:bg-white/10 flex-shrink-0">
                    <span class="material-symbols-outlined text-xl">close</span>
                </button>
            </div>

            <!-- Modal Body / Drink List -->
            <div class="p-4 sm:p-6 overflow-y-auto flex-1 space-y-3 min-h-[260px] pb-36" id="cfg-modal-drinks-list">
                <!-- Dynamic drink rows -->
            </div>

            <!-- Modal Footer -->
            <div class="p-4 sm:p-6 border-t border-white/5 bg-surface-container/50 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
                <button type="button" onclick="addCfgModalDrinkRow()" class="w-full sm:w-auto text-xs text-primary font-bold flex items-center justify-center gap-1.5 bg-primary/10 hover:bg-primary/20 border border-primary/20 px-3.5 py-2.5 rounded-xl transition-all">
                    <span class="material-symbols-outlined text-sm">add_circle</span> Añadir bebida
                </button>
                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <button type="button" onclick="closePetacoConfigModal()" class="flex-1 sm:flex-none py-2.5 px-4 rounded-xl border border-white/10 text-xs font-bold text-slate-300 hover:bg-white/5 transition-all text-center">
                        Cancelar
                    </button>
                    <button type="button" onclick="savePetacoConfigModal()" class="flex-1 sm:flex-none py-2.5 px-5 rounded-xl bg-primary text-on-primary font-bold text-xs shadow-lg shadow-primary/20 hover:scale-95 transition-all text-center">
                        Guardar Selección
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        const modalOverlay = document.getElementById('modal-overlay');

        // Sidebar Responsive Logic
        function toggleSidebar() {
            document.body.classList.toggle('sidebar-collapsed');
            localStorage.setItem('sidebar-collapsed', document.body.classList.contains('sidebar-collapsed'));
        }

        function toggleMobileMenu() {
            document.body.classList.toggle('mobile-open');
        }

        // Restore sidebar state from local storage on load (prevent flicker ideally done in head but this works for demo)
        document.addEventListener('DOMContentLoaded', () => {
            if (localStorage.getItem('sidebar-collapsed') === 'true' && window.innerWidth > 768) {
                document.body.classList.add('sidebar-collapsed');
            }
        });

        // expose toggles on window for inline onclick handlers
        try { window.toggleSidebar = toggleSidebar; window.toggleMobileMenu = toggleMobileMenu; } catch(e){}

        window.openModal = function(modalId) {
            const target = document.getElementById(modalId);
            if (!target) {
                console.warn('Modal no encontrado:', modalId);
                return;
            }

            // Cierra todos primero
            document.querySelectorAll('.modal-content').forEach(m => {
                m.classList.add('hidden');
                m.classList.remove('scale-100');
                m.classList.add('scale-95');
            });
            
            // Abre el overlay y el modal elegido
            modalOverlay.classList.remove('hidden');
            modalOverlay.classList.add('flex');
            
            // Animación: Request animation frame asegura la transición visual
            requestAnimationFrame(() => {
                modalOverlay.classList.remove('opacity-0');
                modalOverlay.classList.add('opacity-100');
                // If opening product menu modal, configure form action dynamically
                if (modalId === 'modal-add-product-order') {
                    const form = document.getElementById('add-products-form');
                    const parts = window.location.pathname.split('/');
                    const idx = parts.indexOf('mesas');
                    const mesaId = (idx !== -1 && parts[idx + 1]) ? parts[idx + 1] : (sessionStorage.getItem('selected_mesa_id') || '0');
                    if (form) {
                        form.action = '/admin/mesas/' + mesaId + '/pedido/add';
                    }
                }
                target.classList.remove('hidden');
                setTimeout(() => {
                    target.classList.remove('scale-95');
                    target.classList.add('scale-100');
                }, 10);
            });
        }

        // Live search filter for Blade-rendered products inside the modal
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('productos-search-input');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const query = this.value.toLowerCase().trim();
                    document.querySelectorAll('#productos-grid .product-card').forEach(card => {
                        const name = card.getAttribute('data-name') || '';
                        if (name.includes(query)) {
                            card.style.display = 'flex';
                        } else {
                            card.style.display = 'none';
                        }
                    });
                });
            }
        });

        // Petaco Dynamic Beverages Handler (Modal-based)
        const availableBeverages = @json($beverageProducts->map(fn($b) => ['id' => $b->id, 'nombre' => $b->nombre])->values());
        let currentConfigPetacoId = null;
        const petacoConfigState = {}; // { [productId]: [ { producto_id, cantidad } ] }

        window.openPetacoConfigModal = function(productId, productName, productPrice) {
            currentConfigPetacoId = productId;
            document.getElementById('cfg-modal-petaco-title').textContent = 'Bebidas: ' + productName;
            document.getElementById('cfg-modal-petaco-price').textContent = '$' + productPrice;
            
            const container = document.getElementById('cfg-modal-drinks-list');
            container.innerHTML = '';
            
            const currentItems = petacoConfigState[productId] || [];
            if (currentItems.length > 0) {
                currentItems.forEach(item => {
                    addCfgModalDrinkRow(item.producto_id, item.cantidad);
                });
            } else {
                addCfgModalDrinkRow();
            }
            
            const configModal = document.getElementById('modal-config-petaco-menu');
            configModal.classList.remove('hidden');
            configModal.classList.add('flex');
            requestAnimationFrame(() => {
                configModal.classList.remove('opacity-0');
                configModal.classList.add('opacity-100');
                const inner = configModal.firstElementChild;
                if (inner) {
                    inner.classList.remove('scale-95');
                    inner.classList.add('scale-100');
                }
            });
        };

        window.closePetacoConfigModal = function() {
            const configModal = document.getElementById('modal-config-petaco-menu');
            if (!configModal) return;
            configModal.classList.remove('opacity-100');
            configModal.classList.add('opacity-0');
            const inner = configModal.firstElementChild;
            if (inner) {
                inner.classList.remove('scale-100');
                inner.classList.add('scale-95');
            }
            setTimeout(() => {
                configModal.classList.add('hidden');
                configModal.classList.remove('flex');
            }, 200);
        };

        // Searchable Dropdown Helper for Drinks
        window.renderSearchableDrinkSelectHtml = function(selectedId = null, inputName = '', inputClass = 'searchable-hidden-id') {
            const selectedProd = availableBeverages.find(b => String(b.id) === String(selectedId));
            const initialName = selectedProd ? selectedProd.nombre : '';
            const initialId = selectedProd ? selectedProd.id : '';

            return `
                <div class="relative flex-1 min-w-0 searchable-drink-box">
                    <input type="hidden" class="${inputClass}" ${inputName ? `name="${inputName}"` : ''} value="${initialId}">
                    <div class="relative flex items-center">
                        <input type="text" class="searchable-drink-input w-full bg-surface-container-low border border-white/10 rounded-xl text-xs sm:text-sm text-white pl-3 pr-8 py-2.5 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all placeholder:text-slate-500" value="${escapeHtml(initialName)}" placeholder="Escribe para buscar bebida..." autocomplete="off" onfocus="handleSearchableDrinkFocus(this)" oninput="handleSearchableDrinkInput(this)">
                        <span class="material-symbols-outlined text-slate-400 absolute right-2.5 pointer-events-none text-base">search</span>
                    </div>
                    <div class="searchable-drink-options hidden absolute top-full left-0 right-0 mt-1 bg-surface-container-high border border-white/20 rounded-xl shadow-[0_20px_50px_rgba(0,0,0,0.95)] max-h-52 overflow-y-auto p-1.5 space-y-1 z-[999] backdrop-blur-2xl">
                    </div>
                </div>
            `;
        };

        window.handleSearchableDrinkFocus = function(inputEl) {
            window.handleSearchableDrinkInput(inputEl);
        };

        window.handleSearchableDrinkInput = function(inputEl) {
            const box = inputEl.closest('.searchable-drink-box');
            if (!box) return;
            const row = inputEl.closest('.cfg-drink-row') || inputEl.closest('.edit-drink-row');
            const optionsContainer = box.querySelector('.searchable-drink-options');
            if (!optionsContainer) return;
            
            // Close other open searchable dropdowns
            document.querySelectorAll('.searchable-drink-options').forEach(opt => {
                if (opt !== optionsContainer) opt.classList.add('hidden');
            });
            document.querySelectorAll('.cfg-drink-row, .edit-drink-row').forEach(r => {
                r.classList.remove('z-30');
            });

            if (row) row.classList.add('z-30');

            const query = inputEl.value.toLowerCase().trim();
            const filtered = availableBeverages.filter(b => b.nombre.toLowerCase().includes(query));

            if (filtered.length === 0) {
                optionsContainer.innerHTML = '<p class="text-xs text-slate-400 p-2.5 text-center">No se encontraron bebidas</p>';
            } else {
                optionsContainer.innerHTML = filtered.map(b => `
                    <button type="button" onclick="selectSearchableDrinkOption(this, ${b.id}, '${escapeHtml(b.nombre)}')" class="w-full text-left px-3 py-2.5 rounded-lg text-xs sm:text-sm text-slate-200 hover:text-white hover:bg-primary/20 hover:border hover:border-primary/30 flex items-center justify-between transition-colors">
                        <span class="font-medium">${escapeHtml(b.nombre)}</span>
                    </button>
                `).join('');
            }

            optionsContainer.classList.remove('hidden');
        };

        window.selectSearchableDrinkOption = function(btnEl, prodId, prodName) {
            const box = btnEl.closest('.searchable-drink-box');
            if (!box) return;
            const row = btnEl.closest('.cfg-drink-row') || btnEl.closest('.edit-drink-row');
            const hiddenInput = box.querySelector('.searchable-hidden-id') || box.querySelector('input[type="hidden"]');
            const textInput = box.querySelector('.searchable-drink-input');
            const optionsContainer = box.querySelector('.searchable-drink-options');
            
            if (hiddenInput) hiddenInput.value = prodId;
            if (textInput) textInput.value = prodName;
            if (optionsContainer) optionsContainer.classList.add('hidden');
            if (row) row.classList.remove('z-30');
        };

        // Global click outside to close searchable dropdowns
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.searchable-drink-box')) {
                document.querySelectorAll('.searchable-drink-options').forEach(opt => opt.classList.add('hidden'));
                document.querySelectorAll('.cfg-drink-row, .edit-drink-row').forEach(r => r.classList.remove('z-30'));
            }
        });

        window.addCfgModalDrinkRow = function(selectedId = null, qty = 1) {
            const container = document.getElementById('cfg-modal-drinks-list');
            if (!container) return;
            
            const row = document.createElement('div');
            row.className = 'flex items-center gap-2 cfg-drink-row bg-surface-container-highest/60 p-2.5 sm:p-3 rounded-xl border border-white/5 relative';
            row.innerHTML = `
                ${window.renderSearchableDrinkSelectHtml(selectedId, '', 'cfg-drink-id')}
                <div class="w-24 sm:w-28 flex-shrink-0 flex items-center gap-1">
                    <span class="text-[11px] text-slate-400 font-bold">Cant:</span>
                    <input type="number" min="1" value="${qty}" class="cfg-drink-qty w-full bg-surface-container-low border border-white/10 rounded-xl text-xs sm:text-sm text-white p-2 text-center focus:outline-none focus:border-primary font-bold">
                </div>
                <button type="button" onclick="this.closest('.cfg-drink-row').remove()" class="text-error hover:text-error-container p-2 rounded-xl bg-error/10 hover:bg-error/20 transition-colors flex-shrink-0" title="Eliminar bebida">
                    <span class="material-symbols-outlined text-lg">delete</span>
                </button>
            `;
            container.appendChild(row);
        };

        window.savePetacoConfigModal = function() {
            if (!currentConfigPetacoId) return;
            const pId = currentConfigPetacoId;
            const rows = document.querySelectorAll('#cfg-modal-drinks-list .cfg-drink-row');
            const items = [];
            
            rows.forEach(r => {
                const idInput = r.querySelector('.cfg-drink-id');
                const qtyInput = r.querySelector('.cfg-drink-qty');
                if (idInput && qtyInput) {
                    const prodId = idInput.value;
                    const cant = parseInt(qtyInput.value) || 1;
                    if (prodId && cant > 0) {
                        items.push({ producto_id: prodId, cantidad: cant });
                    }
                }
            });
            
            petacoConfigState[pId] = items;
            
            // Inject hidden inputs into form
            const hiddenContainer = document.getElementById(`petaco-hidden-inputs-${pId}`);
            if (hiddenContainer) {
                hiddenContainer.innerHTML = '';
                items.forEach((item, idx) => {
                    hiddenContainer.innerHTML += `
                        <input type="hidden" name="petaco_components[${pId}][${idx}][producto_id]" value="${item.producto_id}">
                        <input type="hidden" name="petaco_components[${pId}][${idx}][cantidad]" value="${item.cantidad}">
                    `;
                });
            }
            
            // Auto-set petaco quantity to 1 if it was 0
            const qtyInput = document.getElementById(`petaco-qty-${pId}`);
            if (qtyInput && parseInt(qtyInput.value || 0) <= 0) {
                qtyInput.value = 1;
            }
            
            // Update badge count
            const badge = document.getElementById(`petaco-badge-count-${pId}`);
            if (badge) {
                const totalBebidas = items.reduce((sum, it) => sum + it.cantidad, 0);
                if (totalBebidas > 0) {
                    badge.textContent = totalBebidas;
                    badge.classList.remove('hidden');
                } else {
                    badge.classList.add('hidden');
                }
            }
            
            closePetacoConfigModal();
        };

        // API-related functions removed. Carga directa por base de datos en Blade.

        function escapeHtml(s){ return String(s||'').replaceAll('&','&amp;').replaceAll('<','&lt;').replaceAll('>','&gt;').replaceAll('"','&quot;'); }

        window.closeModals = function closeModals() {
            modalOverlay.classList.remove('opacity-100');
            modalOverlay.classList.add('opacity-0');
            document.querySelectorAll('.modal-content').forEach(m => {
                m.classList.remove('scale-100');
                m.classList.add('scale-95');
            });

            setTimeout(() => {
                modalOverlay.classList.add('hidden');
                modalOverlay.classList.remove('flex');
                document.querySelectorAll('.modal-content').forEach(m => m.classList.add('hidden'));
            }, 300); // Wait for transition
        }

        // Close when clicking outside content
        modalOverlay.addEventListener('click', (e) => {
            if (e.target === modalOverlay) closeModals();
        });

        const configModalEl = document.getElementById('modal-config-petaco-menu');
        if (configModalEl) {
            configModalEl.addEventListener('click', (e) => {
                if (e.target === configModalEl) closePetacoConfigModal();
            });
        }

        // Play a nice retro double chime notification using Web Audio API
        let audioCtxInstance = null;
        window.playNotificationSound = function() {
            try {
                const AudioContext = window.AudioContext || window.webkitAudioContext;
                if (!AudioContext) return;
                if (!audioCtxInstance) {
                    audioCtxInstance = new AudioContext();
                }
                if (audioCtxInstance.state === 'suspended') {
                    audioCtxInstance.resume();
                }
                
                function playTone(freq, startTime, duration) {
                    const osc = audioCtxInstance.createOscillator();
                    const gainNode = audioCtxInstance.createGain();
                    
                    osc.connect(gainNode);
                    gainNode.connect(audioCtxInstance.destination);
                    
                    osc.type = 'sine';
                    osc.frequency.setValueAtTime(freq, startTime);
                    
                    gainNode.gain.setValueAtTime(0.15, startTime);
                    gainNode.gain.exponentialRampToValueAtTime(0.0001, startTime + duration);
                    
                    osc.start(startTime);
                    osc.stop(startTime + duration);
                }
                
                const now = audioCtxInstance.currentTime;
                playTone(587.33, now, 0.25); // D5
                playTone(880.00, now + 0.12, 0.35); // A5
            } catch (e) {
                console.warn('AudioContext failed:', e);
            }
        };

        // Global Loading Screen Functions
        window.showLoader = function(text = 'Procesando...') {
            const loader = document.getElementById('global-loader');
            const loaderText = document.getElementById('global-loader-text');
            if (loader) {
                if (loaderText) loaderText.textContent = text;
                loader.classList.remove('opacity-0', 'pointer-events-none');
                loader.classList.add('opacity-100');
            }
        };

        window.hideLoader = function() {
            const loader = document.getElementById('global-loader');
            if (loader) {
                loader.classList.remove('opacity-100');
                loader.classList.add('opacity-0', 'pointer-events-none');
            }
        };

        window.showLoading = window.showLoader;
        window.hideLoading = window.hideLoader;

        // Auto-interceptor for Standard Non-GET Form Submissions
        document.addEventListener('submit', function(e) {
            const form = e.target;
            if (form && form.tagName === 'FORM') {
                const method = (form.getAttribute('method') || 'GET').toUpperCase();
                if (method === 'GET' || form.dataset.noLoader === 'true') {
                    return;
                }
                
                let msg = 'Procesando...';
                const activeBtn = document.activeElement;
                if (activeBtn && activeBtn.form === form) {
                    const btnText = activeBtn.textContent.trim().toLowerCase();
                    if (btnText.includes('eliminar') || btnText.includes('borrar')) {
                        msg = 'Eliminando...';
                    } else if (btnText.includes('guardar') || btnText.includes('crear') || btnText.includes('registrar')) {
                        msg = 'Guardando cambios...';
                    } else if (btnText.includes('actualizar')) {
                        msg = 'Actualizando...';
                    } else if (btnText.includes('cobrar') || btnText.includes('pagar')) {
                        msg = 'Procesando pago...';
                    }
                } else {
                    const action = (form.getAttribute('action') || '').toLowerCase();
                    if (action.includes('delete') || action.includes('destroy')) {
                        msg = 'Eliminando...';
                    } else if (action.includes('store') || action.includes('create')) {
                        msg = 'Guardando...';
                    } else if (action.includes('update') || action.includes('edit')) {
                        msg = 'Actualizando...';
                    }
                }
                
                window.showLoader(msg);
            }
        });

        // Auto-interceptor for AJAX/Fetch modification requests (POST/PUT/DELETE)
        const originalFetch = window.fetch;
        window.fetch = async function(...args) {
            let url = '';
            let method = 'GET';
            let options = {};
            
            if (args[0] instanceof Request) {
                url = args[0].url;
                method = args[0].method || 'GET';
            } else {
                url = args[0];
                options = args[1] || {};
                method = options.method || 'GET';
            }
            method = method.toUpperCase();
            
            // Skip background/silent operations or specific UI update polls
            const isBg = url.includes('partial=1') || url.includes('/caja/preload') || url.includes('/productos/json') || options.noLoader;
            const isModification = ['POST', 'PUT', 'DELETE', 'PATCH'].includes(method) && !isBg;
            
            if (isModification) {
                let msg = 'Procesando...';
                if (method === 'DELETE') {
                    msg = 'Eliminando...';
                } else if (method === 'POST') {
                    msg = 'Guardando...';
                } else if (method === 'PUT' || method === 'PATCH') {
                    msg = 'Actualizando...';
                }
                window.showLoader(msg);
            }
            
            try {
                return await originalFetch(...args);
            } finally {
                if (isModification) {
                    window.hideLoader();
                }
            }
        };
    </script>
    <!-- Removed redundant client-side API/roles scripts -->
    @stack('scripts')

    <!-- Global Loading Screen Overlay -->
    <div id="global-loader" class="fixed inset-0 z-[99999] flex flex-col items-center justify-center bg-black/80 backdrop-blur-md opacity-0 pointer-events-none transition-all duration-300">
        <div class="flex flex-col items-center gap-6 p-8 rounded-3xl bg-[#0f0f0f]/95 border border-white/10 shadow-2xl relative overflow-hidden group max-w-xs w-full text-center">
            <!-- Background glow -->
            <div class="absolute -inset-10 bg-primary/5 rounded-full blur-2xl group-hover:bg-primary/10 transition-all duration-500"></div>
            
            <div class="relative">
                <!-- Dual spin rings -->
                <div class="w-16 h-16 border-4 border-primary/20 border-t-primary rounded-full animate-spin"></div>
                <div class="absolute inset-1 w-14 h-14 border-4 border-transparent border-t-[#8a6c1c] rounded-full animate-spin [animation-duration:0.8s] [animation-direction:reverse]"></div>
                <!-- Brand logo center or small dot -->
                <div class="absolute inset-0 m-auto w-3 h-3 bg-primary rounded-full animate-ping"></div>
            </div>
            
            <div class="space-y-1 relative z-10">
                <p id="global-loader-text" class="text-base font-black text-white tracking-tight">Procesando...</p>
                <p class="text-[10px] uppercase font-bold text-primary tracking-widest opacity-80">Esencia Retro</p>
            </div>
        </div>
    </div>
</body>
</html>
