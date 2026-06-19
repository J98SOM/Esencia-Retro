// JS para vista de Alquiler
(function(){
    const API_BASE = (window.VITE_API_URL || window.API_BASE || '/api').replace(/\/$/, '');
    const token = () => localStorage.getItem('auth_token');

    // Helpers UI
    function el(selector){ return document.querySelector(selector); }
    function numberFrom(str){ if (!str && str!==0) return 0; return Number(String(str).replace(/[^0-9.-]+/g,'')) || 0; }

    function showFacturaMessage(type, text){
        const container = el('#factura-alquiler');
        if (!container) return;
        const existing = container.querySelector('.alquiler-message');
        if (existing) existing.remove();
        const div = document.createElement('div');
        div.className = 'alquiler-message p-3 rounded-md mb-3 text-sm ' + (type==='success'? 'bg-emerald-600/20 text-emerald-200':'bg-error/10 text-error');
        div.textContent = text;
        container.insertBefore(div, container.firstChild);
        setTimeout(()=>{ div.remove(); }, 4000);
    }

    // Cálculo de fila y totales
    window.calcularFila = function(i){
        try{
            const cant = numberFrom(document.querySelector(`[name="cantidad_${i}"]`).value);
            const precio = numberFrom(document.querySelector(`[name="vr_unitario_${i}"]`).value);
            const bruto = (cant * precio) || 0;
            const brutoEl = document.getElementById('bruto_' + i);
            if (brutoEl) brutoEl.textContent = formatMoney(bruto);
            updateTotals();
        }catch(e){ console.error(e); }
    };

    function formatMoney(n){
        return '$' + Number(n||0).toLocaleString('es-CO', {minimumFractionDigits: 0, maximumFractionDigits: 2});
    }

    function updateTotals(){
        const rows = document.querySelectorAll('#items-tabla tr.item-row');
        let total = 0; let itemsCount = 0;
        rows.forEach(r=>{
            if (r.style.display === 'none') return;
            const idx = r.getAttribute('data-row');
            const brutoEl = document.getElementById('bruto_' + idx);
            const bruto = numberFrom(brutoEl ? brutoEl.textContent : 0);
            if (bruto > 0) itemsCount += 1;
            total += bruto;
        });
        const totalBrutoEl = el('#total-bruto');
        const totalPagarEl = el('#total-pagar');
        const itemsCountEl = el('#total-items-count');
        if (totalBrutoEl) totalBrutoEl.textContent = formatMoney(total);
        if (totalPagarEl) totalPagarEl.textContent = formatMoney(total);
        if (itemsCountEl) itemsCountEl.textContent = String(itemsCount);
    }

    // Cambiar cantidad de filas visibles
    window.onCantidadItemsChange = function(sel){
        const n = Number(sel.value||0);
        const rows = document.querySelectorAll('#items-tabla tr.item-row');
        rows.forEach(r=>{
            const idx = Number(r.getAttribute('data-row')) || 0;
            if (idx <= n) r.style.display = '';
            else r.style.display = 'none';
        });
        updateTotals();
    };

    // Limpiar formulario
    window.limpiarFormulario = function(){
        // reset inputs
        const inputs = document.querySelectorAll('#factura-alquiler input, #factura-alquiler textarea, #factura-alquiler select');
        inputs.forEach(i=>{
            if (i.type === 'checkbox' || i.type === 'radio') i.checked = false;
            else i.value = '';
        });
        // reset items
        const rows = document.querySelectorAll('#items-tabla tr.item-row');
        rows.forEach(r=>{
            const idx = r.getAttribute('data-row');
            const desc = document.querySelector(`[name="descripcion_${idx}"]`);
            const cant = document.querySelector(`[name="cantidad_${idx}"]`);
            const precio = document.querySelector(`[name="vr_unitario_${idx}"]`);
            if (desc) desc.value = ''; if (cant) cant.value = ''; if (precio) precio.value = '';
            const brutoEl = document.getElementById('bruto_' + idx); if (brutoEl) brutoEl.textContent = '$0';
        });
        updateTotals();
        showFacturaMessage('success','Formulario limpiado');
    };

    // Construir payload invoice según documentación
    function buildInvoicePayload(){
        const fecha = document.getElementById('factura-fecha')?.value || new Date().toISOString().slice(0,10);
        const cliente = {
            nombre: document.getElementById('cliente-nombre')?.value || '',
            nit: document.getElementById('cliente-nit')?.value || '',
            direccion: document.getElementById('cliente-direccion')?.value || '',
            telefono: document.getElementById('cliente-telefono')?.value || '',
            ciudad: document.getElementById('cliente-ciudad')?.value || ''
        };
        const orden_compra = document.getElementById('orden-compra')?.value || null;
        const observaciones = document.getElementById('observaciones')?.value || '';
        const items = [];
        const visibleRows = document.querySelectorAll('#items-tabla tr.item-row');
        visibleRows.forEach(r=>{
            if (r.style.display === 'none') return;
            const idx = r.getAttribute('data-row');
            const desc = document.querySelector(`[name="descripcion_${idx}"]`)?.value || '';
            const cant = numberFrom(document.querySelector(`[name="cantidad_${idx}"]`)?.value);
            const precio = numberFrom(document.querySelector(`[name="vr_unitario_${idx}"]`)?.value);
            if (!desc && (!cant || !precio)) return;
            items.push({ producto_id: null, desc: desc, cant: cant, precio: precio });
        });
        const total = numberFrom(document.getElementById('total-pagar')?.textContent);
        const medio = document.getElementById('medio-pago')?.value || 'efectivo';
        const metodos = [{ metodo: medio, valor: total }];

        const invoice = {
            tipo: 'evento',
            fecha,
            cliente,
            orden_compra,
            observaciones,
            mesa_id: null,
            estatus: 'pagada',
            total: total,
            cambio: 0,
            items,
            metodos
        };
        return invoice;
    }

    // Guardar factura (crear)
    window.guardarFactura = async function(){
        try{
            const invoice = buildInvoicePayload();
            if (!invoice.items || invoice.items.length === 0){ showFacturaMessage('error','Añade al menos un ítem'); return; }
            const res = await fetch(API_BASE + '/alquiler', {
                method: 'POST',
                headers: Object.assign({ 'Accept':'application/json', 'Content-Type':'application/json' }, token()? { 'Authorization':'Bearer ' + token() } : {}),
                body: JSON.stringify({ invoice })
            });
            if (res.status === 422){ const body = await res.json().catch(()=>null); showFacturaMessage('error', (body && body.message) || 'Payload inválido'); return; }
            if (res.status === 401){ showFacturaMessage('error','No autorizado'); return; }
            if (!res.ok){ const txt = await res.text().catch(()=>null); showFacturaMessage('error', txt || 'Error guardando factura'); return; }
            const body = await res.json().catch(()=>null);
            showFacturaMessage('success', (body && body.message) ? body.message : 'Factura creada');
            // set factura id/no if returned
            if (body && body.factura_id){ document.getElementById('factura-no').value = String(body.factura_id).padStart(4,'0'); document.getElementById('factura-no').setAttribute('data-factura-id', body.factura_id); }
        }catch(e){ console.error(e); showFacturaMessage('error','Error guardando factura'); }
    };

    // Imprimir factura: descarga PDF via fetch blob
    window.imprimirFactura = async function(){
        const id = document.getElementById('factura-no')?.getAttribute('data-factura-id');
        if (!id){ showFacturaMessage('error','Guarda la factura antes de imprimir'); return; }
            // Prepare printable values: show formatted spans and hide inputs during print
            try{
                const rows = document.querySelectorAll('#items-tabla tr.item-row');
                rows.forEach(r=>{
                    if (r.style.display === 'none' || r.classList.contains('hidden-row')) return;
                    const idx = r.getAttribute('data-row');
                    const cantInput = document.querySelector(`[name="cantidad_${idx}"]`);
                    const precioInput = document.querySelector(`[name="vr_unitario_${idx}"]`);
                    const cantVal = cantInput ? (cantInput.value || '') : '';
                    const precioVal = precioInput ? (precioInput.value || '') : '';
                    // ensure print spans exist
                    let cantSpan = r.querySelector('.print-value.cant');
                    if (!cantSpan){ cantSpan = document.createElement('span'); cantSpan.className = 'print-value cant'; if (cantInput && cantInput.parentNode) cantInput.parentNode.appendChild(cantSpan); }
                    cantSpan.textContent = cantVal;
                    let precioSpan = r.querySelector('.print-value.precio');
                    if (!precioSpan){ precioSpan = document.createElement('span'); precioSpan.className = 'print-value precio'; if (precioInput && precioInput.parentNode) precioInput.parentNode.appendChild(precioSpan); }
                    try{ precioSpan.textContent = formatMoney(Number(precioVal||0)); }catch(e){ precioSpan.textContent = precioVal; }
                    if (cantInput) cantInput.classList.add('hide-for-print');
                    if (precioInput) precioInput.classList.add('hide-for-print');
                });
            }catch(e){ console.debug('prepare print values error', e); }
            // Print and then revert print-only changes
            try{
                const doRestore = ()=>{
                    try{
                        const rows = document.querySelectorAll('#items-tabla tr.item-row');
                        rows.forEach(r=>{
                            const idx = r.getAttribute('data-row');
                            const cantInput = document.querySelector(`[name="cantidad_${idx}"]`);
                            const precioInput = document.querySelector(`[name="vr_unitario_${idx}"]`);
                            if (cantInput) cantInput.classList.remove('hide-for-print');
                            if (precioInput) precioInput.classList.remove('hide-for-print');
                            const cs = r.querySelectorAll('.print-value'); cs.forEach(n=>n.remove());
                        });
                    }catch(e){ console.debug('restore after print error', e); }
                };
                if ('onafterprint' in window){ window.onafterprint = doRestore; window.print(); }
                else { window.print(); setTimeout(doRestore, 500); }
            }catch(e){ console.error(e); }
        try{
            const res = await fetch(API_BASE + '/alquiler/' + id + '/pdf', { headers: Object.assign({ 'Accept':'application/pdf' }, token()? { 'Authorization':'Bearer ' + token() } : {}) });
            if (!res.ok){ showFacturaMessage('error','Error descargando PDF'); return; }
            const blob = await res.blob();
            const url = URL.createObjectURL(blob);
            window.open(url, '_blank');
            setTimeout(()=> URL.revokeObjectURL(url), 60000);
        }catch(e){ console.error(e); showFacturaMessage('error','Error imprimiendo factura'); }
    };

    // Init
    document.addEventListener('DOMContentLoaded', ()=>{
        // ensure initial totals
        updateTotals();
        // attach oninput to existing rows
        const rows = document.querySelectorAll('#items-tabla tr.item-row');
        rows.forEach(r=>{
            const idx = r.getAttribute('data-row');
            const cant = document.querySelector(`[name="cantidad_${idx}"]`);
            const precio = document.querySelector(`[name="vr_unitario_${idx}"]`);
            if (cant) cant.addEventListener('input', ()=> window.calcularFila(idx));
            if (precio) precio.addEventListener('input', ()=> window.calcularFila(idx));
        });
    });

    // Make sure inputs also have robust listeners (in case inline handlers don't fire)
    document.addEventListener('DOMContentLoaded', ()=>{
        document.querySelectorAll('.item-cant, .item-precio').forEach(el=>{
            el.removeEventListener('input', onRowInputFallback);
            el.addEventListener('input', onRowInputFallback);
        });
        const sel = document.getElementById('items-count-selector');
        if (sel) sel.addEventListener('change', ()=>{ window.onCantidadItemsChange(sel); });
    });

    // If a view invoice was passed via sessionStorage, load it into the form in read-only mode
    document.addEventListener('DOMContentLoaded', ()=>{
        try{
            const raw = sessionStorage.getItem('alquiler_view_invoice');
            if (!raw) return;
            const data = JSON.parse(raw);
            // populate main fields (support many possible invoice shapes)
            const facturaNoEl = document.getElementById('factura-no');
            if (data.numero_orden && facturaNoEl) facturaNoEl.value = data.numero_orden;
            if (data.fecha){ const f = document.getElementById('factura-fecha'); if (f) f.value = data.fecha; }
            // helper to try several keys
            function pick(...paths){
                for (const p of paths){
                    if (!p) continue;
                    // nested via dot
                    if (p.indexOf('.') > -1){
                        const parts = p.split('.'); let cur = data; let ok = true;
                        for (const part of parts){ if (cur && (part in cur)) cur = cur[part]; else { ok = false; break; } }
                        if (ok && cur != null) return cur;
                    } else {
                        if (p in data && data[p] != null) return data[p];
                        // also check data.cliente[p]
                        if (data.cliente && (p in data.cliente) && data.cliente[p] != null) return data.cliente[p];
                    }
                }
                return '';
            }
            const clienteNombre = pick('persona', 'persona_nombre', 'persona_name', 'cliente.nombre', 'cliente_nombre', 'cliente_name');
            const clienteNit = pick('cliente.nit', 'persona_nit', 'cliente_nit', 'nit');
            const clienteDir = pick('cliente.direccion', 'persona_direccion', 'cliente_direccion', 'direccion');
            const clienteTel = pick('cliente.telefono', 'persona_telefono', 'cliente_telefono', 'telefono');
            const clienteCiudad = pick('cliente.ciudad', 'persona_ciudad', 'cliente_ciudad', 'ciudad');
            if (clienteNombre){ const el = document.getElementById('cliente-nombre'); if (el) el.value = clienteNombre; }
            if (clienteNit){ const el = document.getElementById('cliente-nit'); if (el) el.value = clienteNit; }
            if (clienteDir){ const el = document.getElementById('cliente-direccion'); if (el) el.value = clienteDir; }
            if (clienteTel){ const el = document.getElementById('cliente-telefono'); if (el) el.value = clienteTel; }
            if (clienteCiudad){ const el = document.getElementById('cliente-ciudad'); if (el) el.value = clienteCiudad; }
            if (data.observaciones){ const ob = document.getElementById('observaciones'); if (ob) ob.value = data.observaciones; }
            if (data.orden_compra){ const oc = document.getElementById('orden-compra'); if (oc) oc.value = data.orden_compra; }
            // items
            const items = Array.isArray(data.productos) ? data.productos : (Array.isArray(data.items) ? data.items : []);
            let _viewMode = true;
            if (items && items.length){
                const sel = document.getElementById('items-count-selector');
                const n = Math.min(15, items.length);
                if (sel){ sel.value = String(n); window.onCantidadItemsChange(sel); }
                items.forEach((it, idx)=>{
                    const i = idx + 1;
                    const desc = document.querySelector(`[name="descripcion_${i}"]`);
                    const cant = document.querySelector(`[name="cantidad_${i}"]`);
                    const precio = document.querySelector(`[name="vr_unitario_${i}"]`);
                    if (desc) desc.value = it.descripcion || it.desc || it.nombre || '';
                    if (cant){ const v = it.cantidad || it.cant || it.qty || ''; cant.value = v !== undefined && v !== null ? String(v) : ''; cant.style.color = cant.style.color || '#fff'; cant.style.background = cant.style.background || 'transparent'; try{ cant.dispatchEvent(new Event('input',{bubbles:true})); }catch(e){} }
                    if (precio){ const v = it.precio || it.precio_unitario || it.valor || it.vr_unitario || ''; precio.value = v !== undefined && v !== null ? String(v) : ''; precio.style.color = precio.style.color || '#fff'; precio.style.background = precio.style.background || 'transparent'; try{ precio.dispatchEvent(new Event('input',{bubbles:true})); }catch(e){} }
                    try{ window.calcularFila(i); }catch(e){}
                });
                            // Make inputs readonly and style them for visibility (avoid DOM replacement issues)
                try{
                    for (let idx=0; idx<items.length; idx++){
                        const i = idx+1;
                        const cant = document.querySelector(`[name="cantidad_${i}"]`);
                        const precio = document.querySelector(`[name="vr_unitario_${i}"]`);
                                    if (cant){ cant.readOnly = true; cant.classList.add('readonly-input'); cant.style.color = cant.style.color || '#111'; cant.style.background = 'transparent'; }
                                    if (precio){ precio.readOnly = true; precio.classList.add('readonly-input'); precio.style.color = precio.style.color || '#111'; precio.style.background = 'transparent'; }
                    }
                }catch(e){ console.debug('replace inputs error', e); }
            }
            // Disable all inputs, textareas, selects to make them read-only
            document.querySelectorAll('input, textarea, select').forEach(el => {
                el.readOnly = true;
                if (el.tagName === 'SELECT') {
                    el.disabled = true;
                }
                el.style.pointerEvents = 'none';
                el.classList.add('readonly-input');
            });
            // hide limpiar/guardar buttons since this is a loaded factura
            document.querySelectorAll('button[onclick="limpiarFormulario()"], button[onclick="guardarFactura()"]').forEach(b=>{ b.style.display = 'none'; });
            // recalc totals
            updateTotals();
            // remove session key
            sessionStorage.removeItem('alquiler_view_invoice');
        }catch(e){ console.debug('No pending invoice to load', e); }
    });

    function onRowInputFallback(e){
        try{
            const name = e.target.name || '';
            const m = name.match(/_(\d+)$/);
            if (m) window.calcularFila(Number(m[1]));
        }catch(err){ console.error(err); }
    }
})();
