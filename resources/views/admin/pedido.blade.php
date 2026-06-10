@extends('layouts.admin')

@section('title', 'Esencia Retro - Detalle de Mesa')

@section('content')
<div class="p-8 flex-1">
    <!-- Header Section -->
    <header class="flex justify-between items-center mb-10">
        <div>
            <nav class="flex items-center space-x-2 text-xs text-on-surface-variant mb-2">
                <span>Gestión</span>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-primary hover:underline"><a href="{{ route('admin.mesas') }}">Mesas</a></span>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-primary">Mesa {{ $mesaId ?? '00' }}</span>
            </nav>
            <h2 class="text-4xl font-extrabold tracking-tight text-white flex items-center gap-4">
                Pedido en Curso 
                <span class="px-3 py-1 bg-primary/20 text-primary rounded-lg text-sm uppercase tracking-widest font-black">
                    Mesa {{ $mesaId ?? '00' }}
                </span>
            </h2>
            <p class="text-on-surface-variant text-sm mt-1">Añade o modifica los productos ordenados en esta mesa.</p>
        </div>
        <div class="flex items-center gap-4">
            <button onclick="alert('Enviando instrucción a la impresora térmica...')" class="bg-surface-container-high hover:bg-surface-bright text-on-surface p-3 rounded-xl transition-colors font-bold flex items-center gap-2">
                <span class="material-symbols-outlined">print</span>
                Imprimir Pre-cuenta
            </button>
            <button onclick="window.location.href='{{ route('admin.checkout', ['id' => $mesaId ?? '4']) }}'" class="primary-gradient text-on-primary p-3 rounded-xl transition-transform hover:scale-95 font-bold flex items-center gap-2 shadow-lg shadow-primary/20">
                <span class="material-symbols-outlined">check_circle</span>
                Cerrar Mesa
            </button>
        </div>
    </header>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
        <!-- Order List (Left/Main) -->
        <div class="lg:col-span-2 bg-surface-container-low rounded-2xl p-8 border border-white/5 flex flex-col h-full">
            <div class="flex justify-between items-center mb-6">
                <h4 class="text-xl font-bold text-white">Detalle del Pedido</h4>
                <button onclick="openModal('modal-add-product-order')" class="text-primary font-bold text-sm flex items-center gap-1 hover:text-primary-fixed-dim transition-colors">
                    <span class="material-symbols-outlined text-sm">add_circle</span>
                    Añadir Producto
                </button>
            </div>
            
            <div class="flex-1 overflow-auto space-y-4">
                <!-- Order Item -->
                <div class="bg-surface-container-highest/50 p-4 rounded-xl flex justify-between items-center border border-white/5 hover:border-white/10 transition-colors">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-surface-container-low rounded-lg flex items-center justify-center font-bold text-lg text-white">
                            2x
                        </div>
                        <div>
                            <p class="font-bold text-white text-lg">Hamburguesa Premium</p>
                            <p class="text-xs text-on-surface-variant flex items-center gap-1">
                                <span class="material-symbols-outlined text-[14px]">edit_note</span> Sin cebolla
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-6">
                        <p class="text-lg font-bold text-white">$25.98</p>
                        <button class="text-error hover:text-error-container p-2 rounded-lg bg-error/10 transition-colors">
                            <span class="material-symbols-outlined">delete</span>
                        </button>
                    </div>
                </div>

                <!-- Order Item -->
                <div class="bg-surface-container-highest/50 p-4 rounded-xl flex justify-between items-center border border-white/5 hover:border-white/10 transition-colors">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-surface-container-low rounded-lg flex items-center justify-center font-bold text-lg text-white">
                            1x
                        </div>
                        <div>
                            <p class="font-bold text-white text-lg">Cerveza Artesanal</p>
                            <p class="text-xs text-on-surface-variant">Bebidas</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-6">
                        <p class="text-lg font-bold text-white">$5.99</p>
                        <button class="text-error hover:text-error-container p-2 rounded-lg bg-error/10 transition-colors">
                            <span class="material-symbols-outlined">delete</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Summary (Right Side) -->
        <aside class="flex flex-col gap-6">
            <div class="bg-surface-container-low rounded-2xl p-8 border border-white/5 group">
                <h4 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">receipt_long</span>
                    Resumen
                </h4>
                <div class="space-y-4 text-sm mb-6">
                    <div class="flex justify-between text-on-surface-variant">
                        <span>Subtotal</span>
                        <span class="font-bold text-white">$31.97</span>
                    </div>
                    <div class="flex justify-between text-on-surface-variant">
                        <span>Impuestos (10%)</span>
                        <span class="font-bold text-white">$3.20</span>
                    </div>
                    <div class="flex justify-between text-on-surface-variant">
                        <span>Propina sugerida</span>
                        <span class="font-bold text-white">$3.20</span>
                    </div>
                </div>
                <div class="pt-6 border-t border-white/5 flex justify-between items-center mb-6">
                    <span class="text-lg font-bold text-on-surface">Total</span>
                    <span class="text-3xl font-black text-primary">$38.37</span>
                </div>
                <button onclick="window.location.href='{{ route('admin.checkout', ['id' => $mesaId ?? '4']) }}'" class="w-full py-4 rounded-xl bg-primary text-on-primary font-bold shadow-lg shadow-primary/20 hover:scale-[0.98] transition-all">
                    Cobrar Mesa
                </button>
            </div>
            
            <div class="bg-surface-container-low rounded-2xl p-6 border border-white/5 flex gap-4">
                <div class="w-12 h-12 rounded-full bg-emerald-500/10 flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined text-emerald-400">person</span>
                </div>
                <div>
                    <h5 class="font-bold text-white text-sm">Elena Martínez</h5>
                    <p class="text-xs text-on-surface-variant">Mesera a cargo</p>
                </div>
            </div>
        </aside>
    </div>
