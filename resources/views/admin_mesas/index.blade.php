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
                <input id="mesas-search" class="w-full bg-surface-container-low border-none focus:ring-2 focus:ring-primary text-on-surface placeholder:text-slate-500 rounded-xl px-4 py-3 pl-11 outline-none" placeholder="Buscar mesa..." type="text"/>
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-500">search</span>
            </div>
            @if(auth()->user()->rol && auth()->user()->rol->name === 'admin')
            <button onclick="openModal('modal-add-table')" class="p-3 bg-primary rounded-xl text-on-primary font-bold hover:scale-95 transition-all whitespace-nowrap">
                Nueva Mesa
            </button>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4">
            <div class="p-3 rounded bg-emerald-700/10 text-emerald-300">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4">
            <div class="p-3 rounded bg-error/20 text-error">
                {{ $errors->first() }}
            </div>
        </div>
    @endif

    <!-- Status Filter Bar -->
    <div class="flex flex-wrap gap-4 mb-10" id="mesas-filters">
        <button id="btn-filter-all" data-status="all" class="filter-btn px-6 py-2 rounded-full bg-primary text-on-primary font-bold text-sm shadow-xl shadow-primary/10">Todas (<span id="count-all">{{ $mesas->count() }}</span>)</button>
        <button id="btn-filter-libres" data-status="libre" class="filter-btn px-6 py-2 rounded-full bg-surface-container-high text-on-surface-variant hover:text-on-surface transition-colors font-bold text-sm">Libres (<span id="count-libres">{{ $mesas->filter(fn($m) => !($m->latestFactura && !in_array(strtolower($m->latestFactura->estatus), ['pagado', 'pagada'])))->count() }}</span>)</button>
        <button id="btn-filter-ocupadas" data-status="ocupada" class="filter-btn px-6 py-2 rounded-full bg-surface-container-high text-on-surface-variant hover:text-on-surface transition-colors font-bold text-sm">Ocupadas (<span id="count-ocupadas">{{ $mesas->filter(fn($m) => $m->latestFactura && !in_array(strtolower($m->latestFactura->estatus), ['pagado', 'pagada']))->count() }}</span>)</button>
    </div>

    <!-- Tables Grid (loaded directly from backend) -->
    <div id="mesas-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($mesas as $m)
            @php
                $isOccupied = $m->latestFactura && !in_array(strtolower($m->latestFactura->estatus), ['pagado', 'pagada']);
                $status = $isOccupied ? 'ocupada' : 'libre';
            @endphp
            <div class="mesa-card-item group relative {{ $isOccupied ? 'bg-surface-container-highest' : 'bg-surface-container-low' }} rounded-3xl p-6 border border-white/5 overflow-hidden transition-all duration-300 hover:shadow-2xl hover:shadow-primary/10" data-status="{{ $status }}" data-nombre="{{ strtolower($m->nombre) }}">
                <div class="flex justify-between items-start mb-8">
                    <div>
                        <span class="text-slate-500 font-['Inter'] uppercase tracking-widest text-[10px] block mb-1">Zona General</span>
                        <h3 class="text-3xl font-black text-white">{{ $m->nombre }}</h3>
                    </div>
                    @if($isOccupied)
                        <div class="px-3 py-1 bg-primary/20 rounded-lg"><span class="text-[10px] font-black text-primary uppercase tracking-widest">Ocupada</span></div>
                    @else
                        <div class="px-3 py-1 bg-emerald-500/10 rounded-lg"><span class="text-[10px] font-black text-emerald-400 uppercase tracking-widest">Libre</span></div>
                    @endif
                </div>
                <div class="space-y-4">
                    <div class="flex justify-between items-end">
                        <div class="text-on-surface-variant">
                            <p class="text-[10px] uppercase tracking-widest font-bold">Capacidad</p>
                            <p class="text-2xl font-bold text-primary">{{ $m->capacidad ?? '-' }} pax</p>
                        </div>
                    </div>
                    <div class="pt-4 border-t border-white/5 flex gap-2">
                        <a href="{{ route('admin.pedido', ['id' => $m->id]) }}" class="flex-1 py-2 bg-white/5 hover:bg-white/10 rounded-lg text-center text-[10px] font-bold uppercase tracking-widest block transition-colors">Detalles</a>
                        @if(auth()->user()->rol && auth()->user()->rol->name === 'admin')
                        <form method="POST" action="{{ route('admin.mesas.delete', ['id' => $m->id]) }}" onsubmit="return confirm('¿Eliminar mesa?')">
                            @csrf
                            <button type="submit" class="w-full py-2 bg-primary/10 text-primary hover:bg-primary/20 rounded-lg text-[10px] font-bold uppercase tracking-widest transition-colors">Eliminar</button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

