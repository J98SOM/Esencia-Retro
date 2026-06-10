@extends('layouts.admin')

@section('title', 'Esencia Retro - Inventario')

@section('content')
<!-- Header Section -->
<header class="h-20 flex items-center justify-between px-8 bg-surface-container-low/50 backdrop-blur-md">
    <div>
        <h2 class="text-2xl font-extrabold text-on-surface tracking-tight">Inventario: Esencia Retro</h2>
        <p class="text-sm text-on-surface-variant font-medium">Control central de insumos y materia prima</p>
    </div>
    <div class="flex items-center gap-4">
        <div class="relative">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline">search</span>
            <input type="text" placeholder="Buscar insumo..." class="bg-surface-container-low border border-outline-variant/10 rounded-xl pl-10 pr-4 py-2 text-sm focus:outline-none focus:border-primary transition-all w-64">
        </div>
        <button onclick="openModal('modal-add-insumo')" class="bg-gradient-to-br from-primary to-primary-container text-on-primary-container px-6 py-2.5 rounded-xl font-bold text-sm shadow-xl shadow-primary/10 hover:scale-95 transition-transform">
            Agregar Insumo
        </button>
    </div>
</header>

<!-- Main Workspace -->
<div class="flex-1 flex overflow-hidden p-8 gap-8">
    <!-- Left Side: Data Table -->
    <section class="flex-[3] flex flex-col min-w-0">
        <div class="bg-surface-container-low rounded-2xl overflow-hidden shadow-2xl shadow-black/20 flex flex-col h-full border border-white/5">
            <div class="p-6 border-b border-white/5 flex items-center justify-between">
                <h3 class="font-bold text-lg flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">format_list_bulleted</span>
                    Lista de Insumos
                </h3>
                <div class="flex gap-2">
                    <button class="p-2 hover:bg-white/5 rounded-lg text-outline transition-colors"><span class="material-symbols-outlined">filter_list</span></button>
                    <button class="p-2 hover:bg-white/5 rounded-lg text-outline transition-colors"><span class="material-symbols-outlined">download</span></button>
                </div>
            </div>
            
            <div class="flex-1 overflow-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="sticky top-0 bg-surface-container-low z-10">
                        <tr>
                            <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-outline">Insumo</th>
                            <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-outline">Stock Actual</th>
                            <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-outline">Stock Mínimo</th>
                            <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-outline text-center">Estado</th>
                            <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-outline text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="inventarios-body" class="divide-y divide-white/5">
                        <tr id="inventarios-loading"><td colspan="5" class="px-6 py-8 text-center text-sm text-slate-400">Cargando insumos…</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- Right Side: Sidebar Analytics & Log -->
    <aside class="flex-1 flex flex-col gap-8 min-w-[320px]">
        <!-- Quick Metric Card -->
        <div class="bg-gradient-to-br from-surface-container-high to-surface-container shadow-2xl rounded-2xl p-6 border border-white/5 relative overflow-hidden group">
            <div class="absolute -right-8 -top-8 w-32 h-32 bg-primary/10 blur-3xl rounded-full transition-all group-hover:bg-primary/20"></div>
            <p class="text-[10px] font-bold uppercase tracking-widest text-outline mb-1">Insumos Críticos</p>
            <div class="flex items-end gap-3">
                <h4 class="text-4xl font-black text-error">03</h4>
                <span class="text-sm text-on-surface-variant mb-1.5">Requieren atención inmediata</span>
            </div>
            <div class="mt-4 w-full bg-white/5 h-1.5 rounded-full overflow-hidden">
                <div class="w-3/4 h-full bg-error rounded-full shadow-[0_0_10px_rgba(255,180,171,0.5)]"></div>
            </div>
        </div>

        <!-- Movimientos Recientes Section -->
        <div class="flex-1 bg-surface-container-low rounded-2xl flex flex-col shadow-2xl border border-white/5 overflow-hidden">
            <div class="p-6 border-b border-white/5">
                <h3 class="font-bold text-lg flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">history</span>
                    Movimientos Recientes
                </h3>
            </div>
            
            <div id="movimientos-list" class="flex-1 overflow-auto p-6 space-y-6">
                <!-- movimientos will be injected here -->
            </div>
            
            <button class="m-6 p-3 bg-surface-container-highest/50 hover:bg-surface-container-highest text-xs font-bold text-outline hover:text-white rounded-xl transition-all border border-white/5 uppercase tracking-widest">
                Ver todo el historial
            </button>
        </div>
    </aside>
