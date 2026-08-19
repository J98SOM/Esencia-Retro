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
                        <h3 class="text-3xl font-black text-white flex items-center gap-2">
                            <span>{{ $m->nombre }}</span>
                            @if($m->es_admin)
                                <span class="px-2 py-0.5 bg-red-500/20 text-red-400 rounded text-xs uppercase tracking-wider font-extrabold">Admin</span>
                            @endif
                        </h3>
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
                        @php
                            $activeCaja = \App\Models\AperturaCaja::where('estado', 'abierta')->exists();
                        @endphp
                        @if($m->es_admin && !empty($m->password))
                            @if($activeCaja)
                                <button onclick="openPasswordModal({{ $m->id }}, '{{ route('admin.pedido', ['id' => $m->id]) }}')" class="flex-1 py-2 bg-white/5 hover:bg-white/10 rounded-lg text-center text-[10px] font-bold uppercase tracking-widest block transition-colors w-full">Detalles</button>
                            @else
                                <button type="button" onclick="openModal('modal-caja-cerrada-alerta')" class="flex-1 py-2 bg-white/5 hover:bg-white/10 rounded-lg text-center text-[10px] font-bold uppercase tracking-widest block transition-colors w-full">Detalles</button>
                            @endif
                        @else
                            @if($activeCaja)
                                <a href="{{ route('admin.pedido', ['id' => $m->id]) }}" class="flex-1 py-2 bg-white/5 hover:bg-white/10 rounded-lg text-center text-[10px] font-bold uppercase tracking-widest block transition-colors">Detalles</a>
                            @else
                                <button type="button" onclick="openModal('modal-caja-cerrada-alerta')" class="flex-1 py-2 bg-white/5 hover:bg-white/10 rounded-lg text-center text-[10px] font-bold uppercase tracking-widest block transition-colors w-full">Detalles</button>
                            @endif
                        @endif
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
            <div class="flex items-center gap-2 py-2">
                <input id="es_admin" name="es_admin" type="checkbox" value="1" onchange="document.getElementById('password_container').style.display = this.checked ? 'block' : 'none'" class="rounded bg-surface-container-highest border border-white/10 text-primary focus:ring-primary focus:ring-2">
                <label for="es_admin" class="text-xs font-bold text-on-surface-variant uppercase tracking-widest cursor-pointer select-none">Mesa Administrativa</label>
            </div>
            <div id="password_container" style="display: none;" class="mb-4">
                <label class="text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1 block">Contraseña</label>
                <input name="password" type="password" class="w-full bg-surface-container-highest border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-primary transition-all" placeholder="Introduce una contraseña">
            </div>
            <div class="flex gap-3 pt-4 border-t border-white/10 mt-6">
                <button type="button" onclick="closeModals()" class="flex-1 py-3 rounded-xl border border-white/10 hover:bg-white/5 transition-colors font-bold text-sm text-white">Cancelar</button>
                <button type="submit" class="flex-1 py-3 bg-gradient-to-br from-primary to-primary-container text-on-primary-container font-bold rounded-xl hover:scale-[0.98] transition-transform text-sm">Registrar Mesa</button>
            </div>
        </form>
    </div>
    
    <!-- Alerta Caja Cerrada Modal -->
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
        function closePasswordModal() {
            document.getElementById('modalPasswordOverlay').classList.add('opacity-0', 'pointer-events-none');
            document.getElementById('modalPasswordContent').classList.add('scale-95', 'opacity-0');
            document.getElementById('password_input').value = '';
            document.getElementById('password_error').classList.add('hidden');
        }

        let currentAdminMesaUrl = '';
        let currentAdminMesaId = '';

        function openPasswordModal(id, url) {
            currentAdminMesaId = id;
            currentAdminMesaUrl = url;
            document.getElementById('password_error').classList.add('hidden');
            document.getElementById('password_input').value = '';
            
            document.getElementById('modalPasswordOverlay').classList.remove('opacity-0', 'pointer-events-none');
            document.getElementById('modalPasswordContent').classList.remove('scale-95', 'opacity-0');
            
            setTimeout(() => {
                document.getElementById('password_input').focus();
            }, 100);
        }

        function verifyPassword(e) {
            e.preventDefault();
            const password = document.getElementById('password_input').value;
            const btn = document.getElementById('verify_btn');
            
            if (!password) {
                document.getElementById('password_error').textContent = 'Ingresa la contraseña.';
                document.getElementById('password_error').classList.remove('hidden');
                return;
            }
            
            btn.innerHTML = '<span class="material-symbols-outlined animate-spin text-sm">autorenew</span> Validando...';
            btn.disabled = true;

            fetch('/admin/mesas/' + currentAdminMesaId + '/verify-password', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ password: password })
            })
            .then(res => res.json().then(data => ({status: res.status, body: data})))
            .then(res => {
                if (res.status === 200 && res.body.success) {
                    window.location.href = currentAdminMesaUrl;
                } else {
                    document.getElementById('password_error').textContent = res.body.message || 'Contraseña incorrecta.';
                    document.getElementById('password_error').classList.remove('hidden');
                    btn.innerHTML = 'Verificar';
                    btn.disabled = false;
                }
            })
            .catch(err => {
                document.getElementById('password_error').textContent = 'Error de conexión.';
                document.getElementById('password_error').classList.remove('hidden');
                btn.innerHTML = 'Verificar';
                btn.disabled = false;
            });
        }
    </script>
    
    <!-- Modal Password -->
    <div id="modalPasswordOverlay" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 opacity-0 pointer-events-none transition-opacity duration-300 flex items-center justify-center p-4">
        <div id="modalPasswordContent" class="bg-surface-container-high border border-white/10 rounded-3xl p-8 max-w-sm w-full transform scale-95 opacity-0 transition-all duration-300 shadow-2xl relative">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-black text-white">Mesa Administrador</h2>
                <button onclick="closePasswordModal()" class="text-on-surface-variant hover:text-white transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form onsubmit="verifyPassword(event)" class="space-y-4">
                <div>
                    <label class="text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1 block">Contraseña</label>
                    <input id="password_input" type="password" class="w-full bg-surface-container-highest border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-primary transition-all" placeholder="******">
                    <p id="password_error" class="text-red-400 text-xs mt-1 hidden"></p>
                </div>
                <div class="pt-4 border-t border-white/10 mt-6">
                    <button id="verify_btn" type="submit" class="w-full py-3 bg-gradient-to-br from-primary to-primary-container text-on-primary-container font-bold rounded-xl hover:scale-[0.98] transition-transform text-sm flex justify-center items-center gap-2">Verificar</button>
                </div>
            </form>
        </div>
    </div>
@endpush

@endsection
