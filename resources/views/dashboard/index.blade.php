@extends('layouts.admin')

@section('title', 'Esencia Retro - Dashboard')

@section('content')
<div class="p-8 flex-1">
    <!-- Header -->
    <header class="flex justify-between items-center mb-10">
        <div>
            <h2 class="text-3xl font-extrabold tracking-tight text-white">Dashboard</h2>
            <p class="text-on-surface-variant text-sm mt-1">Control de operaciones para Esencia Retro</p>
        </div>
        <div class="flex items-center gap-4 w-1/3">
            <div class="relative w-full">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">search</span>
                <input type="text" placeholder="Buscar pedido, mesa o producto..."
                       class="w-full bg-surface-container-low border border-outline-variant/10 rounded-xl py-3 pl-12 pr-4 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
            </div>
        </div>
    </header>

    <!-- Summary Grid (Bento Style) -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
        <div class="bg-surface-container-low p-6 rounded-xl relative overflow-hidden group">
            <div class="relative z-10">
                <p class="font-['Inter'] uppercase tracking-widest text-[10px] text-slate-500 mb-4">Ventas del Día</p>
                <h3 class="text-3xl font-bold text-white">${{ number_format($ventasDia, 2) }}</h3>
                <div class="flex items-center gap-1 text-emerald-400 text-xs font-bold mt-2">
                    <span class="material-symbols-outlined text-sm">trending_up</span>
                    Hoy
                </div>
            </div>
            <div class="absolute -right-4 -bottom-4 text-slate-400 opacity-20 group-hover:scale-110 transition-transform duration-500">
                <span class="material-symbols-outlined text-9xl text-white">payments</span>
            </div>
        </div>

        <div class="bg-surface-container-low p-6 rounded-xl relative overflow-hidden group">
            <div class="relative z-10">
                <p class="font-['Inter'] uppercase tracking-widest text-[10px] text-slate-500 mb-4">Pedidos Activos</p>
                <h3 class="text-3xl font-bold text-white">{{ $pedidosActivos }}</h3>
                <div class="flex items-center gap-1 text-primary text-xs font-bold mt-2">
                    <span class="material-symbols-outlined text-sm">restaurant</span>
                    En curso
                </div>
            </div>
            <div class="absolute -right-4 -bottom-4 text-slate-400 opacity-20 group-hover:scale-110 transition-transform duration-500">
                <span class="material-symbols-outlined text-9xl text-white">order_approve</span>
            </div>
        </div>

        <div class="bg-surface-container-low p-6 rounded-xl relative overflow-hidden group">
            <div class="relative z-10">
                <p class="font-['Inter'] uppercase tracking-widest text-[10px] text-slate-500 mb-4">Producto Más Vendido</p>
                <h3 class="text-xl font-bold text-white leading-tight truncate" title="{{ $topProducto }}">{{ $topProducto }}</h3>
                <p class="text-on-surface-variant text-sm mt-1">{{ $topProductoQty }} unidades</p>
            </div>
            <div class="absolute -right-4 -bottom-4 text-slate-400 opacity-20 group-hover:scale-110 transition-transform duration-500">
                <span class="material-symbols-outlined text-9xl text-white">lunch_dining</span>
            </div>
        </div>

        <div class="bg-surface-container-low p-6 rounded-xl relative overflow-hidden group border border-error/5">
            <div class="relative z-10">
                <p class="font-['Inter'] uppercase tracking-widest text-[10px] text-slate-500 mb-4">Alertas de Inventario</p>
                <h3 class="text-3xl font-bold text-error">{{ $alertasInventario }}</h3>
                <p class="text-on-surface-variant text-sm mt-1">Artículos críticos</p>
            </div>
            <div class="absolute -right-4 -bottom-4 opacity-20 group-hover:scale-110 transition-transform duration-500">
                <span class="material-symbols-outlined text-9xl text-error">warning</span>
            </div>
        </div>
    </div>

    <!-- Main Layout Split -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Table Status Grid (2/3) -->
        <div class="lg:col-span-2">
            <div class="flex justify-between items-end mb-6">
                <div>
                    <h4 class="text-xl font-bold text-white">Estado de Mesas</h4>
                    <p class="text-sm text-on-surface-variant">Vista en tiempo real del salón principal ({{ $mesas->count() }} mesas)</p>
                </div>
                <div class="flex gap-4 text-[10px] font-bold uppercase tracking-widest">
                    <div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-secondary"></span> Libre</div>
                    <div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-primary"></span> Ocupada</div>
                </div>
            </div>

            <div id="dashboard-mesas-grid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-4 max-h-[600px] overflow-y-auto pr-2 custom-scrollbar">
                @foreach($mesas as $m)
                    @php
                        $isOccupied = $m->latestFactura && !in_array(strtolower($m->latestFactura->estatus), ['pagado', 'pagada']);
                    @endphp
                    @if($isOccupied)
                        <div class="bg-surface-container/60 backdrop-blur-md p-5 rounded-2xl border border-white/10 hover:border-primary/30 transition-all duration-300 hover:-translate-y-1 shadow-lg group active-glow">
                            <div class="flex justify-between items-center mb-4">
                                <span class="text-lg font-bold text-white">{{ $m->nombre }}</span>
                                <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold tracking-wider uppercase bg-primary/10 text-primary border border-primary/20">
                                    <span class="w-1.5 h-1.5 rounded-full bg-primary shadow-[0_0_8px_rgba(234,188,78,0.5)]"></span>
                                    Ocupada
                                </div>
                            </div>
                            <div class="flex items-center gap-2 text-on-surface-variant/80 my-3">
                                <span class="material-symbols-outlined text-base">group</span>
                                <span class="text-xs font-medium">{{ $m->capacidad ?? '-' }} personas</span>
                            </div>
                            <div class="mt-4 pt-4 border-t border-white/5 flex justify-between items-center gap-2">
                                <span class="text-sm font-bold text-white">${{ number_format($m->latestFactura->monto_total ?? 0, 2) }}</span>
                                <div class="flex gap-2">
                                    @if($activeCaja)
                                        <a href="{{ route('admin.pedido', ['id' => $m->id]) }}" class="p-2 bg-primary/10 hover:bg-primary text-primary hover:text-white rounded-xl transition-all flex items-center justify-center shadow-sm" title="Ver pedido">
                                    @else
                                        <a href="javascript:void(0)" onclick="openModal('modal-caja-cerrada-alerta')" class="p-2 bg-primary/10 hover:bg-primary text-primary hover:text-white rounded-xl transition-all flex items-center justify-center shadow-sm" title="Ver pedido">
                                    @endif
                                        <span class="material-symbols-outlined text-sm">visibility</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="bg-surface-container/60 backdrop-blur-md p-5 rounded-2xl border border-white/10 hover:border-secondary/30 transition-all duration-300 hover:-translate-y-1 shadow-lg group">
                            <div class="flex justify-between items-center mb-4">
                                <span class="text-lg font-bold text-white">{{ $m->nombre }}</span>
                                <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold tracking-wider uppercase bg-secondary/10 text-secondary border border-secondary/20">
                                    <span class="w-1.5 h-1.5 rounded-full bg-secondary"></span>
                                    Libre
                                </div>
                            </div>
                            <div class="flex items-center gap-2 text-on-surface-variant/80 my-3">
                                <span class="material-symbols-outlined text-base">group</span>
                                <span class="text-xs font-medium">{{ $m->capacidad ?? '-' }} personas</span>
                            </div>
                            <div class="mt-4 pt-4 border-t border-white/5">
                                                               @if($activeCaja)
                                    <a href="{{ route('admin.pedido', ['id' => $m->id]) }}" class="w-full text-xs font-bold py-2 px-3 rounded-xl border border-secondary/20 text-secondary hover:bg-secondary hover:text-white text-center block transition-all shadow-sm">ASIGNAR / PEDIDO</a>
                                @else
                                    <a href="javascript:void(0)" onclick="openModal('modal-caja-cerrada-alerta')" class="w-full text-xs font-bold py-2 px-3 rounded-xl border border-secondary/20 text-secondary hover:bg-secondary hover:text-white text-center block transition-all shadow-sm">ASIGNAR / PEDIDO</a>
                                @endif
                            </div>
                        </div>
                    @endif
                @endforeach

                @if(auth()->user()->rol && auth()->user()->rol->name === 'admin')
                <div onclick="window.location.href='{{ route('admin.mesas') }}'" class="bg-surface-container-low border border-white/5 rounded-xl flex flex-col items-center justify-center p-6 border-dashed border-2 hover:border-primary/50 transition-all cursor-pointer">
                    <span class="material-symbols-outlined text-3xl text-primary/50">add_circle</span>
                    <span class="text-[10px] font-bold uppercase tracking-widest text-primary/50 mt-2">Nueva Mesa</span>
                </div>
                @endif
            </div>
            @push('scripts')
            <script>
            (function(){
                // Refresh dashboard dynamically on Echo updates to maintain live state without API token overhead
                 if (window.Echo) {
                    window.Echo.channel('pedidos-canal')
                        .listen('.pedido.actualizado', (e) => {
                            console.log('Pedido actualizado recibido en dashboard:', e);
                            if (typeof window.playNotificationSound === 'function') {
                                window.playNotificationSound();
                            }
                            fetch(window.location.href)
                                .then(response => response.text())
                                .then(html => {
                                    const parser = new DOMParser();
                                    const doc = parser.parseFromString(html, 'text/html');
                                    
                                    const newGrid = doc.getElementById('dashboard-mesas-grid');
                                    const currentGrid = document.getElementById('dashboard-mesas-grid');
                                    if (newGrid && currentGrid) {
                                        currentGrid.innerHTML = newGrid.innerHTML;
                                    }
                                })
                                .catch(err => console.error('Error al actualizar dashboard:', err));
                        });
                }
            })();
            </script>
            @endpush

            <!-- Featured Image Card -->
            <div class="mt-8 rounded-2xl overflow-hidden relative h-48 group">
                <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuAlaPz2HboCnCS6DwFpP5ZuesEFktCHSP2T5niDK7cz7wCPo0nnJIyGhYWJcowKXQPZ7I8zdRA-HePPyRjf4kpSpzpwtpD9sW0bOCpRqtmRZyZ2j2wPcVRNkjwWbu5BtLEDJ7ZMAv6gSf8cAsddY9PwzDFQQU8-8dl66iqoS6RFjmucntgEOWUa1hS1EuZKiOTy9S2W8Q4dvRFn-71jnK2YioInW6L0vfQvmcwJYdVsgZtXZAartT0SnSxrrPc3OeMUNPziddqfga2T" 
                     alt="Interior" 
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-1000"/>
                <div class="absolute inset-0 bg-gradient-to-t from-background via-background/40 to-transparent p-8 flex flex-col justify-end">
                    <h5 class="text-2xl font-black text-white">Ambiente Principal</h5>
                    <p class="text-on-surface-variant text-sm max-w-md">Capacidad actual al 65%. 4 mesas disponibles para reservación inmediata.</p>
                </div>
            </div>
        </div>

        <!-- Alerts & Inventory (1/3) -->
        <div class="space-y-8">
            <section>
                <div class="flex items-center gap-3 mb-6">
                    <span class="material-symbols-outlined text-error" style="font-variation-settings: 'FILL' 1;">warning</span>
                    <h4 class="text-xl font-bold text-white">Alertas del Sistema</h4>
                </div>

                <div class="bg-surface-container-low rounded-2xl p-2 space-y-2">
                    @forelse($alertas as $alerta)
                    <div class="bg-error-container/20 p-4 rounded-xl flex items-center gap-4 border border-error/10">
                        <div class="w-12 h-12 rounded-lg bg-error-container flex items-center justify-center">
                            <span class="material-symbols-outlined text-on-error-container">kitchen</span>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-bold text-white">{{ $alerta->nombre }}</p>
                            <p class="text-xs text-on-surface-variant">Stock: {{ $alerta->stock_inicial }} {{ $alerta->unidad_medida }} (Crítico)</p>
                        </div>
                        <button class="bg-surface-container-highest p-2 rounded-lg hover:text-error transition-colors">
                            <span class="material-symbols-outlined text-sm">shopping_cart</span>
                        </button>
                    </div>
                    @empty
                    <div class="p-6 text-center text-sm text-slate-400 flex flex-col items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-emerald-400 text-3xl">check_circle</span>
                        <p>Todo está bien en el inventario.</p>
                    </div>
                    @endforelse
                </div>
            </section>
        </div>
    </div>
