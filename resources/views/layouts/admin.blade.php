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
        // expose backend API URL to client scripts
        try { window.VITE_API_URL = "{{ env('VITE_API_URL') }}"; } catch(e) { window.VITE_API_URL = window.VITE_API_URL || null; }
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
        <div id="modal-new-order" class="modal-content hidden bg-surface-container-low border border-white/10 p-8 rounded-3xl w-full max-w-md shadow-2xl transform scale-95 transition-transform duration-300">
            <h3 class="text-2xl font-black text-white mb-2">Nueva Orden</h3>
            <p class="text-sm text-on-surface-variant mb-6">Selecciona una mesa libre para abrir una nueva orden rápida.</p>
            
            <div class="space-y-3 mb-6 max-h-64 overflow-y-auto pr-2" id="mesas-list-container">
                <!-- mesas listas se inyectan dinámicamente -->
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
                    <p class="font-bold text-white text-lg">System Operator</p>
                    <p class="text-[10px] uppercase font-bold text-primary tracking-widest">Administrador</p>
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
            <a href="#" data-logout class="w-full py-3 rounded-xl bg-error/10 text-error hover:bg-error hover:text-on-error transition-colors flex items-center justify-center gap-2 font-bold text-sm">
                <span class="material-symbols-outlined text-sm">logout</span>
                Cerrar Sesión
            </a>
        </div>
        
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
            <div id="productos-filters" class="flex gap-4 mb-6 overflow-x-auto pb-2 scrollbar-hidden">
                <!-- filtros se inyectan dinámicamente -->
            </div>

            <div id="productos-grid" class="grid gap-6 max-h-[65vh] md:max-h-[75vh] overflow-y-auto pr-4" style="grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));">
                <!-- productos inyectados por JS -->
            </div>
            <div class="mt-4 flex justify-end gap-3">
                <button id="crear-pedido-from-menu" class="py-3 px-6 rounded-xl bg-primary text-on-primary font-bold">Crear Pedido</button>
                <button onclick="closeModals()" class="py-3 px-6 rounded-xl border border-white/10">Cancelar</button>
            </div>
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
                // If opening new-order modal, load free mesas from API
                if (modalId === 'modal-new-order') {
                    loadMesasLibres();
                }
                // If opening product menu modal, load products
                if (modalId === 'modal-add-product-order') {
                    loadProductos();
                }
                target.classList.remove('hidden');
                setTimeout(() => {
                    target.classList.remove('scale-95');
                    target.classList.add('scale-100');
                }, 10);
            });
        }

        // Load free mesas from API and render into modal-new-order list
        window.loadMesasLibres = async function loadMesasLibres(){
            const container = document.querySelector('#modal-new-order .space-y-3');
            if (!container) return;
            container.innerHTML = '<div class="text-sm text-slate-400">Cargando mesas libres...</div>';
            const token = localStorage.getItem('auth_token');
            const headers = { 'Accept': 'application/json' };
            // attach auth token to the common headers so candidate fetches use the same credentials
            try{
                const token = localStorage.getItem('auth_token');
                if (token && String(token).trim().length) {
                    headers['Authorization'] = 'Bearer ' + token;
                }
            }catch(e){ /* ignore */ }
            if (token) headers['Authorization'] = 'Bearer ' + token;
            // debug: indicate presence/length of token but avoid printing full token
            try { console.debug('loadProductos auth token present:', Boolean(token), token ? ('len:' + token.length) : null); } catch(e){}

            // Build candidate URLs to avoid double '/api' mistakes
            const candidates = [];
            // If a VITE_API_URL is configured, prefer it first (try remote backend before local relative)
            try {
                const base = (window.VITE_API_URL || '').toString().trim();
                if (base) {
                    const b = base.replace(/\/+$/, '');
                    if (b.indexOf('/mesas') !== -1) candidates.unshift(b);
                    else if (b.endsWith('/api')) candidates.unshift(b + '/mesas');
                    else candidates.unshift(b + '/api/mesas');
                }
            } catch(e){}
            // then try relative (works when app and API share same origin)
            candidates.push('/api/mesas');

            // If running a Vite dev server (common port 5173), try the Laravel dev server on port 8000
            try{
                const host = window.location.hostname;
                const port = window.location.port;
                if ((host === '127.0.0.1' || host === 'localhost') && port && port !== '8000'){
                    const alt = 'http://' + host + ':8000/api/mesas';
                    if (candidates.indexOf(alt) === -1) candidates.push(alt);
                }
                // also try replacing port portion if present
                if (window.location.origin && window.location.origin.indexOf(':') !== -1){
                    const maybe = window.location.origin.replace(/:\d+$/, ':8000') + '/api/mesas';
                    if (candidates.indexOf(maybe) === -1) candidates.push(maybe);
                }
            }catch(e){}

            // also try window.API_BASE if present
            try{
                const alt = (window.API_BASE || '').toString().trim().replace(/\/+$/, '');
                if (alt && candidates.indexOf(alt) === -1) {
                    if (alt.indexOf('/mesas') !== -1) candidates.push(alt);
                    else if (alt.endsWith('/api')) candidates.push(alt + '/mesas');
                    else candidates.push(alt + '/api/mesas');
                }
            }catch(e){}

            let lastErr = null;
            for (const url of candidates) {
                try{
                    console.debug('Trying mesas URL', url);
                    // Only send credentials for same-origin requests to avoid CORS + credentials issues
                    const opts = { headers };
                    try{ const u = new URL(url, window.location.href); if (u.origin === window.location.origin) { opts.credentials = 'include'; } else { opts.mode = 'cors'; } } catch(e){ opts.mode = 'cors'; }
                    const res = await fetch(url, opts);
                    if (!res.ok) {
                        // attempt to capture response body for debugging
                        let text = '';
                        try{ text = await res.text(); } catch(e) { text = '<no body>'; }
                        lastErr = new Error('Status ' + res.status + ' for ' + url + ' body: ' + text);
                        console.debug('mesas fetch not ok', res.status, url, text);
                        continue;
                    }
                    const data = await res.json();
                    console.debug('mesas fetched', url, data && data.length ? data.length : (data? 'object' : 'empty'));
                    renderMesasList(container, data);
                    return;
                }catch(e){ lastErr = e; console.debug('mesas fetch error', e); continue; }
            }
            console.debug('loadMesasLibres error', lastErr);
            container.innerHTML = '<div class="text-sm text-red-400">Error cargando mesas</div>';
        }

        // Load products for the menu modal
        window.loadProductos = async function loadProductos(){
            const grid = document.getElementById('productos-grid');
            if (!grid) return;
            grid.innerHTML = '<div class="col-span-2 text-sm text-slate-400">Cargando productos...</div>';
            const headers = { 'Accept': 'application/json' };
            // Prefer the exact backend you tested to avoid mismatched origins or double /api
            let data = null;
            const candidates = [];
            try {
                // Hardcode the tested Railway backend as first preference
                const tested = 'https://esencia-retrobackend-testing.up.railway.app/api/productos';
                // First try a direct fetch exactly like the manual test, to reproduce the working request
                try{
                    const tkn = localStorage.getItem('auth_token');
                    const dbgHeaders = { 'Accept': 'application/json' };
                    if (tkn) dbgHeaders['Authorization'] = 'Bearer ' + tkn;
                    console.debug('Direct test fetch to', tested, 'headers present', Boolean(tkn));
                    const rTest = await fetch(tested, { headers: dbgHeaders, mode: 'cors' });
                    console.debug('Direct test fetch status', rTest.status);
                    const text = await rTest.text();
                    console.debug('Direct test fetch body', text);
                    if (rTest.ok) {
                        try{ const parsed = JSON.parse(text); data = parsed; } catch(e){ data = null; }
                        // If the direct test succeeded, render immediately and skip the candidate loop
                        if (data && Array.isArray(data)){
                            try{ window._productos_cache = data; renderProductosFilters(); renderProductosGrid(); return; } catch(e){ /* fallthrough if render fails */ }
                        }
                    }
                }catch(e){ console.debug('direct test fetch err', e); }
                candidates.push(tested);
                const baseRaw = (window.VITE_API_URL || '').toString().trim();
                if (baseRaw) {
                    const b = baseRaw.replace(/\/+$/, '');
                    if (b.match(/\/productos$|\/products$/)) {
                        candidates.push(b);
                    } else if (b.endsWith('/api')) {
                        candidates.push(b + '/productos');
                        candidates.push(b + '/products');
                    } else {
                        candidates.push(b + '/api/productos');
                        candidates.push(b + '/api/products');
                    }
                }
            } catch(e){}
            // fallback relative endpoints
            candidates.push('/api/productos');
            candidates.push('/api/products');

            let lastErr = null;
            let unauthorized = false;
            // First pass: try with Authorization header if present
            for (const url of candidates){
                try{
                    console.debug('Trying products URL', url);
                    const opts = { headers };
                    try{
                        const masked = headers['Authorization'] ? ('Bearer ' + String(headers['Authorization']).slice(-8).padStart(10,'*')) : null;
                        console.debug('fetch opts', { url, headers: { Accept: headers['Accept'], Authorization: masked } });
                    }catch(e){}
                    try{ const u = new URL(url, window.location.href); if (u.origin === window.location.origin) { opts.credentials = 'include'; } else { opts.mode = 'cors'; } } catch(e){ opts.mode = 'cors'; }
                    const res = await fetch(url, opts);
                    if (res.status === 401) { unauthorized = true; lastErr = new Error('Unauthorized ' + url); console.debug('products fetch unauthorized', url); continue; }
                    if (!res.ok) { lastErr = new Error('Status ' + res.status + ' ' + url); console.debug('products fetch not ok', res.status, url); continue; }
                    data = await res.json(); break;
                }catch(e){ lastErr = e; console.debug('products fetch error', e); continue; }
            }
            // If unauthorized and we had a token, try again WITHOUT Authorization header (public fallback)
            if (!data && unauthorized && (localStorage.getItem('auth_token'))){
                console.debug('Unauthorized with token — retrying products without Authorization header');
                const headersNoAuth = { 'Accept': 'application/json' };
                for (const url of candidates){
                    try{
                        const opts = { headers: headersNoAuth };
                        try{ const u = new URL(url, window.location.href); if (u.origin === window.location.origin) { opts.credentials = 'include'; } else { opts.mode = 'cors'; } } catch(e){ opts.mode = 'cors'; }
                        const res = await fetch(url, opts);
                        if (!res.ok) { lastErr = new Error('Status ' + res.status + ' ' + url); console.debug('products fetch not ok (no auth)', res.status, url); continue; }
                        data = await res.json(); break;
                    }catch(e){ lastErr = e; console.debug('products fetch error (no auth)', e); continue; }
                }
            }

            if (!data || !Array.isArray(data)) {
                console.debug('loadProductos final err', lastErr);
                // If unauthorized detected, show login CTA
                if (unauthorized) {
                    grid.innerHTML = `
                        <div class="col-span-2 text-sm text-red-400">No autorizado. Debes iniciar sesión para ver los productos.</div>
                        <div class="col-span-2 mt-4">
                            <button id="productos-login-cta" class="py-2 px-4 rounded-xl bg-primary text-on-primary font-bold">Ir a Login</button>
                        </div>
                    `;
                    setTimeout(()=>{
                        const cta = document.getElementById('productos-login-cta');
                        if (cta) cta.addEventListener('click', ()=>{ window.location.href = '/login'; });
                    }, 30);
                } else {
                    grid.innerHTML = '<div class="col-span-2 text-sm text-slate-400">No hay productos</div>';
                }
                return;
            }
            // Cache products and render filters + grid (search + filter applied)
            try{ window._productos_cache = Array.isArray(data) ? data : []; } catch(e){ window._productos_cache = data || []; }
            renderProductosFilters();
            renderProductosGrid();
        }

        // Render filter buttons based on product categories (dynamic)
        function renderProductosFilters(){
            try{
                const container = document.getElementById('productos-filters');
                if (!container) return;
                const products = Array.isArray(window._productos_cache) ? window._productos_cache : [];
                // detect category field candidates
                const catCandidates = ['category','categoria','tipo','tipo_producto','categoria_id','group'];
                const cats = new Set();
                products.forEach(p => {
                    for (const k of catCandidates){
                        if (p && p[k]){ cats.add(String(p[k]||'').trim()); break; }
                    }
                    // fallback: if product has 'tags' array
                    if (!p) return;
                    if (Array.isArray(p.tags) && p.tags.length){ p.tags.forEach(t => cats.add(String(t).trim())); }
                });
                // Normalise and remove empty
                const finalCats = Array.from(cats).map(c=>c).filter(Boolean);
                // Always include 'Todas' first
                container.innerHTML = '';
                const allBtn = document.createElement('button');
                allBtn.className = 'px-4 py-1.5 rounded-full text-xs font-bold whitespace-nowrap';
                allBtn.dataset.filter = '';
                allBtn.textContent = 'Todas';
                container.appendChild(allBtn);
                finalCats.forEach(cat => {
                    const b = document.createElement('button');
                    b.className = 'px-4 py-1.5 rounded-full text-xs font-bold whitespace-nowrap';
                    b.dataset.filter = cat;
                    b.textContent = cat;
                    container.appendChild(b);
                });
                // Apply styles and handlers
                Array.from(container.children).forEach((btn, idx)=>{
                    btn.classList.add('bg-surface-container-highest','text-on-surface','border','border-white/5');
                    if (idx===0) btn.classList.remove('bg-surface-container-highest','border','border-white/5');
                    if (idx===0) btn.classList.add('bg-primary','text-on-primary');
                    btn.addEventListener('click', function(){
                        Array.from(container.children).forEach(c=>{
                            c.classList.remove('bg-primary','text-on-primary');
                            c.classList.add('bg-surface-container-highest','text-on-surface','border','border-white/5');
                        });
                        this.classList.remove('bg-surface-container-highest','text-on-surface','border','border-white/5');
                        this.classList.add('bg-primary','text-on-primary');
                        renderProductosGrid();
                    });
                });
            }catch(e){ console.debug('renderProductosFilters err', e); }
        }

        // Render products into grid using active filter + search term
        function renderProductosGrid(){
            try{
                const grid = document.getElementById('productos-grid');
                const container = document.getElementById('productos-filters');
                const searchInput = document.getElementById('productos-search-input');
                if (!grid) return;
                const products = Array.isArray(window._productos_cache) ? window._productos_cache : [];
                let activeFilter = '';
                if (container){
                    const active = Array.from(container.children).find(c=>c.classList && c.classList.contains('bg-primary'));
                    if (active && active.dataset) activeFilter = active.dataset.filter || '';
                }
                const q = (searchInput && searchInput.value) ? String(searchInput.value).toLowerCase().trim() : '';
                // filter products
                const filtered = products.filter(p => {
                    // match category if activeFilter set
                    if (activeFilter){
                        const catKeys = ['category','categoria','tipo','tipo_producto','categoria_id','group'];
                        let matched = false;
                        for (const k of catKeys){ if (p && p[k] && String(p[k]).toLowerCase().indexOf(String(activeFilter).toLowerCase()) !== -1){ matched = true; break; } }
                        if (!matched) return false;
                    }
                    if (!q) return true;
                    // match name/description
                    const name = String(p.name||p.nombre||p.desc||p.descripcion||'').toLowerCase();
                    if (name.indexOf(q) !== -1) return true;
                    const code = String(p.code||p.sku||p.id||'').toLowerCase();
                    if (code.indexOf(q) !== -1) return true;
                    return false;
                });
                // render
                grid.innerHTML = '';
                if (!filtered.length){ grid.innerHTML = '<div class="col-span-2 text-sm text-slate-400">No hay productos</div>'; return; }
                filtered.forEach(p => {
                    const card = document.createElement('div');
                    card.className = 'group relative overflow-hidden rounded-xl bg-surface-container-low p-5 md:p-6 transition-all hover:bg-surface-container-high cursor-pointer flex flex-col md:flex-row items-start gap-4';
                    const price = (p.price || p.precio || p.valor || p.price_cop) || 0;
                    card.innerHTML = `
                        <div class="w-full md:w-28 h-36 md:h-28 rounded-lg overflow-hidden bg-surface-container-highest flex items-center justify-center flex-shrink-0">
                            <img src="${escapeHtml(p.image || p.img || p.foto || p.imagen_url || '')}" class="w-full h-full object-cover" onerror="this.style.display='none'" />
                        </div>
                        <div class="flex-1 min-w-0 flex flex-col justify-between relative">
                            <div>
                                <p class="font-bold text-white text-base md:text-lg whitespace-nowrap overflow-visible">${escapeHtml(p.name||p.nombre||p.desc||p.descripcion||'Producto')}</p>
                                <p class="text-sm md:text-base text-primary font-bold mt-1">${formatMoney(Number(price||0))}</p>
                            </div>
                            <div class="mt-3 md:mt-0 flex items-center justify-end relative">
                                <button class="add-product-btn material-symbols-outlined text-outline bg-white/5 rounded-full w-12 h-12 md:w-14 md:h-14 flex items-center justify-center">add_circle</button>
                                <div class="qty-panel hidden sm:absolute right-0 bottom-full mb-3 sm:w-40 w-full p-3 rounded-lg bg-surface-container-high border border-white/5 flex flex-col sm:flex-row items-center sm:justify-between gap-2">
                                    <div class="flex items-center gap-2 w-full sm:w-auto justify-between sm:justify-start">
                                        <button class="qty-decr px-3 py-1 rounded-full border border-white/5">-</button>
                                        <div class="qty-value font-bold px-2">1</div>
                                        <button class="qty-incr px-3 py-1 rounded-full border border-white/5">+</button>
                                    </div>
                                    <button class="qty-add w-full sm:w-auto px-2 py-1 rounded bg-primary text-on-primary mt-2 sm:mt-0">OK</button>
                                </div>
                            </div>
                        </div>
                    `;
                    const btn = card.querySelector('.add-product-btn');
                    // quantity panel handlers
                    const panel = card.querySelector('.qty-panel');
                    const valEl = card.querySelector('.qty-value');
                    const incr = card.querySelector('.qty-incr');
                    const decr = card.querySelector('.qty-decr');
                    const addConfirm = card.querySelector('.qty-add');
                    let qty = 1;
                    function showPanel(){ if(panel) { panel.classList.remove('hidden'); panel.classList.add('flex'); } }
                    function hidePanel(){ if(panel) { panel.classList.remove('flex'); panel.classList.add('hidden'); } qty = 1; if (valEl) valEl.textContent = String(qty); }
                    btn.addEventListener('click', function(e){
                        e.stopPropagation();
                        if (!panel) return;
                        if (panel.classList.contains('hidden')) showPanel(); else hidePanel();
                    });
                    if (incr) incr.addEventListener('click', function(e){ e.stopPropagation(); qty = Math.min(99, qty + 1); if (valEl) valEl.textContent = String(qty); });
                    if (decr) decr.addEventListener('click', function(e){ e.stopPropagation(); qty = Math.max(1, qty - 1); if (valEl) valEl.textContent = String(qty); });
                    if (addConfirm) addConfirm.addEventListener('click', function(e){
                        e.stopPropagation();
                        try{
                            const items = JSON.parse(sessionStorage.getItem('pending_order_items') || '[]');
                            items.push({ producto_id: p.id || p.producto_id || null, name: p.name||p.nombre||p.desc||p.descripcion, precio: Number(price||0), cantidad: Number(qty||1) });
                            sessionStorage.setItem('pending_order_items', JSON.stringify(items));
                            hidePanel();
                            try{ const f = document.createElement('div'); f.textContent = 'Añadido'; f.className = 'toast-temp fixed z-[9999] right-6 bottom-6 bg-primary text-on-primary px-4 py-2 rounded'; document.body.appendChild(f); setTimeout(()=>f.remove(),1200); }catch(e){}
                        }catch(e){ console.debug('add product err', e); }
                    });
                    grid.appendChild(card);
                });
            }catch(e){ console.debug('renderProductosGrid err', e); }
        }

        // wire search input to filter live
        try{
            const si = document.getElementById('productos-search-input');
            if (si) si.addEventListener('input', function(){ renderProductosGrid(); });
        }catch(e){}

        // ensure formatMoney exists
        if (typeof formatMoney !== 'function'){
            function formatMoney(n){ return '$' + Number(n||0).toLocaleString('es-CO', {minimumFractionDigits: 0}); }
        }

        function renderMesasList(container, mesas){
            console.debug('renderMesasList called', mesas);
            if (!Array.isArray(mesas)) { container.innerHTML = '<div class="text-sm text-slate-400">No hay mesas (respuesta inválida)</div>'; return; }
            container.innerHTML = '';
            mesas.forEach(m => {
                const status = (m.status || '').toString().toLowerCase();
                // detect if mesa has active orders
                let hasOrders = false;
                try{
                    if (Array.isArray(m.orders) && m.orders.length) hasOrders = true;
                    if (Array.isArray(m.pedidos) && m.pedidos.length) hasOrders = true;
                    if (Array.isArray(m.ordenes) && m.ordenes.length) hasOrders = true;
                    if (typeof m.order_count !== 'undefined' && Number(m.order_count) > 0) hasOrders = true;
                    if (m.order && typeof m.order === 'object' && Object.keys(m.order).length) hasOrders = true;
                }catch(e){ /* ignore */ }

                // occupancy flags
                const occupiedFlagExplicit = (typeof m.occupied !== 'undefined') ? Boolean(m.occupied) : null;
                const isStatusOccupied = (status === 'ocupada' || status === 'occupied' || status === 'ocupado');

                const isLibre = (()=>{
                    if (occupiedFlagExplicit !== null) return !occupiedFlagExplicit && !hasOrders;
                    if (isStatusOccupied) return false;
                    // consider libre if status explicitly indicates free or no orders present
                    if (['libre','available','free','vacant','disponible'].includes(status)) return true;
                    return !hasOrders;
                })();
                const a = document.createElement('a');
                a.className = 'flex items-center justify-between p-4 rounded-xl border border-white/5 transition-all group';
                if (isLibre) a.classList.add('bg-surface','hover:bg-surface-container-highest','hover:border-primary/50');
                else a.classList.add('bg-surface-container-high','opacity-60','cursor-not-allowed');
                // For libre mesas, open the product menu modal instead of navigating immediately
                a.setAttribute('href', '#');
                a.addEventListener('click', function(e){
                    e.preventDefault();
                    try{
                        sessionStorage.setItem('selected_mesa_id', String(m.id));
                    }catch(err){ /* ignore */ }
                    if (isLibre) {
                        // Load products and open the global product menu modal so staff can add items first
                        try{
                            loadProductos();
                        }catch(e){ console.debug('loadProductos err', e); }
                        openModal('modal-add-product-order');
                        return;
                    }
                    // If occupied, navigate to existing pedido view
                    window.location.href = '/admin/mesas/' + encodeURIComponent(m.id) + '/pedido';
                });
                a.innerHTML = `
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-lg ${isLibre? 'bg-emerald-500/10 text-emerald-400':'bg-slate-700 text-slate-400'} flex items-center justify-center">
                            <span class="material-symbols-outlined">restaurant</span>
                        </div>
                        <div>
                            <p class="font-bold text-white group-hover:text-primary transition-colors">${escapeHtml(m.identifier || ('M-' + String(m.id).padStart(2,'0')))}</p>
                            <p class="text-[10px] text-slate-500 uppercase tracking-widest">${escapeHtml(m.zone || 'General')} ${isLibre? '': '(ocupada)'}</p>
                        </div>
                    </div>
                `;
                container.appendChild(a);
            });
        }

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
    <script>
        // Attach logout handler to any element with [data-logout]
        document.addEventListener('click', function (e) {
            const el = e.target.closest && e.target.closest('[data-logout]');
            if (!el) return;
            e.preventDefault();
            if (window.AuthService && typeof window.AuthService.logout === 'function') {
                window.AuthService.logout();
            } else {
                // Fallback: clear localStorage token and navigate to login
                try { localStorage.removeItem('auth_token'); localStorage.removeItem('auth_user'); } catch (err) {}
                window.location.href = '{{ route('login') }}';
            }
        });
    </script>
    <script>
        // Role-based sidebar rendering: hide links not allowed for the active role
        (function(){
            function applyRoleUI(){
                try{
                    const userJson = localStorage.getItem('auth_user');
                    if (!userJson) return;
                    const user = JSON.parse(userJson);
                    const activeRoleId = (window.AuthService && typeof window.AuthService.getActiveRole === 'function') ? window.AuthService.getActiveRole() : (localStorage.getItem('active_role') ? Number(localStorage.getItem('active_role')) : null);
                    let activeRole = null;
                    if (user && Array.isArray(user.roles)){
                        if (activeRoleId) activeRole = user.roles.find(r => Number(r.id) === Number(activeRoleId));
                        if (!activeRole) activeRole = user.roles[0] || null;
                    }

                    const roleName = activeRole && activeRole.name ? String(activeRole.name).toLowerCase() : null;
                    // Debug info
                    try { console.debug('applyRoleUI:', { roleName, activeRole, userRoles: user.roles || null, role_id: user.role_id || null }); } catch(e){}

                    // If the user is admin (role name contains 'admin' or numeric role_id === 1), give full access
                    let isAdminOverride = false;
                    try {
                        if (roleName && roleName.indexOf('admin') !== -1) isAdminOverride = true;
                        if (!isAdminOverride && user && Number(user.role_id) === 1) isAdminOverride = true;
                    } catch(e){}

                    // update profile display
                    const nameEl = document.getElementById('sidebar-profile-name');
                    const roleEl = document.getElementById('sidebar-profile-role');
                    if (nameEl) nameEl.textContent = user.name || nameEl.textContent;
                    if (roleEl && roleName) roleEl.textContent = roleName.charAt(0).toUpperCase() + roleName.slice(1);

                    // show/hide nav links
                    // If user lacks `roles` array but has `role_id`, try to fetch full user from /api/me once.
                    try {
                        const rawUser = localStorage.getItem('auth_user');
                        if (rawUser) {
                            const u = JSON.parse(rawUser);
                            if ((!u.roles || !Array.isArray(u.roles) || u.roles.length === 0) && u.role_id) {
                                const fetchedFlag = sessionStorage.getItem('fetched_roles_for_user_' + (u.id || ''));
                                const token = localStorage.getItem('auth_token');
                                if (!fetchedFlag && token) {
                                    sessionStorage.setItem('fetched_roles_for_user_' + (u.id || ''), '1');
                                    fetch('/api/me', { headers: { 'Authorization': 'Bearer ' + token, 'Content-Type': 'application/json' }})
                                        .then(r => r.ok ? r.json() : Promise.reject(r))
                                        .then(data => {
                                            if (data && data.user) {
                                                try { localStorage.setItem('auth_user', JSON.stringify(data.user)); } catch(e){}
                                            }
                                            // re-run UI update after we have roles
                                            setTimeout(applyRoleUI, 50);
                                        })
                                        .catch(()=>{});
                                }
                            }
                        }
                    } catch (e) { /* ignore */ }

                    // Determine effective role: prefer `active_role`, fallback to first role from `auth_user.roles`.
                    let effectiveRole = null;
                    try {
                        const storedActive = localStorage.getItem('active_role');
                        if (storedActive && String(storedActive).trim().length) {
                            effectiveRole = String(storedActive).trim().toLowerCase();
                        } else {
                            const rawUser = localStorage.getItem('auth_user');
                            if (rawUser) {
                                const u = JSON.parse(rawUser);
                                if (u && Array.isArray(u.roles) && u.roles.length) {
                                    // roles may be objects with `name` or strings
                                    const first = u.roles[0];
                                    if (typeof first === 'string') effectiveRole = first.toLowerCase();
                                    else if (first && first.name) effectiveRole = String(first.name).toLowerCase();
                                }
                            }
                        }
                    } catch (e) {
                        effectiveRole = null;
                    }

                    document.querySelectorAll('[data-roles]').forEach(a => {
                        const allowed = String(a.getAttribute('data-roles')||'').split(',').map(s=>s.trim().toLowerCase()).filter(Boolean);
                        if (!allowed.length) {
                            // no restriction — leave visible
                            a.style.display = '';
                            return;
                        }
                        // If admin override, show all links
                        if (isAdminOverride) { a.style.display = ''; return; }

                        // If we determined an effective role, show only allowed links for it.
                        if (effectiveRole) {
                            if (allowed.indexOf(effectiveRole) === -1) a.style.display = 'none';
                            else a.style.display = '';
                        } else {
                                // No role at all: conservative default — keep links whose visible text is Dashboard or Mesas
                                const linkText = (a.textContent||'').toLowerCase();
                                const keep = linkText.indexOf('dashboard') !== -1 || linkText.indexOf('mesas') !== -1;
                                if (keep) a.style.display = '';
                                else a.style.display = 'none';
                        }
                    });
                }catch(e){ /* ignore */ }
            }

            // try to apply immediately and again after a short delay (AuthService may initialize slightly later)
            document.addEventListener('DOMContentLoaded', ()=>{ applyRoleUI(); setTimeout(applyRoleUI, 800); setTimeout(applyRoleUI, 2000); });
            // also apply when storage changes (e.g., login in another tab)
            window.addEventListener('storage', (e)=>{ if (e.key === 'auth_user' || e.key === 'active_role') applyRoleUI(); });
        })();
    </script>
    <script>
        // Handle Crear Pedido button inside the global product modal
        document.addEventListener('DOMContentLoaded', function(){
            try{
                const crearBtn = document.getElementById('crear-pedido-from-menu');
                if (crearBtn) crearBtn.addEventListener('click', function(){
                    try{
                        const mesa = sessionStorage.getItem('selected_mesa_id');
                        const items = JSON.parse(sessionStorage.getItem('pending_order_items') || '[]');
                        if (!mesa) { alert('Seleccione una mesa antes de crear el pedido.'); return; }
                        if (!items || !items.length) { alert('No hay productos añadidos.'); return; }
                        // mark mesa as occupied via API, then navigate to mesas list
                        const token = localStorage.getItem('auth_token');
                        const headers = { 'Accept': 'application/json', 'Content-Type': 'application/json' };
                        if (token) headers['Authorization'] = 'Bearer ' + token;
                        // call occupy endpoint
                        fetch('/api/mesas/' + encodeURIComponent(mesa) + '/occupy', { method: 'POST', headers })
                            .then(r => {
                                if (!r.ok) throw r;
                                return r.json();
                            })
                            .then(() => {
                                try{ sessionStorage.removeItem('pending_order_items'); } catch(e){}
                                closeModals();
                                // go to mesas list so staff can click the occupied mesa to invoice
                                window.location.href = '/admin/mesas';
                            })
                            .catch(err => {
                                console.debug('occupy mesa err', err);
                                // fallback: still navigate to mesas view
                                closeModals();
                                window.location.href = '/admin/mesas';
                            });
                    }catch(e){ console.debug('crear pedido click err', e); }
                });
            }catch(e){ console.debug('attach crear-pedido handler err', e); }
        });
    </script>
    @stack('scripts')
</body>
</html>
