/* ============================================================
   Alquiler de Salón — Lógica JavaScript dedicada
   ============================================================ */

/** Número máximo de filas disponibles en la tabla */
const MAX_ROWS = 15;

/** Número de filas actualmente visibles */
let activeRows = 5;

// ----------------------------------------------------------------
// Inicialización
// ----------------------------------------------------------------
document.addEventListener('DOMContentLoaded', () => {
    // Fecha de hoy (solo si está vacía)
    const today = new Date().toISOString().split('T')[0];
    const fechaEl = document.getElementById('factura-fecha');
    if (fechaEl && (!fechaEl.value || fechaEl.value === '')) {
        fechaEl.value = today;
    }

    // El consecutivo lo pre-llena el servidor y es readonly

    // Leer selector de cantidad de filas
    const itemsSelector = document.getElementById('items-count-selector');
    if (itemsSelector) {
        activeRows = parseInt(itemsSelector.value, 10) || activeRows;
    }
    // Aplica la cantidad de filas
    aplicarCantidadFilas(activeRows);

    // Asegura listeners en inputs de cantidad y precio para cálculos
    document.querySelectorAll('.item-cant, .item-precio').forEach(el => {
        el.removeEventListener('input', onRowInput);
        el.addEventListener('input', onRowInput);
    });

    // También recalcula al cambiar el selector de cantidad de ítems
    const sel = document.getElementById('items-count-selector');
    if (sel) sel.addEventListener('change', () => { onCantidadItemsChange(sel); });

    // Inicializar display del medio de pago
    const medioSelect = document.getElementById('medio-pago');
    const displayMedio = document.getElementById('display-medio-pago');
    if (medioSelect && displayMedio) {
        displayMedio.textContent = medioSelect.options[medioSelect.selectedIndex]?.text?.toUpperCase() || '';
        medioSelect.addEventListener('change', () => {
            displayMedio.textContent = medioSelect.options[medioSelect.selectedIndex]?.text?.toUpperCase() || '';
        });
    }

    // Attach product select and dropdown autocomplete handlers
    attachProductHandlers();
});

/** Attach autocomplete and event handlers to products dropdown */
function attachProductHandlers() {
    const dropdowns = document.querySelectorAll('.product-dropdown');
    if (window.PRODUCTS_DATA && !window.PRODUCTS_MAP) {
        window.PRODUCTS_MAP = {};
        window.PRODUCTS_DATA.forEach(p => { window.PRODUCTS_MAP[p.nombre] = p; });
    }

    dropdowns.forEach(inp => {
        if (inp._attached) return; inp._attached = true;
        inp.addEventListener('input', function(e) {
            const name = (this.value || '').toString();
            const rowNum = this.dataset.row;
            selectProductByName(rowNum, name);
        });
        inp.addEventListener('change', function(e) {
            const name = (this.value || '').toString();
            const rowNum = this.dataset.row;
            selectProductByName(rowNum, name);
        });
        // If prefilled (editing), trigger selection
        if (inp.value && inp.value.trim() !== '') {
            selectProductByName(inp.dataset.row, inp.value.trim());
        }
    });

    attachDescriptionHandlers();
}

/** Find product by name and fill row fields (id, price, desc) */
function selectProductByName(rowNum, name) {
    if (!name) return;
    const prod = (window.PRODUCTS_MAP && window.PRODUCTS_MAP[name]) || (window.PRODUCTS_DATA && window.PRODUCTS_DATA.find(p => p.nombre === name));
    const row = document.querySelector(`[data-row="${rowNum}"]`);
    if (!row) return;
    const descInput = row.querySelector('.item-desc');
    const pidInput = row.querySelector('.item-product-id');
    const priceInput = row.querySelector('.item-precio');
    if (prod) {
        if (descInput && (!descInput.value || descInput.value.trim() === '')) descInput.value = prod.nombre;
        if (pidInput) pidInput.value = prod.id;
        if (priceInput) priceInput.value = prod.precio;
        row._manualDesc = false;
    } else {
        if (pidInput) pidInput.value = '';
        row._manualDesc = false;
    }
    calcularFila(parseInt(rowNum, 10));
}

// When user edits the description manually, clear product_id so backend stores null
function attachDescriptionHandlers() {
    const descs = document.querySelectorAll('.item-desc');
    descs.forEach(inp => {
        if (inp._attachedDesc) return; inp._attachedDesc = true;
        inp.addEventListener('input', function(e){
            const row = this.closest('tr.item-row');
            if (!row) return;
            row._manualDesc = true;
            const pidInput = row.querySelector('.item-product-id');
            if (pidInput) pidInput.value = '';
        });
    });
}

