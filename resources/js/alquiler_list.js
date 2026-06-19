(() => {
  const API = (window.VITE_API_URL || window.API_BASE || '/api').replace(/\/$/, '');
  const token = () => localStorage.getItem('auth_token');

  function el(q){ return document.querySelector(q); }

  function formatMoney(n){ return '$' + Number(n||0).toLocaleString('es-CO', {minimumFractionDigits: 0}); }

  function renderRow(i, item){
    try{ console.debug('renderRow item', item); }catch(e){}
    let montoVal = item.monto_total || item.total || item.monto || 0;
    if (!montoVal || Number(montoVal) === 0){
      // try compute from productos/items if backend didn't provide total
      const prods = Array.isArray(item.productos) ? item.productos : (Array.isArray(item.items) ? item.items : []);
      if (prods && prods.length){
        montoVal = prods.reduce((s,it)=> s + (Number(it.precio || it.precio_unitario || it.vr_unitario || it.valor || 0) * Number(it.cantidad || it.cant || 1)), 0);
      }
    }
    try{ console.debug('renderRow montoVal ->', montoVal); }catch(e){}
    return `
      <tr class="border-b">
        <td class="p-3">${i}</td>
        <td class="p-3">${item.numero_orden || ''}</td>
        <td class="p-3">${item.fecha || ''}</td>
        <td class="p-3">${item.persona || item.cliente?.nombre || ''}</td>
        <td class="p-3 text-right">${formatMoney(montoVal)}</td>
        <td class="p-3 text-right">
          <button class="btn-view-alq px-3 py-1 rounded bg-primary text-on-primary" data-id="${item.id}">Ver</button>
          <button class="btn-del-alq ml-2 px-3 py-1 rounded bg-error text-on-primary" data-id="${item.id}">Eliminar</button>
        </td>
      </tr>`;
  }

  window.fetchAlquileres = async function(page = 1){
    const body = el('#alquileres-body');
    const countEl = el('#alquiler-count');
    const pagEl = el('#alquileres-pagination');
    if (body) body.innerHTML = '<tr><td colspan="6" class="p-6 text-center text-slate-400">Cargando…</td></tr>';
    try{
      const headers = { 'Accept':'application/json' };
      if (token()) headers['Authorization'] = 'Bearer ' + token();
      const res = await fetch(`${API}/alquiler?page=${page}`, { headers });
      if (res.status === 401){ if (body) body.innerHTML = '<tr><td colspan="6" class="p-6 text-center text-red-400">No autorizado</td></tr>'; return; }
      if (!res.ok) throw new Error('Error cargando alquileres');
      const data = await res.json();
      const list = Array.isArray(data.data) ? data.data : (data.data || data);
      const total = data.total || (data.meta && data.meta.total) || 0;
      const per_page = data.per_page || (data.meta && data.meta.per_page) || (list.length || 20);
      const current_page = data.current_page || (data.meta && data.meta.current_page) || page;
      if (countEl) countEl.textContent = String(total);
      if (!list || !list.length){ if (body) body.innerHTML = '<tr><td colspan="6" class="p-6 text-center text-slate-400">Sin resultados</td></tr>'; if (pagEl) pagEl.innerHTML = ''; return; }
      if (body) body.innerHTML = list.map((it, idx) => renderRow((current_page-1)*per_page + idx + 1, it)).join('');
      renderPagination(current_page, per_page, total);
      // attach handlers for dynamically created buttons
      attachRowButtons();
    }catch(err){ console.error(err); if (body) body.innerHTML = '<tr><td colspan="6" class="p-6 text-center text-red-400">Error cargando datos</td></tr>'; }
  };

  function attachRowButtons(){
    document.querySelectorAll('.btn-view-alq').forEach(b=>{
      b.removeEventListener('click', viewBtnHandler);
      b.addEventListener('click', viewBtnHandler);
    });
    document.querySelectorAll('.btn-del-alq').forEach(b=>{
      b.removeEventListener('click', delBtnHandler);
      b.addEventListener('click', delBtnHandler);
    });
  }

  function viewBtnHandler(e){ const id = e.currentTarget.getAttribute('data-id'); console.debug('viewBtnHandler clicked id=', id); if (id) viewAlquiler(id); }
  function delBtnHandler(e){ const id = e.currentTarget.getAttribute('data-id'); console.debug('delBtnHandler clicked id=', id); if (id) deleteAlquiler(id); }

  function renderPagination(current, perPage, total){
    const pagEl = el('#alquileres-pagination');
    if (!pagEl) return;
    const pages = Math.max(1, Math.ceil(total / perPage));
    let html = '';
    if (current > 1) html += `<button onclick="fetchAlquileres(${current-1})" class="px-3 py-1 rounded">«</button>`;
    for (let p=1;p<=pages;p++){ html += `<button onclick="fetchAlquileres(${p})" class="px-3 py-1 rounded ${p===current? 'bg-primary text-on-primary':''}">${p}</button>`; }
    if (current < pages) html += `<button onclick="fetchAlquileres(${current+1})" class="px-3 py-1 rounded">»</button>`;
    pagEl.innerHTML = html;
  }

  function buildInvoiceHtml(data){
    const clienteNombre = data.persona || (data.cliente && (data.cliente.nombre || data.cliente.nombre_completo)) || '';
    const fecha = data.fecha || '';
    const items = Array.isArray(data.productos) ? data.productos : (Array.isArray(data.items) ? data.items : []);
    const total = data.monto_total || data.total || items.reduce((s,it)=> s + (Number(it.precio || it.precio_unitario || it.valor || 0) * Number(it.cantidad || it.cant || 1)), 0);
    let html = `
      <div class="invoice-card bg-transparent">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-0 border-b border-white/10 pb-4 mb-4">
          <div class="flex items-center gap-4">
            <div class="w-20 h-20 rounded overflow-hidden bg-black border">
              <img src="/img/logo.png" alt="Esencia Retro" class="w-full h-full object-contain p-1">
            </div>
            <div>
              <div class="text-white font-black">ESENCIA RETRO</div>
              <div class="text-xs text-on-surface-variant">NIT: 1,007,450,540</div>
              <div class="text-xs text-on-surface-variant">Tel: 3162218491</div>
              <div class="text-xs text-on-surface-variant">Bogotá</div>
            </div>
          </div>
          <div class="text-right">
            <h2 class="text-primary font-black uppercase">Factura de Venta</h2>
            <div class="text-sm">No. <strong>${data.numero_orden || data.id || ''}</strong></div>
            <div class="text-sm">Fecha: ${fecha}</div>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
          <div>
            <div class="text-xs text-on-surface-variant font-black">Señores</div>
            <div class="text-white font-semibold">${clienteNombre}</div>
          </div>
          <div class="text-right">
            <div class="text-xs text-on-surface-variant font-black">Teléfono</div>
            <div class="text-white">${data.persona_telefono || (data.cliente && data.cliente.telefono) || ''}</div>
          </div>
        </div>

        <div class="overflow-x-auto mb-4">
          <table class="w-full text-sm">
            <thead>
              <tr class="text-on-surface-variant text-xs">
                <th class="p-2 text-left">Descripción</th>
                <th class="p-2 text-right">Cant.</th>
                <th class="p-2 text-right">Vr. Unit.</th>
                <th class="p-2 text-right">Total</th>
              </tr>
            </thead>
            <tbody>
    `;
    items.forEach(it=>{
      const desc = it.descripcion || it.desc || it.nombre || '';
      const cant = it.cantidad || it.cant || 1;
      const unit = it.precio || it.precio_unitario || it.valor || it.vr_unitario || 0;
      const lineTotal = (Number(cant) * Number(unit)) || 0;
      html += `<tr class="border-b"><td class="p-2">${desc}</td><td class="p-2 text-right">${cant}</td><td class="p-2 text-right">${formatMoney(unit)}</td><td class="p-2 text-right">${formatMoney(lineTotal)}</td></tr>`;
    });
    html += `</tbody></table></div>`;

    html += `<div class="grid grid-cols-1 md:grid-cols-2 gap-4"><div class="text-xs text-on-surface-variant">Observaciones</div><div class="text-right font-bold text-white">Total: ${formatMoney(total)}</div></div>`;

    if (data.metodos_pago && data.metodos_pago.length){
      html += `<div class="mt-3"><h4 class="font-bold">Métodos de pago</h4>`;
      data.metodos_pago.forEach(m=>{ html += `<div class="text-sm">${m.metodo || m.descripcion}: ${m.valor != null ? formatMoney(m.valor) : '-'}</div>`; });
      html += `</div>`;
    }

    if (data.observaciones){ html += `<div class="mt-3 text-sm text-on-surface-variant">${data.observaciones}</div>`; }

    return html + `</div>`;
  }

  window.deleteAlquiler = async function(id){
    if (!confirm('Eliminar factura de alquiler?')) return;
    try{
      const headers = { 'Accept':'application/json' };
      if (token()) headers['Authorization'] = 'Bearer ' + token();
      const res = await fetch(`${API}/alquiler/${id}`, { method: 'DELETE', headers });
      if (!res.ok){ const txt = await res.text().catch(()=>null); alert(txt || 'Error eliminando'); return; }
      fetchAlquileres(1);
    }catch(e){ console.error(e); alert('Error eliminando'); }
  };

  // view detail in modal
  const _loadingAlq = new Set();
  window.viewAlquiler = async function(id){
    if (!id) return;
    if (_loadingAlq.has(id)) { console.debug('viewAlquiler: already loading id=', id); return; }
    _loadingAlq.add(id);
    const modal = document.getElementById('modal-alquiler-detail');
    const body = document.getElementById('modal-alq-body');
    const title = document.getElementById('modal-alq-title');
    console.debug('viewAlquiler invoked with id=', id);
    if (!modal || !body) { _loadingAlq.delete(id); return; }
    // ensure visible and top-most
    // Attach modal to document.body to avoid parent layout collapsing it
    try{ if (modal.parentElement !== document.body) document.body.appendChild(modal); }catch(e){ console.debug('appendModal error', e); }
    modal.classList.remove('hidden');
    modal.style.zIndex = 99999;
    body.innerHTML = '<p class="text-sm text-slate-400">Cargando...</p>';
    try{
      const headers = { 'Accept':'application/json' };
      if (token()) headers['Authorization'] = 'Bearer ' + token();
      console.debug('Fetching', `${API}/alquiler/${id}`);
      const res = await fetch(`${API}/alquiler/${id}`, { headers });
      console.debug('Fetch status', res.status);
      if (!res.ok){
        const txt = await res.text().catch(()=>null);
        body.innerHTML = `<div class="text-sm text-red-400">Error cargando detalle (status ${res.status})<pre class="mt-2 text-xs whitespace-pre-wrap">${txt || 'Sin cuerpo'}</pre></div>`;
        _loadingAlq.delete(id);
        return;
      }
      let data = null;
      try{ data = await res.json(); }catch(parseErr){ const txt = await res.text().catch(()=>null); body.innerHTML = `<div class="text-sm text-red-400">Respuesta inválida JSON<pre class="mt-2 text-xs whitespace-pre-wrap">${txt || 'Sin cuerpo'}</pre></div>`; _loadingAlq.delete(id); return; }
      // Instead of showing a modal, open the full Alquiler form and load the data there.
      try{
        sessionStorage.setItem('alquiler_view_invoice', JSON.stringify(data));
        // navigate to alquiler form page; server route renders the form
        window.location.href = '/admin/alquiler?view=1';
        _loadingAlq.delete(id);
        return;
      }catch(e){ console.debug('redirect to alquiler form failed', e); }
    }catch(err){ console.error(err); body.innerHTML = '<p class="text-sm text-red-400">Error cargando detalle</p>'; }
  };

  window.closeAlquilerModal = function(){
    const modal = document.getElementById('modal-alquiler-detail');
    if (modal) modal.classList.add('hidden');
  };

  document.addEventListener('DOMContentLoaded', ()=>{ if (document.getElementById('alquileres-body')) fetchAlquileres(1); });
  // Delegated click handler: captura Ver/Eliminar incluso si botones se re-renderizan
  document.addEventListener('DOMContentLoaded', ()=>{
    const tbody = document.getElementById('alquileres-body');
    if (!tbody) return;
    tbody.addEventListener('click', (ev)=>{
      const v = ev.target.closest && ev.target.closest('.btn-view-alq');
      if (v){ const id = v.getAttribute('data-id'); if (id) viewAlquiler(id); return; }
      const d = ev.target.closest && ev.target.closest('.btn-del-alq');
      if (d){ const id = d.getAttribute('data-id'); if (id) deleteAlquiler(id); return; }
    });
  });

})();
