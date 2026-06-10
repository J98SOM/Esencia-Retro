@extends('layouts.admin')

@section('title','Cocina - Pedidos')

@section('content')
<div class="p-8">
    <h1 class="text-2xl font-bold mb-4">Cocina — Pedidos</h1>
    <p class="text-sm text-slate-500 mb-6">Lista de pedidos para preparar. Marca el pedido como "Preparando" o "Listo".</p>

    <div id="kitchen-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4"></div>
</div>

<script>
(function(){
    const listEl = document.getElementById('kitchen-list');
    async function fetchOrders(){
        const token = localStorage.getItem('auth_token');
        if (!token) { listEl.innerHTML = '<p class="text-sm text-red-400">No autorizado</p>'; return; }
        const res = await fetch('/api/kitchen/orders', { headers: { 'Authorization': 'Bearer '+token } });
        if (!res.ok) { listEl.innerHTML = '<p class="text-sm text-red-400">Error cargando pedidos</p>'; return; }
        const json = await res.json();
        render(json.orders || []);
    }

    function render(orders){
        if (!orders.length) { listEl.innerHTML = '<p class="text-sm text-slate-400">No hay pedidos</p>'; return; }
        listEl.innerHTML = orders.map(o=>{
            const items = (o.items||[]).map(i=>`<div class="text-sm text-slate-300">${i.qty} × ${i.name}</div>`).join('');
            const statusColor = o.status === 'pending' ? 'text-amber-400' : (o.status === 'preparing' ? 'text-cyan-400' : 'text-emerald-400');
            return `
                <div class="p-4 rounded-xl bg-surface-container-high border border-white/5">
                    <div class="flex justify-between items-center mb-2">
                        <div>
                            <div class="font-bold text-white">Pedido #${o.id}</div>
                            <div class="text-xs text-slate-500">Mesa: ${o.table} • ${o.created_at}</div>
                        </div>
                        <div class="text-xs ${statusColor} uppercase font-bold">${o.status}</div>
                    </div>
                    <div class="mb-3">${items}</div>
                    <div class="flex gap-2">
                        <button data-action="preparing" data-id="${o.id}" class="py-2 px-3 rounded-lg bg-primary/10 text-primary hover:bg-primary/20">Marcar Preparando</button>
                        <button data-action="ready" data-id="${o.id}" class="py-2 px-3 rounded-lg bg-emerald-600/10 text-emerald-400 hover:bg-emerald-600/20">Marcar Listo</button>
                    </div>
                </div>
            `;
        }).join('');

        // attach handlers
        listEl.querySelectorAll('button[data-action]').forEach(b=>{
            b.addEventListener('click', async (ev)=>{
                const id = b.getAttribute('data-id');
                const action = b.getAttribute('data-action');
                const status = action === 'preparing' ? 'preparing' : 'ready';
                await updateStatus(id, status);
            });
        });
    }

    async function updateStatus(id, status){
        const token = localStorage.getItem('auth_token');
        try {
            const res = await fetch('/api/kitchen/orders/'+id+'/status', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Authorization': 'Bearer '+token },
                body: JSON.stringify({ status })
            });
            if (!res.ok) throw new Error('error');
            const json = await res.json();
            render(json.orders || []);
        } catch(e) {
            alert('No se pudo cambiar el estado');
        }
    }

    fetchOrders();
    // poll every 8 seconds
    setInterval(fetchOrders, 8000);
})();
</script>

@endsection