@push('modals')
    <!-- Add Table Modal -->
    <div id="modal-add-table" class="modal-content hidden bg-surface-container-low border border-white/10 p-8 rounded-3xl w-full max-w-sm shadow-2xl transform scale-95 transition-transform duration-300">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-black text-white">Nueva Mesa</h3>
            <button onclick="closeModals()" class="text-outline hover:text-white transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.mesas.store') }}" class="space-y-4">
            @csrf
            @php
                $allMesas = \App\Models\Mesa::all();
                $nextMesaNum = 1;
                foreach ($allMesas as $mesa) {
                    if (preg_match('/^Mesa\s+(\d+)$/i', trim($mesa->nombre), $matches)) {
                        $num = (int)$matches[1];
                        if ($num >= $nextMesaNum) {
                            $nextMesaNum = $num + 1;
                        }
                    }
                }
            @endphp
            <div>
                <label class="text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1 block">Identificador o Número (opcional)</label>
                <input name="nombre" type="text" class="w-full bg-surface-container-highest border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-primary transition-all" placeholder="Ej. Mesa {{ $nextMesaNum }} (Dejar vacío para usar este por defecto)">
            </div>
            <div>
                <label class="text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1 block">Capacidad Max. Pax</label>
                <input name="capacidad" type="number" class="w-full bg-surface-container-highest border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-primary transition-all" placeholder="4">
            </div>
            <div class="flex gap-3 pt-4 border-t border-white/10 mt-6">
                <button type="button" onclick="closeModals()" class="flex-1 py-3 rounded-xl border border-white/10 hover:bg-white/5 transition-colors font-bold text-sm text-white">Cancelar</button>
                <button type="submit" class="flex-1 py-3 bg-gradient-to-br from-primary to-primary-container text-on-primary-container font-bold rounded-xl hover:scale-[0.98] transition-transform text-sm">Registrar Mesa</button>
            </div>
        </form>
    </div>
@endpush

@push('scripts')
<script>
(function(){
    let currentFilter = 'all';

    function initMesasFilters() {
        const searchInput = document.getElementById('mesas-search');
        const cards = document.querySelectorAll('.mesa-card-item');
        const filterButtons = document.querySelectorAll('#mesas-filters button');

        // Rebind search input
        const cleanSearch = searchInput.cloneNode(true);
        // Copy value over to the clone to preserve user typing
        cleanSearch.value = searchInput.value;
        searchInput.parentNode.replaceChild(cleanSearch, searchInput);
        
        function filterList() {
            const query = cleanSearch.value.toLowerCase().trim();
            cards.forEach(card => {
                const status = card.getAttribute('data-status') || '';
                const nombre = card.getAttribute('data-nombre') || '';
                const matchesQuery = nombre.includes(query);
                const matchesFilter = (currentFilter === 'all') || (status === currentFilter);

                if (matchesQuery && matchesFilter) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        cleanSearch.addEventListener('input', filterList);

        // Rebind buttons
        filterButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                filterButtons.forEach(b => b.classList.remove('bg-primary', 'text-on-primary'));
                btn.classList.add('bg-primary', 'text-on-primary');
                currentFilter = btn.getAttribute('data-status');
                filterList();
            });
        });
        
        // Restore active filter button visually
        filterButtons.forEach(b => {
            if (b.getAttribute('data-status') === currentFilter) {
                b.classList.add('bg-primary', 'text-on-primary');
            } else {
                b.classList.remove('bg-primary', 'text-on-primary');
            }
        });
        filterList();
    }

    document.addEventListener('DOMContentLoaded', () => {
        initMesasFilters();

        if (window.Echo) {
            window.Echo.channel('pedidos-canal')
                .listen('.pedido.actualizado', (e) => {
                    console.log('Pedido actualizado recibido en mesas:', e);
                    if (typeof window.playNotificationSound === 'function') {
                        window.playNotificationSound();
                    }
                    fetch(window.location.href)
                        .then(response => response.text())
                        .then(html => {
                            const parser = new DOMParser();
                            const doc = parser.parseFromString(html, 'text/html');
                            
                            const newGrid = doc.getElementById('mesas-grid');
                            const currentGrid = document.getElementById('mesas-grid');
                            if (newGrid && currentGrid) {
                                currentGrid.innerHTML = newGrid.innerHTML;
                            }
                            
                            const newFilters = doc.getElementById('mesas-filters');
                            const currentFilters = document.getElementById('mesas-filters');
                            if (newFilters && currentFilters) {
                                currentFilters.innerHTML = newFilters.innerHTML;
                            }

                            initMesasFilters();
                        })
                        .catch(err => console.error('Error al actualizar mesas:', err));
                });
        }
    });
})();
</script>
@endpush

@endsection