// If a view invoice was passed via sessionStorage (from list view), load it into the form
document.addEventListener('DOMContentLoaded', () => {
    try{
        const raw = sessionStorage.getItem('alquiler_view_invoice');
        if (!raw) return;
        const data = JSON.parse(raw);
        // main fields
        const fno = document.getElementById('factura-no'); if (data.numero_orden && fno) fno.value = data.numero_orden;
        const ff = document.getElementById('factura-fecha'); if (data.fecha && ff) ff.value = data.fecha;

        function pick(...paths){
            for (const p of paths){
                if (!p) continue;
                if (p.indexOf('.') > -1){
                    const parts = p.split('.'); let cur = data; let ok = true;
                    for (const part of parts){ if (cur && (part in cur)) cur = cur[part]; else { ok = false; break; } }
                    if (ok && cur != null) return cur;
                } else {
                    if (p in data && data[p] != null) return data[p];
                    if (data.cliente && (p in data.cliente) && data.cliente[p] != null) return data.cliente[p];
                }
            }
            return '';
        }
        const clienteNombre = pick('persona','persona_nombre','persona_name','cliente.nombre','cliente_nombre','cliente_name');
        const clienteNit = pick('cliente.nit','persona_nit','cliente_nit','nit');
        const clienteDir = pick('cliente.direccion','persona_direccion','cliente_direccion','direccion');
        const clienteTel = pick('cliente.telefono','persona_telefono','cliente_telefono','telefono');
        const clienteCiudad = pick('cliente.ciudad','persona_ciudad','cliente_ciudad','ciudad');
        if (clienteNombre){ const el = document.getElementById('cliente-nombre'); if (el) el.value = clienteNombre; }
        if (clienteNit){ const el = document.getElementById('cliente-nit'); if (el) el.value = clienteNit; }
        if (clienteDir){ const el = document.getElementById('cliente-direccion'); if (el) el.value = clienteDir; }
        if (clienteTel){ const el = document.getElementById('cliente-telefono'); if (el) el.value = clienteTel; }
        if (clienteCiudad){ const el = document.getElementById('cliente-ciudad'); if (el) el.value = clienteCiudad; }
        if (data.observaciones){ const ob = document.getElementById('observaciones'); if (ob) ob.value = data.observaciones; }
        if (data.orden_compra){ const oc = document.getElementById('orden-compra'); if (oc) oc.value = data.orden_compra; }
        // items
        const items = Array.isArray(data.productos) ? data.productos : (Array.isArray(data.items) ? data.items : []);
        if (items && items.length){
            aplicarCantidadFilas(Math.min(15, items.length));
            items.forEach((it, idx)=>{
                const i = idx + 1;
                const desc = document.querySelector(`[name="descripcion_${i}"]`);
                const cant = document.querySelector(`[name="cantidad_${i}"]`);
                const precio = document.querySelector(`[name="vr_unitario_${i}"]`);
                const pidInput = document.querySelector(`[name="producto_id_${i}"]`);
                if (pidInput) pidInput.value = it.producto_id || '';
                if (desc) desc.value = it.descripcion || it.desc || it.nombre || '';
                if (cant){ const v = it.cantidad || it.cant || it.qty || ''; cant.value = v !== undefined && v !== null ? String(v) : ''; }
                if (precio){ const v = it.precio || it.precio_unitario || it.valor || it.vr_unitario || ''; precio.value = v !== undefined && v !== null ? String(v) : ''; }
                try{ calcularFila(i); }catch(e){}
            });
            // Replace inputs with readonly spans so values are always visible
            try{
                for (let idx=0; idx<items.length; idx++){
                    const i = idx+1;
                    const cant = document.querySelector(`[name="cantidad_${i}"]`);
                    const precio = document.querySelector(`[name="vr_unitario_${i}"]`);
                    if (cant){ cant.readOnly = true; cant.classList.add('readonly-input'); }
                    if (precio){ precio.readOnly = true; precio.classList.add('readonly-input'); }
                }
            }catch(e){}
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
        // hide save/clear buttons
        document.querySelectorAll('button[onclick="limpiarFormulario()"], button[onclick="guardarFactura()"]').forEach(b=>{ b.style.display = 'none'; });
        // recalc totals
        recalcularTotales();
        sessionStorage.removeItem('alquiler_view_invoice');
    }catch(e){}
});

// ----------------------------------------------------------------
// Control de cantidad de ítems
// ----------------------------------------------------------------

/**
 * Llamado cuando cambia el selector de cantidad de ítems.
 * @param {HTMLSelectElement} selectEl
 */
function onCantidadItemsChange(selectEl) {
    activeRows = parseInt(selectEl.value, 10);
    aplicarCantidadFilas(activeRows);
}

/**
 * Muestra las primeras `n` filas y oculta el resto.
 * @param {number} n
 */
function aplicarCantidadFilas(n) {
    for (let i = 1; i <= MAX_ROWS; i++) {
        const row = document.querySelector(`[data-row="${i}"]`);
        if (!row) continue;
        if (i <= n) {
            row.classList.remove('hidden-row');
        } else {
            row.classList.add('hidden-row');
            // Limpia valores de filas ocultas para no afectar totales
            row.querySelectorAll('input').forEach(inp => inp.value = '');
            const brutoEl = document.getElementById(`bruto_${i}`);
            if (brutoEl) brutoEl.textContent = '$0';
        }
    }
    recalcularTotales();
}

// ----------------------------------------------------------------
// Cálculos
// ----------------------------------------------------------------

/** Formatea un número como moneda COP */
function formatCOP(num) {
    if (!num || isNaN(num)) return '$0';
    return '$' + parseFloat(num).toLocaleString('es-CO', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    });
}

