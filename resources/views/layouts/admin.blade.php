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
        @endphp
        <div id="modal-new-order" class="modal-content hidden bg-surface-container-low border border-white/10 p-8 rounded-3xl w-full max-w-md shadow-2xl transform scale-95 transition-transform duration-300">
            <h3 class="text-2xl font-black text-white mb-2">Nueva Orden</h3>
            <p class="text-sm text-on-surface-variant mb-6">Selecciona una mesa libre para abrir una nueva orden rápida.</p>
            
            <div class="space-y-3 mb-6 max-h-64 overflow-y-auto pr-2" id="mesas-list-container">
                @foreach($freeMesas as $m)
                    <a href="{{ route('admin.pedido', ['id' => $m->id]) }}" class="flex items-center justify-between p-4 rounded-xl border border-white/5 transition-all bg-surface hover:bg-surface-container-highest hover:border-primary/50 group">
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
                <div id="productos-grid" class="grid gap-6 max-h-[60vh] overflow-y-auto pr-4" style="grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));">
                    @foreach($allProducts as $p)
                        <div class="product-card group relative overflow-hidden rounded-xl bg-surface-container-low p-5 md:p-6 transition-all hover:bg-surface-container-high cursor-pointer flex flex-col md:flex-row items-start gap-4" data-name="{{ strtolower($p->nombre) }}">
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
                                    <select name="products[{{ $p->id }}]" class="rounded bg-surface-container-highest border border-white/10 text-white text-sm p-1">
                                        <option value="0" selected>0</option>
                                        @for($i=1; $i<=20; $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>
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
                const target = document.getElementById(modalId);
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
    </script>
    <!-- Removed redundant client-side API/roles scripts -->
    @stack('scripts')
</body>
</html>