</div>

*** End Patch

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function(){
        try{
            const btn = document.getElementById('crear-pedido-from-menu');
            if (btn) btn.addEventListener('click', function(){
                try{
                    const items = JSON.parse(sessionStorage.getItem('pending_order_items') || '[]');
                    const mesa = sessionStorage.getItem('selected_mesa_id') || '{{ $mesaId ?? "0" }}';
                    if (!items.length){ alert('No hay productos agregados.'); return; }
                    // keep items in sessionStorage and navigate to pedido view where they'll be rendered
                    window.location.href = '/admin/mesas/' + encodeURIComponent(mesa) + '/pedido';
                }catch(e){ console.debug('crear pedido err', e); }
            });

            // If arriving at pedido and there are pending items, render them
            try{
                const pending = JSON.parse(sessionStorage.getItem('pending_order_items') || '[]');
                if (pending && pending.length){
                    // replace order items list
                    const list = document.querySelector('.flex-1.overflow-auto.space-y-4');
                    if (list){
                        list.innerHTML = '';
                        pending.forEach(it => {
                            const node = document.createElement('div');
                            node.className = 'bg-surface-container-highest/50 p-4 rounded-xl flex justify-between items-center border border-white/5 hover:border-white/10 transition-colors';
                            node.innerHTML = `<div class="flex items-center gap-4"><div class="w-12 h-12 bg-surface-container-low rounded-lg flex items-center justify-center font-bold text-lg text-white">${it.cantidad}x</div><div><p class="font-bold text-white text-lg">${it.name}</p></div></div><div class="flex items-center gap-6"><p class="text-lg font-bold text-white">${it.precio ? ('$'+Number(it.precio).toLocaleString('es-CO')) : '-'}</p></div>`;
                            list.appendChild(node);
                        });
                        // remove pending items after rendering so user doesn't duplicate
                        sessionStorage.removeItem('pending_order_items');
                    }
                }
            }catch(e){}
        }catch(e){ console.debug('pedido init err', e); }
    });
</script>
@endpush

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function(){
        try{
            // open menu if requested via sessionStorage flag or query param ?menu=1
            const shouldOpen = (sessionStorage.getItem('open_menu_after_nav') === '1') || (new URLSearchParams(window.location.search).get('menu') === '1');
            if (sessionStorage.getItem('open_menu_after_nav') === '1') sessionStorage.removeItem('open_menu_after_nav');
            if (shouldOpen){
                // optionally keep selected mesa id
                const mid = sessionStorage.getItem('selected_mesa_id');
                // open modal to add product
                const openBtn = document.querySelector("button[onclick*=" + "\"openModal('modal-add-product-order'\")");
                if (openBtn) openBtn.click();
                else {
                    // fallback: directly show modal element
                    const m = document.getElementById('modal-add-product-order');
                    if (m) {
                        m.classList.remove('hidden'); m.classList.remove('scale-95'); m.classList.add('scale-100');
                        const overlay = document.getElementById('modal-overlay'); if (overlay) { overlay.classList.remove('hidden'); overlay.classList.add('flex'); overlay.classList.remove('opacity-0'); overlay.classList.add('opacity-100'); }
                    }
                }
            }
        }catch(e){ console.debug('open menu after nav err', e); }
    });
</script>
@endpush