/**
 * Recalcula el Vr. Bruto de una fila específica.
 * Fórmula: cant × precio = bruto
 * @param {number} rowNum
 */
function calcularFila(rowNum) {
    const row = document.querySelector(`[data-row="${rowNum}"]`);
    if (!row) return;

    const cant   = parseFloat(row.querySelector('.item-cant').value)   || 0;
    const precio = parseFloat(row.querySelector('.item-precio').value) || 0;

    const bruto = cant * precio;
    const brutoEl = document.getElementById(`bruto_${rowNum}`);
    if (brutoEl) brutoEl.textContent = formatCOP(bruto > 0 ? bruto : 0);

    recalcularTotales();
}

/** Handler reutilizable para inputs de fila */
function onRowInput(e){
    try{
        const row = e.target.closest('tr[item-row], tr[data-row]');
        let idx = null;
        if (row) idx = row.getAttribute('data-row');
        if (!idx && e.target.name){
            const m = e.target.name.match(/_(\d+)$/);
            if (m) idx = m[1];
        }
        if (idx) calcularFila(Number(idx));
    }catch(err){ console.error(err); }
}

/** Recalcula los totales generales del formulario */
function recalcularTotales() {
    let totalBruto  = 0;
    let itemsCount  = 0;

    for (let i = 1; i <= activeRows; i++) {
        const row = document.querySelector(`[data-row="${i}"]`);
        if (!row || row.classList.contains('hidden-row')) continue;

        const cant   = parseFloat(row.querySelector('.item-cant').value)   || 0;
        const precio = parseFloat(row.querySelector('.item-precio').value) || 0;

        if (cant > 0 || precio > 0) itemsCount++;

        const bruto = cant * precio;
        totalBruto += bruto > 0 ? bruto : 0;
    }

    document.getElementById('total-items-count').textContent = itemsCount;
    document.getElementById('total-bruto').textContent       = formatCOP(totalBruto);
    document.getElementById('total-pagar').textContent       = formatCOP(totalBruto);
    document.getElementById('valor-letras').textContent      = totalBruto > 0
        ? numeroALetras(totalBruto)
        : '—';
}

// ----------------------------------------------------------------
// Número a Letras (Español colombiano)
// ----------------------------------------------------------------