</div>

@push('modals')
    <!-- Agregar Insumo Modal -->
    <div id="modal-add-insumo" class="modal-content hidden bg-surface-container-low border border-white/10 p-8 rounded-3xl w-full max-w-lg shadow-2xl transform scale-95 transition-transform duration-300">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-black text-white">Nuevo Insumo</h3>
            <button onclick="closeModals()" class="text-outline hover:text-white transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form id="form-add-insumo" class="space-y-4">
            <div>
                <label class="text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1 block">Nombre del Insumo</label>
                <input type="text" name="nombre" class="w-full bg-surface-container-highest border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-primary transition-all" placeholder="Ej. Tomates frescos" required>
                <p class="text-xs text-error mt-1 field-error" data-field="nombre"></p>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1 block">Stock Inicial</label>
                    <input type="number" step="0.1" name="stock" class="w-full bg-surface-container-highest border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-primary transition-all" placeholder="0.00" required>
                    <p class="text-xs text-error mt-1 field-error" data-field="stock"></p>
                </div>
                <div>
                    <label class="text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1 block">Unidad de Medida</label>
                    <select name="unidad" class="w-full bg-surface-container-highest border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-primary transition-all appearance-none cursor-pointer">
                        <option value="kg">Kilogramos (kg)</option>
                        <option value="L">Litros (L)</option>
                        <option value="und">Unidades (und)</option>
                    </select>
                    <p class="text-xs text-error mt-1 field-error" data-field="unidad"></p>
                </div>
            </div>
            <div>
                <label class="text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1 block">Stock Mínimo (Alerta)</label>
                <input type="number" name="stock_min" class="w-full bg-surface-container-highest border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-primary transition-all" placeholder="0.00">
                    <p class="text-xs text-error mt-1 field-error" data-field="stock_min"></p>
            </div>
            <div>
                <label class="text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1 block">Descuento Inventario (%)</label>
                <input type="number" step="0.01" min="0" name="descuento" value="0"
                    class="w-full bg-surface-container-highest border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-primary transition-all" placeholder="0">
                    <p class="text-xs text-error mt-1 field-error" data-field="descuento"></p>
            </div>
            <div class="flex gap-3 pt-4 border-t border-white/10 mt-6">
                <button type="button" onclick="closeModals()" class="flex-1 py-3 rounded-xl border border-white/10 hover:bg-white/5 transition-colors font-bold text-sm text-white">Cancelar</button>
                <button type="submit" class="flex-1 py-3 bg-gradient-to-br from-primary to-primary-container text-on-primary-container font-bold rounded-xl hover:scale-[0.98] transition-transform text-sm">Guardar Insumo</button>
            </div>
        </form>
    </div>

    <!-- Actualizar Insumo Modal (Editar todos los campos) -->
    <div id="modal-edit-insumo" class="modal-content hidden bg-surface-container-low border border-white/10 p-6 rounded-3xl w-full max-w-md shadow-2xl transform scale-95 transition-transform duration-300">
        <h3 class="text-xl font-black text-white mb-2">Editar Insumo</h3>
        <form id="form-edit-insumo" class="space-y-4">
            <input type="hidden" name="id" id="edit-insumo-id">
            <div>
                <label class="text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1 block">Nombre del Insumo</label>
                <input id="edit-insumo-nombre" name="nombre" type="text" class="w-full bg-surface-container-highest border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-primary" required>
                <p class="text-xs text-error mt-1 field-error" data-field="nombre"></p>
            </div>
            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label class="text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1 block">Stock Actual</label>
                    <input id="edit-insumo-stock" name="stock_inicial" type="number" step="0.1" class="w-full bg-surface-container-highest border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-primary" required>
                    <p class="text-xs text-error mt-1 field-error" data-field="stock_inicial"></p>
                </div>
                <div>
                    <label class="text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1 block">Stock Mínimo</label>
                    <input id="edit-insumo-stock-min" name="stock_minimo" type="number" step="0.1" class="w-full bg-surface-container-highest border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-primary">
                    <p class="text-xs text-error mt-1 field-error" data-field="stock_minimo"></p>
                </div>
                <div>
                    <label class="text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1 block">Unidad</label>
                    <select id="edit-insumo-unidad" name="unidad_medida" class="w-full bg-surface-container-highest border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-primary appearance-none">
                        <option value="kg">kg</option>
                        <option value="L">L</option>
                        <option value="und">und</option>
                    </select>
                    <p class="text-xs text-error mt-1 field-error" data-field="unidad_medida"></p>
                </div>
            </div>
            <div>
                <label class="text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1 block">Descuento Inventario (%)</label>
                <input id="edit-insumo-descuento" name="descuento_inventario" type="number" step="0.01" min="0" class="w-full bg-surface-container-highest border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-primary">
                <p class="text-xs text-error mt-1 field-error" data-field="descuento_inventario"></p>
            </div>
            <div class="flex gap-3 pt-4 border-t border-white/10 mt-4">
                <button type="button" onclick="closeModals()" class="flex-1 py-3 rounded-xl border border-white/10 hover:bg-white/5 transition-colors font-bold text-sm text-white">Cancelar</button>
                <button type="submit" id="edit-insumo-submit" class="flex-1 py-3 bg-primary text-on-primary font-bold rounded-xl hover:scale-[0.98] transition-transform text-sm">Guardar cambios</button>
            </div>
        </form>
    </div>
