@extends('layouts.admin')

@section('title', 'Esencia Retro - Mesas')

@section('content')
<div class="p-8 flex-1">
    <!-- Header & Filters Bento -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-12">
        <div class="lg:col-span-8 flex flex-col justify-center">
            <h2 class="text-4xl font-black text-on-surface tracking-tighter mb-2">Gestión de Mesas</h2>
            <p class="text-on-surface-variant text-lg">Monitoreo en tiempo real del área de comedor.</p>
        </div>
        <div class="lg:col-span-4 flex items-center justify-end gap-4">
            <div class="relative w-full">
                <input class="w-full bg-surface-container-low border-none focus:ring-2 focus:ring-primary text-on-surface placeholder:text-slate-500 rounded-xl px-4 py-3 pl-11 outline-none" placeholder="Buscar mesa..." type="text"/>
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-500">search</span>
            </div>
            <button class="p-3 bg-surface-container-high rounded-xl text-primary hover:bg-surface-bright transition-colors">
                <span class="material-symbols-outlined">filter_list</span>
            </button>
        </div>
    </div>

    <!-- Status Filter Bar -->
    <div class="flex flex-wrap gap-4 mb-10" id="mesas-filters">
        <button id="btn-filter-all" data-status="all" class="filter-btn px-6 py-2 rounded-full bg-primary text-on-primary font-bold text-sm shadow-xl shadow-primary/10">Todas (<span id="count-all">0</span>)</button>
        <button id="btn-filter-libres" data-status="libre" class="filter-btn px-6 py-2 rounded-full bg-surface-container-high text-on-surface-variant hover:text-on-surface transition-colors font-bold text-sm">Libres (<span id="count-libres">0</span>)</button>
        <button id="btn-filter-ocupadas" data-status="ocupada" class="filter-btn px-6 py-2 rounded-full bg-surface-container-high text-on-surface-variant hover:text-on-surface transition-colors font-bold text-sm">Ocupadas (<span id="count-ocupadas">0</span>)</button>
        <button id="btn-filter-reservadas" data-status="reservada" class="filter-btn px-6 py-2 rounded-full bg-surface-container-high text-on-surface-variant hover:text-on-surface transition-colors font-bold text-sm">Reservadas (<span id="count-reservadas">0</span>)</button>
    </div>

    <!-- Tables Grid (loaded from backend) -->
    <div id="mesas-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6"></div>
</div>

<!-- FAB for Quick Actions -->
<button class="fixed bottom-8 right-8 w-14 h-14 bg-primary text-on-primary rounded-full shadow-2xl flex items-center justify-center md:hidden z-50">
    <span class="material-symbols-outlined">add</span>
</button>