function numeroALetras(num) {
    const millones  = ['','un millón','dos millones','tres millones','cuatro millones','cinco millones',
                       'seis millones','siete millones','ocho millones','nueve millones'];
    const miles     = ['','mil','dos mil','tres mil','cuatro mil','cinco mil','seis mil','siete mil',
                       'ocho mil','nueve mil','diez mil','once mil','doce mil','trece mil','catorce mil',
                       'quince mil','dieciséis mil','diecisiete mil','dieciocho mil','diecinueve mil'];
    const centenas  = ['','cien','doscientos','trescientos','cuatrocientos','quinientos',
                       'seiscientos','setecientos','ochocientos','novecientos'];
    const decenas   = ['','diez','veinte','treinta','cuarenta','cincuenta','sesenta','setenta','ochenta','noventa'];
    const unidades  = ['','uno','dos','tres','cuatro','cinco','seis','siete','ocho','nueve',
                       'diez','once','doce','trece','catorce','quince','dieciséis','diecisiete',
                       'dieciocho','diecinueve'];

    num = Math.floor(num);
    if (num === 0) return 'cero pesos';
    if (num >= 1000000) {
        const m = Math.floor(num / 1000000);
        const r = num % 1000000;
        return millones[m] + (r > 0 ? ' ' + numeroALetras(r).replace(' pesos', '') : '') + ' pesos';
    }
    if (num >= 1000) {
        const m = Math.floor(num / 1000);
        const r = num % 1000;
        let t;
        if (m < 20)       t = miles[m];
        else if (m < 100) t = decenas[Math.floor(m / 10)] + (m % 10 > 0 ? ' y ' + unidades[m % 10] : '') + ' mil';
        else              t = centenas[Math.floor(m / 100)] + ' ' + (m % 100 > 0 ? numeroALetras(m % 100).replace(' pesos', '') + ' ' : '') + 'mil';
        return t + (r > 0 ? ' ' + numeroALetras(r).replace(' pesos', '') : '') + ' pesos';
    }
    if (num >= 100) {
        return centenas[Math.floor(num / 100)]
            + (num % 100 > 0 ? ' ' + numeroALetras(num % 100).replace(' pesos', '') : '')
            + ' pesos';
    }
    if (num >= 20) {
        return decenas[Math.floor(num / 10)]
            + (num % 10 > 0 ? ' y ' + unidades[num % 10] : '')
            + ' pesos';
    }
    return unidades[num] + ' pesos';
}

// ----------------------------------------------------------------
// Acciones del formulario
// ----------------------------------------------------------------

/** Limpia todos los campos del formulario */
function limpiarFormulario() {
    document.querySelectorAll('#items-tabla input').forEach(inp => inp.value = '');
    for (let i = 1; i <= MAX_ROWS; i++) {
        const el = document.getElementById(`bruto_${i}`);
        if (el) el.textContent = '$0';
    }

    ['cliente-nombre', 'cliente-nit', 'cliente-direccion',
     'cliente-telefono', 'cliente-ciudad', 'observaciones', 'orden-compra']
        .forEach(id => {
            const el = document.getElementById(id);
            if (el) el.value = '';
        });

    recalcularTotales();
}

/** Muestra un toast de confirmación al guardar */
function guardarFactura() {
    // Construir el objeto invoice
    const invoice = {
        no: document.getElementById('factura-no')?.value || '',
        fecha: document.getElementById('factura-fecha')?.value || '',
        cliente: {
            nombre: document.getElementById('cliente-nombre')?.value || '',
            nit: document.getElementById('cliente-nit')?.value || '',
            direccion: document.getElementById('cliente-direccion')?.value || '',
            telefono: document.getElementById('cliente-telefono')?.value || '',
            ciudad: document.getElementById('cliente-ciudad')?.value || '',
        },
        items: [],
        observaciones: document.getElementById('observaciones')?.value || '',
        total: 0,
        medio_pago: document.getElementById('medio-pago')?.value || '',
        orden_compra: document.getElementById('orden-compra')?.value || ''
    };

    const rows = document.querySelectorAll('#items-tabla tr.item-row');
    rows.forEach((row) => {
        if (row.classList.contains('hidden-row')) return;
        const desc = row.querySelector('.item-desc')?.value || '';
        const cant = parseFloat(row.querySelector('.item-cant')?.value) || 0;
        const precio = parseFloat(row.querySelector('.item-precio')?.value) || 0;
        if (!desc && cant === 0 && precio === 0) return;
        const pid = row.querySelector('.item-product-id')?.value || null;
        invoice.items.push({ desc: desc, cant: cant, precio: precio, producto_id: pid });
        invoice.total += cant * precio;
    });

    const facturaIdEl = document.getElementById('factura-id');
    const isEditing = (facturaIdEl && facturaIdEl.dataset.editing === '1') || window.ALQUILER_EDITING === true || !!window.ALQUILER_FACTURA_ID;
    if (isEditing) {
        invoice.factura_id = facturaIdEl?.value || window.ALQUILER_FACTURA_ID || null;
    }

    // Validaciones
    const errors = [];

    if (!invoice.fecha || invoice.fecha.trim() === '') {
        errors.push('Fecha de la factura (campo Fecha)');
    }
    if (!invoice.cliente || !invoice.cliente.nombre || invoice.cliente.nombre.trim() === '') {
        errors.push('Nombre del cliente');
    }
    if (!invoice.cliente || !invoice.cliente.nit || invoice.cliente.nit.trim() === '') {
        errors.push('NIT / Cédula del cliente');
    }
    if (!invoice.medio_pago || invoice.medio_pago.trim() === '') {
        errors.push('Medio de pago');
    }

    let validItemCount = 0;
    invoice.items.forEach((it, idx) => {
        const desc = (it.desc || '').toString().trim();
        const cant = parseFloat(it.cant) || 0;
        const precio = parseFloat(it.precio) || 0;
        if (desc === '' && cant === 0 && precio === 0) {
            return;
        }
        if (desc === '' || cant <= 0 || precio <= 0) {
            errors.push(`Ítem ${idx + 1}: completar descripción, cantidad (>0) y precio (>0)`);
        } else {
            validItemCount++;
        }
    });

    if (validItemCount === 0) {
        errors.push('Agregar al menos un ítem con descripción, cantidad y precio válidos');
    }

    if (errors.length > 0) {
        alert('Corrige los siguientes campos antes de guardar:\n\n- ' + errors.join('\n- '));
        return;
    }

    const confirmMsg = isEditing ? '¿Desea actualizar esta factura?' : '¿Desea crear la factura de alquiler?';
    if (!window.confirm(confirmMsg)) {
        return;
    }

    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    const url = isEditing ? '/admin/alquiler/update' : '/admin/alquiler/store';
    
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrf || ''
        },
        body: JSON.stringify({ invoice })
    })
    .then(r => {
        if (!r.ok) {
            return r.json().then(data => { throw new Error(data.message || 'Error guardando factura'); });
        }
        return r.json();
    })
    .then(data => {
        showToast(data.message || 'Factura guardada');
        const redirectUrl = data.redirect || (!isEditing ? '/admin/alquiler/list' : null);
        if (redirectUrl) {
            setTimeout(() => { window.location.href = redirectUrl; }, 800);
        }
    })
    .catch(err => {
        console.error('Error guardando factura:', err);
        showToast(err.message || 'Error al guardar factura', true);
    });
}