</div>

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

    <!-- Agregar Nueva Mesa Modal -->
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
                <input type="text" class="w-full bg-surface-container-highest border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-primary transition-all" placeholder="Ej. Barra 1, M-06">
            </div>
            <div>
                <label class="text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1 block">Capacidad Max. Pax</label>
                <input type="number" class="w-full bg-surface-container-highest border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-primary transition-all" placeholder="4">
            </div>
            <div class="flex gap-3 pt-4 border-t border-white/10 mt-6">
                <button type="button" onclick="closeModals()" class="flex-1 py-3 bg-gradient-to-br from-primary to-primary-container text-on-primary-container font-bold rounded-xl hover:scale-[0.98] transition-transform text-sm">Registrar Mesa</button>
            </div>
        </form>
    </div>
    <!-- Alerta Caja Cerrada Modal (Diseño nativo de Esencia Retro) -->
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
            Hasta que no abran caja no se puede usar el sistema de mesas.
        </p>
        <div class="flex gap-3 pt-4 border-t border-white/10">
            <button type="button" onclick="closeModals()" class="flex-1 py-3 rounded-xl border border-white/10 hover:bg-white/5 transition-colors font-bold text-sm text-white">Volver</button>
            <a href="{{ route('admin.caja') }}" class="flex-1 py-3 bg-gradient-to-br from-primary to-primary-container text-on-primary-container font-bold rounded-xl hover:scale-[0.98] transition-transform text-sm text-center flex items-center justify-center gap-1">
                <span class="material-symbols-outlined text-sm">lock_open</span>
                Abrir Caja
            </a>
        </div>
    </div>
@endpush

@endsection