@push('modals')
    <!-- Abrir Mesa Modal -->
    <div id="modal-open-table" class="modal-content hidden bg-surface-container-low border border-white/10 p-8 rounded-3xl w-full max-w-sm shadow-2xl transform scale-95 transition-transform duration-300">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-black text-white">Abrir Mesa</h3>
            <button onclick="closeModals()" class="text-outline hover:text-white transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form class="space-y-6">
            <div>
                <label class="text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-2 block">Número de Comensales (Pax)</label>
                <div class="flex items-center justify-center gap-6 py-4 bg-surface-container-highest rounded-xl border border-white/10">
                    <button type="button" class="w-10 h-10 rounded-full hover:bg-white/10 transition-colors flex items-center justify-center text-xl font-bold text-white">
                        <span class="material-symbols-outlined">remove</span>
                    </button>
                    <span class="text-3xl font-black text-white">2</span>
                    <button type="button" class="w-10 h-10 rounded-full hover:bg-white/10 transition-colors flex items-center justify-center text-xl font-bold text-white">
                        <span class="material-symbols-outlined">add</span>
                    </button>
                </div>
            </div>
            <div class="flex gap-3 pt-4 border-t border-white/10">
                <button type="button" onclick="closeModals()" class="flex-1 py-3 rounded-xl border border-white/10 hover:bg-white/5 transition-colors font-bold text-sm text-white">Cancelar</button>
                <button type="button" onclick="closeModals()" class="flex-1 py-3 bg-emerald-500 text-white font-bold rounded-xl hover:scale-[0.98] transition-transform text-sm">Iniciar Servicio</button>
            </div>
        </form>
    </div>

    <!-- Cerrar Mesa Modal -->
    <div id="modal-close-table" class="modal-content hidden bg-surface-container-low border border-white/10 p-8 rounded-3xl w-full max-w-sm shadow-2xl transform scale-95 transition-transform duration-300 text-center">
        <div class="w-16 h-16 rounded-full bg-error/10 flex items-center justify-center text-error mx-auto mb-4">
            <span class="material-symbols-outlined text-3xl">money_off</span>
        </div>
        <h3 class="text-xl font-black text-white mb-2">¿Liberar Mesa?</h3>
        <p class="text-sm text-on-surface-variant mb-6">Si cierras esta mesa sin facturar, el historial de pedidos no registrará pagos. ¿Deseas redirigirte al checkout?</p>
        
        <div class="flex flex-col gap-3">
            <button type="button" onclick="closeModals()" class="w-full py-3 rounded-xl bg-primary text-on-primary font-bold shadow-lg shadow-primary/20 hover:scale-[0.98] transition-transform text-sm">
                Ir a Facturar y Cobrar
            </button>
            <button type="button" onclick="closeModals()" class="w-full py-3 rounded-xl border border-error/20 hover:bg-error/10 hover:text-error text-error/80 transition-colors font-bold text-sm">
                Solo Liberar (Sin Pago)
            </button>
        </div>
    </div>

    <!-- Add Table Modal -->
    <div id="modal-add-table" class="modal-content hidden bg-surface-container-low border border-white/10 p-8 rounded-3xl w-full max-w-sm shadow-2xl transform scale-95 transition-transform duration-300">
            <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-black text-white">Nueva Mesa</h3>
            <button onclick="closeModals()" class="text-outline hover:text-white transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form class="space-y-4">
            <div>
                <label class="text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1 block">Identificador o Número</label>
                <input id="new-table-identifier" type="text" class="w-full bg-surface-container-highest border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-primary transition-all" placeholder="Ej. VIP 02, Terraza 5">
            </div>
            <div>
                <label class="text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1 block">Capacidad Max. Pax</label>
                <input id="new-table-capacity" type="number" class="w-full bg-surface-container-highest border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-primary transition-all" placeholder="4">
            </div>
            <div class="flex gap-3 pt-4 border-t border-white/10 mt-6">
                <button id="btn-create-mesa" type="button" onclick="createMesa()" class="flex-1 py-3 bg-gradient-to-br from-primary to-primary-container text-on-primary-container font-bold rounded-xl hover:scale-[0.98] transition-transform text-sm">Registrar Mesa</button>
            </div>
        </form>
    </div>
@endpush

@endsection