function showToast(message, isError){
    const toast = document.createElement('div');
    toast.className = 'alquiler-toast';
    if (isError){ toast.style.background = '#b91c1c'; toast.style.color = '#fff'; }
    toast.innerHTML = '<span class="material-symbols-outlined" style="font-variation-settings:\'FILL\' 1;font-size:1.2rem">' + (isError? 'error' : 'check_circle') + '</span> ' + message;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3500);
}

/** Lanza el diálogo de impresión del navegador */
function imprimirFactura() {
    // Prepare printable values: show formatted spans and hide inputs during print
    try{
        const rows = document.querySelectorAll('#items-tabla tr.item-row');
        rows.forEach(r=>{
            if (r.classList.contains('hidden-row')) return;
            const idx = r.getAttribute('data-row');
            const cantInput = document.querySelector(`[name="cantidad_${idx}"]`);
            const precioInput = document.querySelector(`[name="vr_unitario_${idx}"]`);
            const cantVal = cantInput ? (cantInput.value || '') : '';
            const precioVal = precioInput ? (precioInput.value || '') : '';
            let cantSpan = r.querySelector('.print-value.cant');
            if (!cantSpan){ cantSpan = document.createElement('span'); cantSpan.className = 'print-value cant'; if (cantInput && cantInput.parentNode) cantInput.parentNode.appendChild(cantSpan); }
            cantSpan.textContent = cantVal;
            let precioSpan = r.querySelector('.print-value.precio');
            if (!precioSpan){ precioSpan = document.createElement('span'); precioSpan.className = 'print-value precio'; if (precioInput && precioInput.parentNode) precioInput.parentNode.appendChild(precioSpan); }
            try{ precioSpan.textContent = formatCOP(Number(precioVal||0)); }catch(e){ precioSpan.textContent = precioVal; }
            if (cantInput) cantInput.classList.add('hide-for-print');
            if (precioInput) precioInput.classList.add('hide-for-print');
        });
    }catch(e){ console.debug('prepare print values error', e); }
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
}

// --- EXPORTAR FUNCIONES AL OBJETO GLOBAL WINDOW ---
window.onCantidadItemsChange = onCantidadItemsChange;
window.calcularFila = calcularFila;
window.limpiarFormulario = limpiarFormulario;
window.guardarFactura = guardarFactura;
window.imprimirFactura = imprimirFactura;
window.editarFactura = function() { guardarFactura(); };