@endpush

@push('scripts')
<script>
(function(){
    function apiBase(){ return (window.VITE_API_URL || window.API_BASE || '/api').replace(/\/$/, ''); }
    const body = document.getElementById('inventarios-body');
    const loadingRow = document.getElementById('inventarios-loading');
    const searchInput = document.querySelector('header input[placeholder="Buscar insumo..."]');
    const criticalCountEl = document.querySelector('.text-4xl.font-black.text-error');

    function fmtQty(value, unidad){
        if (unidad === 'und' || unidad === 'und.') return `${Number(value).toFixed(0)} und`;
        if (!unidad) return `${Number(value)} `;
        return `${Number(value)} ${unidad}`;
    }

    function renderRow(item){
        const id = item.id;
        const nombre = item.nombre || '';
        const stock = Number(item.stock_inicial || 0);
        const stock_min = Number(item.stock_minimo || 0);
        const unidad = item.unidad_medida || '';
        const isCritical = stock < stock_min;

        return `
        <tr class="hover:bg-white/[0.02] transition-colors group" data-id="${id}">
            <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined">inventory_2</span>
                    </div>
                    <span class="font-semibold text-on-surface">${nombre}</span>
                </div>
            </td>
            <td class="px-6 py-4 font-mono text-sm ${isCritical? 'text-error border-b border-dashed border-error/50':''}">${fmtQty(stock, unidad)}</td>
            <td class="px-6 py-4 font-mono text-sm">${fmtQty(stock_min, unidad)}</td>
            <td class="px-6 py-4 text-center">
                ${isCritical ? `<span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold bg-error-container/20 text-error uppercase tracking-wider">Crítico</span>` : `<span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold bg-secondary-container/20 text-secondary-fixed-dim uppercase tracking-wider">Óptimo</span>`}
            </td>
            <td class="px-6 py-4 text-right">
                <button data-action="edit" data-id="${id}" class="text-xs font-bold text-primary hover:text-white bg-primary/10 hover:bg-primary px-3 py-1.5 rounded-lg transition-all">Actualizar</button>
                <button data-action="delete" data-id="${id}" class="ml-2 text-xs font-bold text-error hover:text-white bg-error/10 hover:bg-error px-3 py-1.5 rounded-lg transition-all">Eliminar</button>
            </td>
        </tr>
        `;
    }

    async function fetchInventarios(){
        if (!body) return;
        body.innerHTML = '<tr><td colspan="5" class="px-6 py-8 text-center text-sm text-slate-400">Cargando insumos…</td></tr>';
        try{
            const token = localStorage.getItem('auth_token');
            const headers = { 'Accept':'application/json' };
            if (token) headers['Authorization'] = 'Bearer ' + token;
            const res = await fetch(apiBase() + '/inventarios', { headers });
            if (res.status === 401){ body.innerHTML = '<tr><td colspan="5" class="px-6 py-8 text-center text-sm text-red-400">No autorizado</td></tr>'; return; }
            if (!res.ok) throw new Error('Error cargando inventarios');
            const data = await res.json();
            const list = Array.isArray(data.data) ? data.data : data;
            if (!list || !list.length){ cacheSet([]); body.innerHTML = '<tr><td colspan="5" class="px-6 py-8 text-center text-sm text-slate-400">No hay insumos.</td></tr>'; updateCriticalCount(0); return; }
            cacheSet(list);
            body.innerHTML = list.map(renderRow).join('');
            updateCriticalCount(list.filter(i=> Number(i.stock_inicial||0) < Number(i.stock_minimo||0)).length);
        }catch(err){
            console.error(err);
            body.innerHTML = '<tr><td colspan="5" class="px-6 py-8 text-center text-sm text-red-400">Error cargando insumos</td></tr>';
        }
    }

    function updateCriticalCount(n){ if (criticalCountEl) criticalCountEl.textContent = String(n).padStart(2,'0'); }

    // search handler
    if (searchInput){
        let t = null;
        searchInput.addEventListener('input', ()=>{
            clearTimeout(t);
            t = setTimeout(async ()=>{
                const q = searchInput.value.trim();
                if (!q){ fetchInventarios(); return; }
                try{
                    const token = localStorage.getItem('auth_token');
                    const headers = { 'Accept':'application/json' };
                    if (token) headers['Authorization'] = 'Bearer ' + token;
                    const res = await fetch(apiBase() + '/inventarios?q=' + encodeURIComponent(q), { headers });
                    if (!res.ok) throw new Error('search error');
                    const data = await res.json();
                    const list = Array.isArray(data.data) ? data.data : data;
                    if (!list || !list.length){ cacheSet([]); body.innerHTML = '<tr><td colspan="5" class="px-6 py-8 text-center text-sm text-slate-400">No hay insumos.</td></tr>'; updateCriticalCount(0); return; }
                    cacheSet(list);
                    body.innerHTML = list.map(renderRow).join('');
                    updateCriticalCount(list.filter(i=> Number(i.stock_inicial||0) < Number(i.stock_minimo||0)).length);
                }catch(e){ console.error(e); }
            }, 300);
        });
    }

    // delegate actions (edit/delete)
    document.addEventListener('click', async function(ev){
        const btn = ev.target.closest && ev.target.closest('button[data-action]');
        if (!btn) return;
        const action = btn.getAttribute('data-action');
        const id = btn.getAttribute('data-id');
        if (action === 'delete'){
            if (!confirm('Eliminar insumo? Esta acción no se puede revertir.')) return;
            try{
                const token = localStorage.getItem('auth_token');
                const headers = { 'Accept':'application/json' };
                if (token) headers['Authorization'] = 'Bearer ' + token;
                // capture current row values for movement record
                const row = document.querySelector('tr[data-id="'+id+'"]');
                let qtyText = '';
                if (row) {
                    const qtyTd = row.querySelectorAll('td')[1];
                    qtyText = qtyTd ? qtyTd.textContent.trim() : '';
                }
                const res = await fetch(apiBase() + '/inventarios/' + id, { method: 'DELETE', headers });
                if (res.status === 204 || res.ok){
                    // record removal movement using available qty text
                    recordMovimiento({ tipo: 'out', nombre: row ? row.querySelector('td span.font-semibold').textContent.trim() : 'Insumo', cantidad_text: qtyText, motivo: 'Eliminación' });
                    fetchInventarios();
                    return;
                }
                const bodyErr = await res.json().catch(()=>({}));
                alert(bodyErr.message || 'Error eliminando');
            }catch(err){ console.error(err); alert('Error eliminando'); }
        }
        if (action === 'edit'){
            // open existing modal for editing: populate modal with current row values
            try{
                // try cache first for speed
                let item = cacheGet(id);
                if (!item){
                    const token = localStorage.getItem('auth_token');
                    const headers = { 'Accept':'application/json' };
                    if (token) headers['Authorization'] = 'Bearer ' + token;
                    const res = await fetch(apiBase() + '/inventarios/' + id, { headers });
                    if (res.ok){
                        const j = await res.json().catch(()=>null);
                        item = Array.isArray(j) ? j[0] : (j && j.data ? j.data : j);
                    }
                }
                // fallback to DOM parsing
                if (!item){
                    const row = document.querySelector('tr[data-id="'+id+'"]');
                    const nameEl = row ? row.querySelector('td span.font-semibold') : null;
                    const qtyTd = row ? row.querySelectorAll('td')[1] : null;
                    const stockMinTd = row ? row.querySelectorAll('td')[2] : null;
                    const nombreFallback = nameEl ? nameEl.textContent.trim() : '';
                    const qtyText = qtyTd ? qtyTd.textContent.trim() : '';
                    const stockMinText = stockMinTd ? stockMinTd.textContent.trim() : '';
                    const m = qtyText.match(/^\s*([0-9.,]+)\s*(\S*)/);
                    const value = m ? Number(String(m[1]).replace(',', '.')) : 0;
                    const unidad = (m && m[2]) ? m[2] : '';
                    item = {
                        id: id,
                        nombre: nombreFallback,
                        stock_inicial: value,
                        stock_minimo: (stockMinText.match(/[0-9.,]+/) ? Number(stockMinText.match(/[0-9.,]+/)[0].replace(',', '.')) : 0),
                        unidad_medida: unidad
                    };
                }

                currentEditItem = item;
                // populate edit form fields
                if (editIdInput) editIdInput.value = item.id || id;
                if (editNombre) editNombre.value = item.nombre || '';
                if (editStock) editStock.value = item.stock_inicial !== undefined ? item.stock_inicial : 0;
                if (editStockMin) editStockMin.value = item.stock_minimo !== undefined ? item.stock_minimo : 0;
                if (editUnidad) editUnidad.value = item.unidad_medida || 'kg';
                if (editDescuento) editDescuento.value = item.descuento_inventario !== undefined ? item.descuento_inventario : 0;
                openModal('modal-edit-insumo');
            }catch(e){ console.error(e); openModal('modal-edit-insumo'); }
        }
    });

    // initial
    // Movimientos helpers
    const movimientosKey = 'inventory_movements_v1';
    const movimientosContainer = document.getElementById('movimientos-list');

    function loadMovimientos(){
        try{ return JSON.parse(localStorage.getItem(movimientosKey) || '[]'); }catch(e){ return []; }
    }

    function saveMovimientos(list){ localStorage.setItem(movimientosKey, JSON.stringify(list)); }

    function formatTime(iso){
        try{ const d = new Date(iso); return d.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}); }catch(e){ return '' }
    }

    function renderMovimientos(){
        const list = loadMovimientos();
        if (!movimientosContainer) return;
        if (!list.length){ movimientosContainer.innerHTML = '<p class="text-sm text-slate-400">Sin movimientos recientes.</p>'; return; }
        movimientosContainer.innerHTML = list.slice(0,12).map(m => {
            const isIn = m.tipo === 'in';
            const sign = isIn ? '+' : '-';
            const colorBg = isIn ? 'bg-green-500/20' : 'bg-red-500/20';
            const colorIcon = isIn ? 'text-green-400' : 'text-red-400';
            const qty = m.cantidad_text || (m.cantidad !== undefined ? (m.cantidad + (m.unidad||'')) : '');
            return `
                <div class="flex gap-4">
                    <div class="mt-1 w-8 h-8 rounded-full ${colorBg} flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined ${colorIcon} text-sm">${isIn? 'add':'remove'}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-start mb-0.5">
                            <p class="text-sm font-bold text-on-surface truncate">${m.nombre}</p>
                            <span class="text-[10px] font-mono text-outline">${formatTime(m.time || new Date().toISOString())}</span>
                        </div>
                        <p class="text-xs text-on-surface-variant font-medium">${sign}${qty} <span class="text-[10px] opacity-60 ml-2">${m.motivo || ''}</span></p>
                    </div>
                </div>
            `;
        }).join('');
    }

    function recordMovimiento(m){
        const list = loadMovimientos();
        const entry = Object.assign({ time: new Date().toISOString() }, m);
        list.unshift(entry);
        saveMovimientos(list.slice(0,50));
        renderMovimientos();
    }

    // Inline feedback helpers
    function clearFormFeedback(form){
        if (!form) return;
        form.querySelectorAll('.field-error').forEach(el=> el.textContent = '');
        const msg = form.querySelector('.form-message'); if (msg) msg.remove();
    }

    function showFieldErrors(form, errors){
        if (!form || !errors) return;
        // errors expected as { fieldName: ["msg1","msg2"] }
        Object.keys(errors).forEach(fn=>{
            const msgs = errors[fn];
            const el = form.querySelector('.field-error[data-field="'+fn+'"]');
            if (el) el.textContent = Array.isArray(msgs)? msgs.join('. '): String(msgs);
        });
    }

    function showFormMessage(form, type, text){
        if (!form) return;
        const div = document.createElement('div');
        div.className = 'form-message p-3 rounded-md text-sm mb-2 ' + (type==='success' ? 'bg-emerald-600/20 text-emerald-200' : 'bg-error/10 text-error');
        div.textContent = text || '';
        const first = form.querySelector(':scope');
        form.insertBefore(div, form.firstChild);
        setTimeout(()=>{ div.remove(); }, 4000);
    }

    // initial render
    renderMovimientos();

    // simple cache for inventarios to speed up UI
    let inventariosCache = [];
    function cacheSet(list){ inventariosCache = Array.isArray(list)? list.slice() : []; }
    function cacheGet(id){ return inventariosCache.find(x=> String(x.id) === String(id)); }

    // wire edit form
    const editForm = document.getElementById('form-edit-insumo');
    const editIdInput = document.getElementById('edit-insumo-id');
    const editNombre = document.getElementById('edit-insumo-nombre');
    const editStock = document.getElementById('edit-insumo-stock');
    const editStockMin = document.getElementById('edit-insumo-stock-min');
    const editUnidad = document.getElementById('edit-insumo-unidad');
    const editDescuento = document.getElementById('edit-insumo-descuento');

    if (editForm){
        editForm.addEventListener('submit', async function(e){
            e.preventDefault();
            const id = editIdInput.value;
            if (!id) return closeModals();
            const old = cacheGet(id) || currentEditItem || {};
            const payload = {
                id: id,
                nombre: (editNombre.value||'').trim(),
                stock_inicial: Number(editStock.value||0),
                stock_minimo: Number(editStockMin.value||0),
                unidad_medida: editUnidad.value || null,
                descuento_inventario: Number(editDescuento.value||0)
            };
            try{
                const token = localStorage.getItem('auth_token');
                const headers = { 'Accept':'application/json', 'Content-Type':'application/json' };
                if (token) headers['Authorization'] = 'Bearer ' + token;
                const res = await fetch(apiBase() + '/inventarios/' + id, { method: 'PUT', headers, body: JSON.stringify(payload) });
                clearFormFeedback(editForm);
                if (res.status === 401){ showFormMessage(editForm, 'error', 'No autorizado'); return; }
                if (res.status === 422){ const body = await res.json().catch(()=>null); const errs = body && body.errors ? body.errors : null; if (errs){ showFieldErrors(editForm, errs); showFormMessage(editForm, 'error', 'Corrige los errores'); } else { showFormMessage(editForm,'error',(body && body.message) || 'Errores de validación'); } return; }
                if (!res.ok){ const body = await res.text().catch(()=>null); showFormMessage(editForm, 'error', body || 'Error actualizando insumo'); return; }
                const updated = await res.json().catch(()=>null) || payload;
                // update cache and DOM in-place for speed
                const idx = inventariosCache.findIndex(x=> String(x.id) === String(id));
                if (idx > -1) inventariosCache[idx] = Object.assign({}, inventariosCache[idx], updated);
                // update row DOM
                const row = document.querySelector('tr[data-id="'+id+'"]');
                if (row){ row.outerHTML = renderRow(Object.assign({}, old, updated)); }
                // record movement: difference between new and old stock
                const oldStock = Number(old.stock_inicial||0);
                const newStock = Number(updated.stock_inicial||payload.stock_inicial||0);
                const diff = +(newStock - oldStock).toFixed(2);
                if (diff !== 0){
                    recordMovimiento({ tipo: diff>0? 'in':'out', nombre: payload.nombre, cantidad: Math.abs(diff), unidad: payload.unidad_medida, motivo: 'Ajuste' });
                }
                closeModals();
            }catch(err){ console.error(err); alert('Error actualizando insumo'); }
        });
    }

    // handle create insumo form
    const addForm = document.getElementById('form-add-insumo');
    if (addForm){
        addForm.addEventListener('submit', async function(e){
            e.preventDefault();
            const fd = new FormData(addForm);
            const payload = {
                nombre: (fd.get('nombre')||'').trim(),
                producto_id: null,
                stock_inicial: Number(fd.get('stock')||0),
                stock_minimo: Number(fd.get('stock_min')||0),
                unidad_medida: fd.get('unidad') || null,
                descuento_inventario: Number(fd.get('descuento')||0)
            };
            try{
                const token = localStorage.getItem('auth_token');
                const headers = { 'Accept':'application/json', 'Content-Type':'application/json' };
                if (token) headers['Authorization'] = 'Bearer ' + token;
                const res = await fetch(apiBase() + '/inventarios', { method: 'POST', headers, body: JSON.stringify(payload) });
                clearFormFeedback(addForm);
                if (res.status === 401){ showFormMessage(addForm, 'error', 'No autorizado'); return; }
                if (res.status === 422){ const body = await res.json().catch(()=>null); const errs = body && body.errors ? body.errors : null; if (errs) { showFieldErrors(addForm, errs); showFormMessage(addForm, 'error', 'Corrige los errores'); } else { showFormMessage(addForm,'error',(body && body.message) || 'Errores de validación'); } return; }
                if (!res.ok) { const body = await res.text().catch(()=>null); showFormMessage(addForm, 'error', body || 'Error creando insumo'); return; }
                // success
                showFormMessage(addForm, 'success', 'Insumo creado correctamente');
                // record movement
                recordMovimiento({ tipo: 'in', nombre: payload.nombre, cantidad: payload.stock_inicial, unidad: payload.unidad_medida, motivo: 'Creación' });
                fetchInventarios();
                setTimeout(()=> closeModals(), 700);
            }catch(err){ console.error(err); alert('Error creando insumo'); }
        });
    }

    fetchInventarios();
})();
</script>
@endpush

@endsection