@push('scripts')
<script>
(function(){
    const grid = document.getElementById('mesas-grid');
    function apiBase(){ return (window.VITE_API_URL || window.API_BASE || '/api').replace(/\/$/, ''); }

    function renderMesa(m){
        const id = m.id || m._id || '';
        const identifier = m.nombre || m.identifier || m.name || m.numero || ('Mesa ' + id);
        const capacity = (m.capacidad || m.capacity || m.pax || '');
        const status = (m.status || m.estado || 'Libre').toString().toLowerCase();
        const isOccupied = status === 'ocupada' || status === 'occupied' || status === 'busy' || status === 'occupied';

        const statusBadge = isOccupied ? `<div class="px-3 py-1 bg-primary/20 rounded-lg"><span class="text-[10px] font-black text-primary uppercase tracking-widest">Ocupada</span></div>` : `<div class="px-3 py-1 bg-emerald-500/10 rounded-lg"><span class="text-[10px] font-black text-emerald-400 uppercase tracking-widest">Libre</span></div>`;

        return `
            <div data-from-backend="1" data-status="${status}" class="group relative ${isOccupied? 'bg-surface-container-highest':'bg-surface-container-low'} rounded-3xl p-6 border border-white/5 overflow-hidden transition-all duration-300 hover:shadow-2xl hover:shadow-primary/10">
                <div class="flex justify-between items-start mb-8">
                    <div>
                        <span class="text-slate-500 font-['Inter'] uppercase tracking-widest text-[10px] block mb-1">${m.zone||''}</span>
                        <h3 class="text-3xl font-black text-white">${identifier}</h3>
                    </div>
                    ${statusBadge}
                </div>
                <div class="space-y-4">
                    <div class="flex justify-between items-end">
                        <div class="text-on-surface-variant">
                            <p class="text-[10px] uppercase tracking-widest font-bold">Capacidad</p>
                            <p class="text-2xl font-bold text-primary">${capacity || '-'}</p>
                        </div>
                        <div class="text-right">
                            <span class="material-symbols-outlined text-slate-500 mb-1">schedule</span>
                            <p class="text-xs text-on-surface-variant">${m.updated_at || ''}</p>
                        </div>
                    </div>
                    <div class="pt-4 border-t border-white/5 flex gap-2">
                        <a href="${(window.location.origin) + '/admin/mesas/' + (id) + '/pedido'}" class="flex-1 py-2 bg-white/5 hover:bg-white/10 rounded-lg text-center text-[10px] font-bold uppercase tracking-widest block transition-colors">Detalles</a>
                        <button data-id="${id}" class="btn-delete-mesa flex-1 py-2 bg-primary/10 text-primary hover:bg-primary/20 rounded-lg text-[10px] font-bold uppercase tracking-widest transition-colors">Eliminar</button>
                    </div>
                </div>
            </div>
        `;
    }

    async function fetchMesas(){
        grid.setAttribute('data-loading','1');
        grid.innerHTML = '<p class="text-sm text-slate-400">Cargando mesas…</p>';
        try{
            const token = localStorage.getItem('auth_token');
            const headers = { 'Accept': 'application/json', 'Content-Type': 'application/json' };
            if (token) headers['Authorization'] = 'Bearer ' + token;
            const res = await fetch(apiBase() + '/mesas', { headers });

            // if backend redirects to /login or responds 401, clear and redirect
            if (res.status === 401) {
                localStorage.removeItem('auth_token');
                localStorage.removeItem('auth_user');
                window.location.href = '/login';
                return;
            }

            if (!res.ok) throw new Error('Error cargando mesas');
            const json = await res.json();
            const list = Array.isArray(json.data) ? json.data : (json.mesas || json);
            if (!list || !list.length) {
                grid.innerHTML = '<p class="text-sm text-slate-400">No hay mesas definidas.</p>';
                return;
            }
            grid.innerHTML = list.map(renderMesa).join('');
            // update filter counts and reapply current filter
            try{ updateStatusCounts(list); }catch(e){}
            applyFilter(currentFilter);
            grid.removeAttribute('data-loading');
            grid.setAttribute('data-loaded','1');
            // attach delete handlers
            document.querySelectorAll('.btn-delete-mesa').forEach(b=>{
                b.addEventListener('click', async (ev)=>{
                    const id = b.getAttribute('data-id');
                    if (!confirm('¿Eliminar mesa #' + id + '?')) return;
                    try{
                        const token = localStorage.getItem('auth_token');
                        const headers = { 'Accept':'application/json', 'Content-Type':'application/json' };
                        if (token) headers['Authorization'] = 'Bearer ' + token;
                        const r = await fetch(apiBase() + '/mesas/' + id, { method: 'DELETE', headers });
                        if (!r.ok) throw new Error('no');
                        fetchMesas();
                    }catch(e){ alert('No se pudo eliminar'); }
                });
            });
        }catch(e){ grid.innerHTML = '<p class="text-sm text-red-400">Error cargando mesas</p>'; }
    }

    // create mesa from modal
    window.createMesa = async function(){
        const identifier = document.getElementById('new-table-identifier').value.trim();
        const capacity = document.getElementById('new-table-capacity').value.trim();
        if (!identifier) return alert('Identificador requerido');
        try{
            const token = localStorage.getItem('auth_token');
            const headers = { 'Accept':'application/json', 'Content-Type':'application/json' };
            if (token) headers['Authorization'] = 'Bearer ' + token;
            const res = await fetch(apiBase() + '/mesas', {
                method: 'POST', headers, body: JSON.stringify({ nombre: identifier, capacidad: capacity ? Number(capacity) : null })
            });
            if (!res.ok) {
                const body = await res.json().catch(()=>({}));
                throw new Error(body.message || 'Error creando mesa');
            }
            // close modal and refresh
            closeModals();
            fetchMesas();
        }catch(e){ alert(e.message || 'Error'); }
    };

    // filtering helpers
    let currentFilter = 'all';
    function updateStatusCounts(list){
        const totals = { all: list.length, libre: 0, ocupada: 0, reservada: 0 };
        list.forEach(m=>{
            const s = (m.status||m.estado||'libre').toString().toLowerCase();
            if (s.includes('ocup')) totals.ocupada++;
            else if (s.includes('reserv')) totals.reservada++;
            else totals.libre++;
        });
        document.getElementById('count-all').textContent = totals.all;
        document.getElementById('count-libres').textContent = totals.libre;
        document.getElementById('count-ocupadas').textContent = totals.ocupada;
        document.getElementById('count-reservadas').textContent = totals.reservada;
    }

    function applyFilter(status){
        currentFilter = status || 'all';
        document.querySelectorAll('#mesas-filters .filter-btn').forEach(b=> b.classList.remove('bg-primary','text-on-primary'));
        const activeBtn = document.querySelector(`#mesas-filters button[data-status="${currentFilter}"]`);
        if (activeBtn) activeBtn.classList.add('bg-primary','text-on-primary');
        document.querySelectorAll('#mesas-grid [data-from-backend]').forEach(card=>{
            const s = (card.getAttribute('data-status')||'libre').toString().toLowerCase();
            if (currentFilter === 'all' || s === currentFilter) card.style.display = '';
            else card.style.display = 'none';
        });
    }

    // attach filter button handlers
    document.addEventListener('click', (ev)=>{
        const btn = ev.target.closest && ev.target.closest('#mesas-filters button');
        if (!btn) return;
        const status = btn.getAttribute('data-status');
        applyFilter(status);
    });

    // ensure any static/front-only mesas are removed: observe mutations and force backend reload
    grid.innerHTML = ''; // wipe any server-rendered visual mocks
    let reloadTimer = null;
    const observer = new MutationObserver((mutations)=>{
        // if nodes are added that are not from backend and we haven't loaded backend mesas yet, clear and refetch
        for (const m of mutations){
            for (const n of m.addedNodes){
                if (n.nodeType !== 1) continue;
                if (!n.hasAttribute || !n.hasAttribute('data-from-backend')){
                    // remove the visual mock
                    n.remove();
                    if (!grid.hasAttribute('data-loading') && !grid.hasAttribute('data-loaded')){
                        clearTimeout(reloadTimer);
                        reloadTimer = setTimeout(()=>{ try { fetchMesas(); } catch(e){} }, 50);
                    }
                }
            }
        }
    });
    observer.observe(grid, { childList: true, subtree: false });

    // initial load — trigger immediately and again on DOMContentLoaded as fallback
    try { fetchMesas(); } catch(e) {}
    document.addEventListener('DOMContentLoaded', ()=>{ try { fetchMesas(); } catch(e){} });
})();
</script>
@endpush
