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
                <h3 class="text-3xl font-bold text-white">$12,458</h3>
                <div class="flex items-center gap-1 text-emerald-400 text-xs font-bold mt-2">
                    <span class="material-symbols-outlined text-sm">trending_up</span>
                    +12.5%
                </div>
            </div>
            <div class="absolute -right-4 -bottom-4 text-slate-400 opacity-20 group-hover:scale-110 transition-transform duration-500">
                <span class="material-symbols-outlined text-9xl text-white">payments</span>
            </div>
        </div>

        <div class="bg-surface-container-low p-6 rounded-xl relative overflow-hidden group">
            <div class="relative z-10">
                <p class="font-['Inter'] uppercase tracking-widest text-[10px] text-slate-500 mb-4">Pedidos Activos</p>
                <h3 class="text-3xl font-bold text-white">24</h3>
                <div class="flex items-center gap-1 text-primary text-xs font-bold mt-2">
                    <span class="material-symbols-outlined text-sm">add_circle</span>
                    +8 nuevos
                </div>
            </div>
            <div class="absolute -right-4 -bottom-4 text-slate-400 opacity-20 group-hover:scale-110 transition-transform duration-500">
                <span class="material-symbols-outlined text-9xl text-white">order_approve</span>
            </div>
        </div>

        <div class="bg-surface-container-low p-6 rounded-xl relative overflow-hidden group">
            <div class="relative z-10">
                <p class="font-['Inter'] uppercase tracking-widest text-[10px] text-slate-500 mb-4">Producto Más Vendido</p>
                <h3 class="text-xl font-bold text-white leading-tight">Hamburguesa Premium</h3>
                <p class="text-on-surface-variant text-sm mt-1">48 unidades vendidas</p>
            </div>
            <div class="absolute -right-4 -bottom-4 text-slate-400 opacity-20 group-hover:scale-110 transition-transform duration-500">
                <span class="material-symbols-outlined text-9xl text-white">lunch_dining</span>
            </div>
        </div>

        <div class="bg-surface-container-low p-6 rounded-xl relative overflow-hidden group border border-error/5">
            <div class="relative z-10">
                <p class="font-['Inter'] uppercase tracking-widest text-[10px] text-slate-500 mb-4">Alertas de Inventario</p>
                <h3 class="text-3xl font-bold text-error">3</h3>
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
                    <p class="text-sm text-on-surface-variant">Vista en tiempo real del salón principal (17 mesas)</p>
                </div>
                <div class="flex gap-4 text-[10px] font-bold uppercase tracking-widest">
                    <div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-secondary"></span> Libre</div>
                    <div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-primary-container"></span> Ocupada</div>
                    <div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-tertiary-container"></span> Reservada</div>
                </div>
            </div>

            @php
            $mesasMock = [
                ['id'=>1, 'zona'=>'Salón Principal', 'numero'=>'01', 'estado'=>'Ocupada', 'pax'=>4, 'total'=>1240.0, 'tiempo'=>'01:14:00'],
                ['id'=>2, 'zona'=>'Salón Principal', 'numero'=>'02', 'estado'=>'Libre', 'pax'=>2, 'tiempo'=>'', 'total'=>0],
                ['id'=>3, 'zona'=>'Salón Principal', 'numero'=>'03', 'estado'=>'Reservada', 'cliente'=>'G. Rossi (4pax)', 'hora'=>'Hoy - 20:30', 'pax'=>0, 'total'=>0],
                ['id'=>4, 'zona'=>'Salón Principal', 'numero'=>'04', 'estado'=>'Ocupada', 'pax'=>6, 'total'=>4100.0, 'tiempo'=>'00:42:15'],
                ['id'=>5, 'zona'=>'Salón Principal', 'numero'=>'05', 'estado'=>'Libre', 'pax'=>4, 'tiempo'=>'', 'total'=>0],
                ['id'=>6, 'zona'=>'Salón Principal', 'numero'=>'06', 'estado'=>'Libre', 'pax'=>4, 'tiempo'=>'', 'total'=>0],
                ['id'=>7, 'zona'=>'Salón Principal', 'numero'=>'07', 'estado'=>'Ocupada', 'pax'=>2, 'total'=>850.0, 'tiempo'=>'00:15:00'],
                ['id'=>8, 'zona'=>'Salón Principal', 'numero'=>'08', 'estado'=>'Libre', 'pax'=>4, 'tiempo'=>'', 'total'=>0],
                ['id'=>9, 'zona'=>'Salón Principal', 'numero'=>'09', 'estado'=>'Libre', 'pax'=>2, 'tiempo'=>'', 'total'=>0],
                ['id'=>10, 'zona'=>'Salón Principal', 'numero'=>'10', 'estado'=>'Reservada', 'cliente'=>'A. Gomez (2pax)', 'hora'=>'Hoy - 21:00', 'pax'=>0, 'total'=>0],
                ['id'=>11, 'zona'=>'Salón Principal', 'numero'=>'11', 'estado'=>'Libre', 'pax'=>4, 'tiempo'=>'', 'total'=>0],
                ['id'=>12, 'zona'=>'Salón Principal', 'numero'=>'12', 'estado'=>'Ocupada', 'pax'=>4, 'total'=>2300.0, 'tiempo'=>'02:10:05'],
                ['id'=>13, 'zona'=>'Terraza', 'numero'=>'13', 'estado'=>'Libre', 'pax'=>2, 'tiempo'=>'', 'total'=>0],
                ['id'=>14, 'zona'=>'Terraza', 'numero'=>'14', 'estado'=>'Ocupada', 'pax'=>6, 'total'=>5400.0, 'tiempo'=>'01:45:00'],
                ['id'=>15, 'zona'=>'Terraza', 'numero'=>'15', 'estado'=>'Libre', 'pax'=>4, 'tiempo'=>'', 'total'=>0],
                ['id'=>16, 'zona'=>'VIP', 'numero'=>'16', 'estado'=>'Libre', 'pax'=>8, 'tiempo'=>'', 'total'=>0],
                ['id'=>17, 'zona'=>'VIP', 'numero'=>'17', 'estado'=>'Reservada', 'cliente'=>'M. Lopez (10pax)', 'hora'=>'Mañana - 22:00', 'pax'=>0, 'total'=>0],
            ];
            @endphp

            <div id="dashboard-mesas-grid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-4 max-h-[600px] overflow-y-auto pr-2 custom-scrollbar">
                <!-- Mesa Items loop -->
                @foreach($mesasMock as $m)
                    @if($m['estado'] === 'Ocupada')
                    <div class="bg-surface-container/60 backdrop-blur-md p-5 rounded-2xl border border-white/10 hover:border-primary/30 transition-all duration-300 hover:-translate-y-1 shadow-lg group active-glow">
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-lg font-bold text-white">M-{{ $m['numero'] }}</span>
                            <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold tracking-wider uppercase bg-primary-container/10 text-primary-container border border-primary-container/20">
                                <span class="w-1.5 h-1.5 rounded-full bg-primary-container shadow-[0_0_8px_rgba(208,188,255,0.5)]"></span>
                                Ocupada
                            </div>
                        </div>
                        <div class="flex items-center gap-2 text-on-surface-variant/80 my-3">
                            <span class="material-symbols-outlined text-base">group</span>
                            <span class="text-xs font-medium">{{ $m['pax'] }} personas</span>
                        </div>
                        <div class="mt-4 pt-4 border-t border-white/5 flex justify-between items-center gap-2">
                            <span class="text-sm font-bold text-white">${{ number_format($m['total'], 2) }}</span>
                            <div class="flex gap-2">
                                <a href="{{ route('admin.pedido', ['id' => $m['id']]) }}" class="p-2 bg-primary/10 hover:bg-primary text-primary hover:text-white rounded-xl transition-all flex items-center justify-center shadow-sm" title="Ver pedido">
                                    <span class="material-symbols-outlined text-sm">visibility</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    @elseif($m['estado'] === 'Libre')
                    <div class="bg-surface-container/60 backdrop-blur-md p-5 rounded-2xl border border-white/10 hover:border-secondary/30 transition-all duration-300 hover:-translate-y-1 shadow-lg group">
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-lg font-bold text-white">M-{{ $m['numero'] }}</span>
                            <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold tracking-wider uppercase bg-secondary/10 text-secondary border border-secondary/20">
                                <span class="w-1.5 h-1.5 rounded-full bg-secondary"></span>
                                Libre
                            </div>
                        </div>
                        <div class="flex items-center gap-2 text-on-surface-variant/80 my-3">
                            <span class="material-symbols-outlined text-base">group</span>
                            <span class="text-xs font-medium">{{ $m['pax'] }} personas</span>
                        </div>
                        <div class="mt-4 pt-4 border-t border-white/5">
                            <button onclick="openModal('modal-open-table')" class="w-full text-xs font-bold py-2 px-3 rounded-xl border border-secondary/20 text-secondary hover:bg-secondary hover:text-white transition-all shadow-sm">ASIGNAR</button>
                        </div>
                    </div>
                    @elseif($m['estado'] === 'Reservada')
                    <div class="bg-surface-container/60 backdrop-blur-md p-5 rounded-2xl border border-white/10 hover:border-tertiary/30 transition-all duration-300 hover:-translate-y-1 shadow-lg group">
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-lg font-bold text-white">M-{{ $m['numero'] }}</span>
                            <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold tracking-wider uppercase bg-tertiary-container/10 text-tertiary-container border border-tertiary-container/20">
                                <span class="w-1.5 h-1.5 rounded-full bg-tertiary-container shadow-[0_0_8px_rgba(255,180,171,0.3)]"></span>
                                Reservada
                            </div>
                        </div>
                        <div class="flex items-center gap-2 text-on-surface-variant/80 my-3">
                            <span class="material-symbols-outlined text-base">calendar_today</span>
                            <span class="text-xs font-medium">{{ $m['hora'] }}</span>
                        </div>
                        <div class="mt-4 pt-4 border-t border-white/5">
                            <p class="text-[10px] text-on-surface-variant truncate">Cliente: {{ explode('(', $m['cliente'])[0] }}</p>
                        </div>
                    </div>
                    @endif
                @endforeach


                @if(auth()->user()->rol && auth()->user()->rol->name === 'admin')
                <div onclick="openModal('modal-add-table')" class="bg-surface-container-low border border-white/5 rounded-xl flex flex-col items-center justify-center p-6 border-dashed border-2 hover:border-primary/50 transition-all cursor-pointer">
                    <span class="material-symbols-outlined text-3xl text-primary/50">add_circle</span>
                    <span class="text-[10px] font-bold uppercase tracking-widest text-primary/50 mt-2">Nueva Mesa</span>
                </div>
                @endif
            </div>

            @push('scripts')
            <script>
            (function(){
                const grid = document.getElementById('dashboard-mesas-grid');
                if (!grid) return;
                function apiBase(){ return (window.VITE_API_URL || window.API_BASE || '/api').replace(/\/$/, ''); }

                function renderSmallMesa(m){
                    const id = m.id || '';
                    const nombre = m.nombre || m.identifier || ('Mesa ' + id);
                    const capacidad = m.capacidad || m.capacity || '';
                    const estado = (m.estado || m.status || 'libre').toString().toLowerCase();
                    
                    let bgClass = 'bg-secondary/10';
                    let textClass = 'text-secondary';
                    let borderClass = 'border-secondary/20';
                    let dotBg = 'bg-secondary';
                    
                    if (estado.includes('ocup')) {
                        bgClass = 'bg-primary/10';
                        textClass = 'text-primary';
                        borderClass = 'border-primary/20';
                        dotBg = 'bg-primary shadow-[0_0_8px_rgba(208,188,255,0.5)]';
                    } else if (estado.includes('reserv')) {
                        bgClass = 'bg-tertiary-container/10';
                        textClass = 'text-tertiary-container';
                        borderClass = 'border-tertiary-container/20';
                        dotBg = 'bg-tertiary-container shadow-[0_0_8px_rgba(255,180,171,0.3)]';
                    }

                    return `
                        <div class="bg-surface-container/60 backdrop-blur-md p-5 rounded-2xl border border-white/10 hover:border-primary/30 transition-all duration-300 hover:-translate-y-1 shadow-lg group ${estado==='ocupada' ? 'active-glow' : ''}">
                            <div class="flex justify-between items-center mb-4">
                                <span class="text-lg font-bold text-white">${nombre}</span>
                                <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold tracking-wider uppercase ${bgClass} ${textClass} border ${borderClass}">
                                    <span class="w-1.5 h-1.5 rounded-full ${dotBg}"></span>
                                    ${estado.toUpperCase()}
                                </div>
                            </div>
                            <div class="flex items-center gap-2 text-on-surface-variant/80 my-3">
                                <span class="material-symbols-outlined text-base">group</span>
                                <span class="text-xs font-medium">${capacidad || '-'} personas</span>
                            </div>
                            <div class="mt-4 pt-4 border-t border-white/5 flex justify-between items-center gap-2">
                                <a href="/admin/mesas/${id}/pedido" class="flex-1 flex items-center justify-center gap-1.5 py-2 px-3 bg-primary/10 hover:bg-primary text-primary hover:text-white rounded-xl text-xs font-bold tracking-wide transition-all shadow-sm">
                                    <span class="material-symbols-outlined text-sm">visibility</span>
                                    Detalles
                                </a>
                            </div>
                        </div>
                    `;
                }

                async function fetchDashboardMesas(){
                    grid.innerHTML = '<p class="text-sm text-slate-400">Cargando mesas…</p>';
                    try{
                        const token = localStorage.getItem('auth_token');
                        const headers = { 'Accept':'application/json' };
                        if (token) headers['Authorization'] = 'Bearer ' + token;
                        const res = await fetch(apiBase() + '/mesas', { headers });
                        if (res.status === 401) return; // let main app handle
                        const data = await res.json();
                        const list = Array.isArray(data.data) ? data.data : (data.mesas || data);
                        if (!list || !list.length) { grid.innerHTML = '<p class="text-sm text-slate-400">No hay mesas.</p>'; return; }
                        grid.innerHTML = list.map(renderSmallMesa).join('');
                        // attach delete handlers
                        document.querySelectorAll('.btn-delete-small-mesa').forEach(b=>{
                            b.addEventListener('click', async ()=>{
                                const id = b.getAttribute('data-id');
                                if (!confirm('Eliminar mesa #' + id + '?')) return;
                                try{
                                    const token = localStorage.getItem('auth_token');
                                    const headers = { 'Accept':'application/json' };
                                    if (token) headers['Authorization'] = 'Bearer ' + token;
                                    const r = await fetch(apiBase() + '/mesas/' + id, { method: 'DELETE', headers });
                                    if (r.ok) fetchDashboardMesas();
                                }catch(e){ console.error(e); }
                            });
                        });
                    }catch(e){ grid.innerHTML = '<p class="text-sm text-red-400">Error cargando mesas</p>'; }
                }

                try{ fetchDashboardMesas(); }catch(e){}
                document.addEventListener('visibilitychange', ()=>{ if (document.visibilityState === 'visible') fetchDashboardMesas(); });
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
@endpush

@endsection
